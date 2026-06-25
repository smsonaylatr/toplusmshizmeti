<?php

namespace App\Livewire;

use App\Jobs\SendVatanSms;
use App\Models\BlacklistedNumber;
use App\Models\SenderName;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use Livewire\Component;

class SmsSingle extends Component
{
    public string $recipient    = '';
    public string $senderName   = '';
    public string $smsType      = 'Normal';
    public string $sendTime     = 'Hemen Gönder';
    public string $messageType  = 'Bilgi';
    public string $template     = '';
    public string $message      = '';
    public int $charCount = 0;
    public int $smsCount  = 1;

    public function mount(): void
    {
        // Varsayılan gönderici adını yükle
        $this->senderName = SenderName::defaultForUser(auth()->id()) ?? '';
    }


    /**
     * Submit butonuna basılınca Alpine, bu metot aracılığıyla mesajı önce sync eder.
     * Textarea @blur yerine bu sayede butona direkt tıklamada da mesaj kaybolmuyor.
     */
    public function setMessage(string $value): void
    {
        $this->message   = $value;
        $this->charCount = mb_strlen($value);
        $this->smsCount  = $this->charCount > 0 ? (int) ceil($this->charCount / 160) : 1;
    }

    public function send(): void
    {
        $this->validate([
            'recipient'   => 'required|min:10',
            'senderName'  => 'required',
            'messageType' => 'required',
            'message'     => 'required|min:1',
        ]);

        $user = auth()->user();

        // Telefon numarasını normalize et: 05xx → 5xx, +905xx → 5xx
        $phone = preg_replace('/\D/', '', trim($this->recipient)); // sadece rakamlar
        if (str_starts_with($phone, '90') && strlen($phone) === 12) {
            $phone = substr($phone, 2); // 905xx → 5xx
        } elseif (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = substr($phone, 1); // 05xx → 5xx
        }

        // Kara liste kontrolü (hem ham hem normalize ile)
        $isBlacklisted = \App\Models\BlacklistedNumber::where('user_id', $user->id)
            ->where(function ($q) use ($phone) {
                $q->where('phone_number', $phone)
                  ->orWhere('phone_number', '0'.$phone)
                  ->orWhere('phone_number', $this->recipient);
            })->exists();

        if ($isBlacklisted) {
            $this->addError('recipient', 'Bu numara kara listede.');
            return;
        }

        // Kredi kontrolü
        $user->refresh();
        if ($user->sms_credits < 1) {
            $this->addError('recipient', 'SMS krediniz bulunmuyor. Lütfen kredi yükleyin.');
            return;
        }

        // DB kaydı
        $smsRecord = \App\Models\SmsMessage::create([
            'user_id'     => $user->id,
            'recipient'   => $phone,
            'sender_name' => $this->senderName,
            'message'     => $this->message,
            'status'      => 'pending',
        ]);
        $user->decrement('sms_credits', 1);

        // VatanSMS API (sync modda anlık çalışır)
        $service  = app(\App\Services\VatanSmsService::class);
        $response = $service->send1toN($this->message, [$phone], null, $this->senderName ?: null);

        if ($response['success'] ?? false) {
            $smsRecord->update(['status' => 'sent', 'sent_at' => now()]);
            \App\Models\CreditLog::record($user->id, 'sms', 'use', 1, $user->fresh()->sms_credits, 'Tek SMS gönderildi', $phone);
            session()->flash('success', 'SMS gönderildi! Kalan kredi: ' . $user->fresh()->sms_credits);
        } else {
            $smsRecord->update(['status' => 'failed']);
            // Başarısız — krediyi geri ver
            $user->increment('sms_credits', 1);
            $err = $response['description'] ?? ($response['message'] ?? 'API hatası.');
            session()->flash('warning', "SMS gönderilemedi: {$err}");
        }
        $this->reset(['recipient', 'message', 'charCount', 'smsCount']);
    }

    public function render()
    {
        $senders = SenderName::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->pluck('name')
            ->toArray();

        $templates      = SmsTemplate::where('user_id', auth()->id())->get();
        $recentMessages = SmsMessage::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.sms-single', compact('senders', 'templates', 'recentMessages'))
            ->layout('components.layouts.panel', ['title' => 'Tek Numaraya SMS']);
    }
}
