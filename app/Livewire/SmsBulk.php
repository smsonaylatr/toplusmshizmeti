<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\CreditLog;
use App\Models\SenderName;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Services\VatanSmsService;
use Livewire\Component;

class SmsBulk extends Component
{
    public string $groupId     = '';
    public string $senderName  = '';
    public string $smsType     = 'Normal';
    public string $sendTime    = 'Hemen Gönder';
    public string $messageType = 'Bilgi';
    public string $message     = '';
    public string $template    = '';
    public int    $charCount   = 0;
    public int    $smsCount    = 1;

    public function mount(): void
    {
        $this->senderName = SenderName::defaultForUser(auth()->id()) ?? '';
    }

    /** Alpine submit'ten önce mesajı sync et */
    public function setMessage(string $value): void
    {
        $this->message   = $value;
        $this->charCount = mb_strlen($value);
        $this->smsCount  = $this->charCount > 0 ? (int) ceil($this->charCount / 160) : 1;
    }

    public function send()
    {
        $this->validate([
            'groupId'    => 'required',
            'senderName' => 'required',
            'message'    => 'required|min:1',
        ]);

        $user = auth()->user();

        // Gruptaki kişileri al ve numaraları normalize et
        $contacts = Contact::where('user_id', $user->id)
            ->where('group_id', $this->groupId)
            ->get();

        // Kara liste (normalize)
        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->pluck('phone_number')
            ->map(fn($p) => $this->normalizePhone($p))
            ->toArray();

        // Her kişi için normalize et ve filtrele
        $phonesMessages = [];
        foreach ($contacts as $contact) {
            $phone = $this->normalizePhone($contact->phone ?? '');
            if (!$phone || strlen($phone) !== 10) continue;
            if (in_array($phone, $blacklisted)) continue;

            $msg = str_replace(
                ['[isim]',        '[soyisim]'],
                [$contact->name ?? '', $contact->surname ?? ''],
                $this->message
            );

            $phonesMessages[] = ['phone' => $phone, 'message' => $msg, '_contact' => $contact];
        }

        if (empty($phonesMessages)) {
            $this->addError('groupId', 'Bu grupta geçerli numara bulunamadı.');
            return;
        }

        $count = count($phonesMessages);

        // Kredi kontrolü
        $user->refresh();
        if ($user->sms_credits < $count) {
            $this->addError('groupId',
                "Yetersiz SMS kredisi. Mevcut: {$user->sms_credits}, Gereken: {$count}.");
            return;
        }

        // DB kaydı
        $messageIds = [];
        $apiPayload = [];
        foreach ($phonesMessages as $item) {
            $rec = SmsMessage::create([
                'user_id'     => $user->id,
                'recipient'   => $item['phone'],
                'sender_name' => $this->senderName,
                'message'     => $item['message'],
                'status'      => 'pending',
            ]);
            $messageIds[] = $rec->id;
            $apiPayload[] = ['phone' => $item['phone'], 'message' => $item['message']];
        }

        // VatanSMS N-N API
        $service  = new VatanSmsService();
        $response = $service->sendNtoN($apiPayload, null, $this->senderName ?: null);

        if ($response['success'] ?? false) {
            $user->decrement('sms_credits', $count);
            $remaining = $user->fresh()->sms_credits;
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'sent', 'sent_at' => now()]);
            CreditLog::record($user->id, 'sms', 'use', $count, $remaining, "Grup SMS ({$count} alıcı, grup:{$this->groupId})");
            session()->flash('success', "{$count} alıcıya SMS başarıyla gönderildi. Kalan kredi: {$remaining}");
        } else {
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'failed']);
            $err = $response['description'] ?? ($response['message'] ?? 'API bağlantı hatası.');
            session()->flash('warning', "SMS gönderilemedi: {$err}");
        }

        $this->reset(['groupId', 'message', 'charCount']);
        $this->smsCount = 1;
    }

    /** 05xx → 5xx, +905xx → 5xx */
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

        $groups = ContactGroup::where('user_id', auth()->id())
            ->withCount('contacts')
            ->get();

        return view('livewire.sms-bulk', compact('senders', 'templates', 'groups'))
            ->layout('components.layouts.panel', ['title' => 'Gruplara SMS']);
    }
}
