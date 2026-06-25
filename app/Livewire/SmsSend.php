<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use App\Models\CreditLog;
use App\Models\SenderName;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Services\VatanSmsService;
use Livewire\Component;

class SmsSend extends Component
{
    public string $customNumbers = '';
    public string $senderName   = '';
    public string $smsType      = 'Normal';
    public string $sendTime     = 'Hemen Gönder';
    public string $messageType  = 'Bilgi';
    public string $message      = '';
    public string $template     = '';
    public int    $charCount    = 0;
    public int    $smsCount     = 1;

    public function mount(): void
    {
        $this->senderName = SenderName::defaultForUser(auth()->id()) ?? '';
    }

    /** Alpine submit'ten önce mesaj + textarea değerini sync et */
    public function setMessage(string $value): void
    {
        $this->message   = $value;
        $this->charCount = mb_strlen($value);
        $this->smsCount  = $this->charCount > 0 ? (int) ceil($this->charCount / 160) : 1;
    }

    /** Textarea'dan özel numara listesini de sync et */
    public function setCustomNumbers(string $value): void
    {
        $this->customNumbers = $value;
    }

    public function send()
    {
        $this->validate([
            'customNumbers' => 'required',
            'senderName'    => 'required',
            'message'       => 'required|min:1',
        ]);

        $user = auth()->user();

        // Numaraları parse et ve normalize et
        $rawNumbers = preg_split('/[\n,;]+/', $this->customNumbers);
        $recipients = [];
        foreach ($rawNumbers as $raw) {
            $phone = $this->normalizePhone(trim($raw));
            if ($phone && strlen($phone) === 10) {
                $recipients[] = $phone;
            }
        }
        $recipients = array_unique(array_filter($recipients));

        // Kara liste filtresi (normalize edilmiş numaralarla)
        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->pluck('phone_number')
            ->map(fn($p) => $this->normalizePhone($p))
            ->toArray();

        $recipients = array_values(array_diff($recipients, $blacklisted));

        if (empty($recipients)) {
            $this->addError('customNumbers', 'Geçerli numara bulunamadı veya tümü kara listede.');
            return;
        }

        // Kredi kontrolü
        $user->refresh();
        $count = count($recipients);
        if ($user->sms_credits < $count) {
            $this->addError('customNumbers',
                "Yetersiz SMS kredisi. Mevcut: {$user->sms_credits}, Gereken: {$count}.");
            return;
        }

        // DB kaydı
        $messageIds = [];
        foreach ($recipients as $phone) {
            $rec = SmsMessage::create([
                'user_id'     => $user->id,
                'recipient'   => $phone,
                'sender_name' => $this->senderName,
                'message'     => $this->message,
                'status'      => 'pending',
            ]);
            $messageIds[] = $rec->id;
        }

        // API çağrısı
        $service  = new VatanSmsService();
        $response = $service->send1toN($this->message, $recipients, null, $this->senderName ?: null);

        if ($response['success'] ?? false) {
            $user->decrement('sms_credits', $count);
            $remaining = $user->fresh()->sms_credits;
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'sent', 'sent_at' => now()]);
            CreditLog::record($user->id, 'sms', 'use', $count, $remaining, "Toplu SMS ({$count} alıcı)");
            session()->flash('success', "{$count} alıcıya SMS başarıyla gönderildi. Kalan kredi: {$remaining}");
        } else {
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'failed']);
            $err = $response['description'] ?? ($response['message'] ?? 'API bağlantı hatası.');
            session()->flash('warning', "SMS gönderilemedi: {$err}");
        }

        $this->reset(['customNumbers', 'message', 'charCount']);
        $this->smsCount = 1;
    }

    /** 05xx → 5xx, +905xx → 5xx, 905xx → 5xx */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '90') && strlen($phone) === 12) {
            return substr($phone, 2);
        }
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            return substr($phone, 1);
        }
        return $phone;
    }

    public function render()
    {
        $senders = SenderName::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->pluck('name')
            ->toArray();

        $templates = SmsTemplate::where('user_id', auth()->id())->get();

        return view('livewire.sms-send', compact('senders', 'templates'))
            ->layout('components.layouts.panel', ['title' => 'Toplu Numaralara SMS']);
    }
}
