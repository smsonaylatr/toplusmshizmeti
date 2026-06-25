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

class SmsSelected extends Component
{
    public string $selectedGroup    = '';
    public array  $selectedContacts = [];
    public string $searchGroup      = '';
    public string $searchContact    = '';
    public string $senderName       = '';
    public string $smsType          = 'Normal';
    public string $sendTime         = 'Hemen Gönder';
    public string $message          = '';
    public string $template         = '';
    public int    $step             = 1;

    public function mount(): void
    {
        $this->senderName = SenderName::defaultForUser(auth()->id()) ?? '';
    }

    public function selectContact($contactId)
    {
        if (!in_array($contactId, $this->selectedContacts)) {
            $this->selectedContacts[] = (int) $contactId;
        }
    }

    public function removeContact($contactId)
    {
        $this->selectedContacts = array_values(
            array_diff($this->selectedContacts, [(int) $contactId])
        );
    }

    public function selectAllFromGroup()
    {
        if (!$this->selectedGroup) return;
        $ids = Contact::where('user_id', auth()->id())
            ->where('group_id', $this->selectedGroup)
            ->pluck('id')->toArray();
        $this->selectedContacts = array_unique(array_merge($this->selectedContacts, $ids));
    }

    public function goToCompose()
    {
        if (empty($this->selectedContacts)) {
            $this->addError('selectedContacts', 'En az bir kayıt seçin.');
            return;
        }
        $this->step = 2;
    }

    public function goBack()
    {
        $this->step = 1;
    }

    /** Alpine submit'ten önce mesajı sync et */
    public function setMessage(string $value): void
    {
        $this->message = $value;
    }

    public function send()
    {
        $this->validate([
            'senderName' => 'required',
            'message'    => 'required|min:1',
        ]);

        $user     = auth()->user();
        $contacts = Contact::whereIn('id', $this->selectedContacts)
            ->where('user_id', $user->id)
            ->get();

        // Kara liste (normalize)
        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->pluck('phone_number')
            ->map(fn($p) => $this->normalizePhone($p))
            ->toArray();

        // Normalize + filtrele
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
            $phonesMessages[] = ['phone' => $phone, 'message' => $msg];
        }

        if (empty($phonesMessages)) {
            $this->addError('senderName', 'Geçerli numara bulunamadı.');
            return;
        }

        $count = count($phonesMessages);

        // Kredi kontrolü
        $user->refresh();
        if ($user->sms_credits < $count) {
            $this->addError('senderName',
                "Yetersiz SMS kredisi. Mevcut: {$user->sms_credits}, Gereken: {$count}.");
            return;
        }

        // DB kaydı
        $messageIds = [];
        foreach ($phonesMessages as $item) {
            $rec = SmsMessage::create([
                'user_id'     => $user->id,
                'recipient'   => $item['phone'],
                'sender_name' => $this->senderName,
                'message'     => $item['message'],
                'status'      => 'pending',
            ]);
            $messageIds[] = $rec->id;
        }

        // VatanSMS N-N API (kişisel mesaj)
        $service  = new VatanSmsService();
        $response = $service->sendNtoN($phonesMessages, null, $this->senderName ?: null);

        if ($response['success'] ?? false) {
            $user->decrement('sms_credits', $count);
            $remaining = $user->fresh()->sms_credits;
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'sent', 'sent_at' => now()]);
            CreditLog::record($user->id, 'sms', 'use', $count, $remaining, "Seçili kişi SMS ({$count} alıcı)");
            session()->flash('success', "{$count} alıcıya SMS başarıyla gönderildi. Kalan kredi: {$remaining}");
        } else {
            SmsMessage::whereIn('id', $messageIds)->update(['status' => 'failed']);
            $err = $response['description'] ?? ($response['message'] ?? 'API bağlantı hatası.');
            session()->flash('warning', "SMS gönderilemedi: {$err}");
        }

        $this->reset(['selectedContacts', 'message', 'selectedGroup']);
        $this->step = 1;
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
        $groups = ContactGroup::where('user_id', auth()->id())
            ->when($this->searchGroup, fn($q) => $q->where('name', 'like', "%{$this->searchGroup}%"))
            ->withCount('contacts')
            ->get();

        $contacts = collect();
        if ($this->selectedGroup) {
            $contacts = Contact::where('user_id', auth()->id())
                ->where('group_id', $this->selectedGroup)
                ->when($this->searchContact, fn($q) => $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->searchContact}%")
                        ->orWhere('phone', 'like', "%{$this->searchContact}%");
                }))
                ->get();
        }

        $selectedContactsData = Contact::whereIn('id', $this->selectedContacts)->get();

        $senders = SenderName::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->pluck('name')
            ->toArray();

        $templates = SmsTemplate::where('user_id', auth()->id())->get();

        return view('livewire.sms-selected', compact(
            'groups', 'contacts', 'selectedContactsData', 'senders', 'templates'
        ))->layout('components.layouts.panel', ['title' => 'Seçili Kayıtlara SMS']);
    }
}
