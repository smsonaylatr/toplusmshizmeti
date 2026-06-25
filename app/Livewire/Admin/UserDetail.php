<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Services\VatanSmsService;

class UserDetail extends Component
{
    public int    $userId;
    public int    $addSmsCredits       = 0;
    public int    $addWhatsappCredits  = 0;
    public int    $deductSmsCredits    = 0;
    public int    $deductWhatsappCredits = 0;
    public string $smsShortCode        = '';
    public string $smsCancelNumber     = '';
    public string $activeTab           = 'sms';

    // VatanSMS hesap bilgileri
    public string $vatanApiKey       = '';
    public string $vatanApiSecret    = '';
    public string $vatanSender       = '';
    public string $vatanAccountId    = '';
    public bool   $documentApproved  = false;
    public array  $vatanSenders      = [];
    public string $vatanTestMsg      = '';

    public function mount(): void
    {
        $user = User::findOrFail($this->userId);
        $this->smsShortCode     = $user->sms_short_code    ?? '';
        $this->smsCancelNumber  = $user->sms_cancel_number ?? '';
        // VatanSMS
        $this->vatanApiKey      = $user->vatansms_api_key     ?? '';
        $this->vatanApiSecret   = $user->vatansms_api_secret  ?? '';
        $this->vatanSender      = $user->vatansms_sender      ?? '';
        $this->vatanAccountId   = $user->vatansms_account_id  ?? '';
        $this->documentApproved = (bool) $user->document_approved;
    }

    public function addCredits(): void
    {
        $user = User::findOrFail($this->userId);
        if ($this->addSmsCredits > 0) {
            $user->increment('sms_credits', $this->addSmsCredits);
            \App\Models\CreditLog::record($user->id, 'sms', 'add', $this->addSmsCredits, $user->fresh()->sms_credits, 'Admin tarafından eklendi');
        }
        if ($this->addWhatsappCredits > 0) {
            $user->increment('whatsapp_credits', $this->addWhatsappCredits);
            \App\Models\CreditLog::record($user->id, 'whatsapp', 'add', $this->addWhatsappCredits, $user->fresh()->whatsapp_credits, 'Admin tarafından eklendi');
        }
        $this->reset(['addSmsCredits', 'addWhatsappCredits']);
        session()->flash('success', 'Kredi başarıyla eklendi.');
    }

    public function deductCredits(): void
    {
        $user = User::findOrFail($this->userId);
        if ($this->deductSmsCredits > 0) {
            $amount = min($this->deductSmsCredits, $user->sms_credits);
            $user->decrement('sms_credits', $amount);
            \App\Models\CreditLog::record($user->id, 'sms', 'deduct', $amount, $user->fresh()->sms_credits, 'Admin tarafından düşüldü');
        }
        if ($this->deductWhatsappCredits > 0) {
            $amount = min($this->deductWhatsappCredits, $user->whatsapp_credits);
            $user->decrement('whatsapp_credits', $amount);
            \App\Models\CreditLog::record($user->id, 'whatsapp', 'deduct', $amount, $user->fresh()->whatsapp_credits, 'Admin tarafından düşüldü');
        }
        $this->reset(['deductSmsCredits', 'deductWhatsappCredits']);
        session()->flash('success', 'Kredi başarıyla düşüldü.');
    }
    public function saveSmsCodes(): void
    {
        $this->validate([
            'smsShortCode'    => 'nullable|string|max:20|alpha',
            'smsCancelNumber' => 'nullable|string|max:200',
        ]);

        User::findOrFail($this->userId)->update([
            'sms_short_code'    => strtoupper(trim($this->smsShortCode)) ?: null,
            'sms_cancel_number' => trim($this->smsCancelNumber) ?: null,
        ]);

        session()->flash('success', 'SMS kısa kodu ve iptal linki güncellendi.');
    }

    public function saveVatanSms(): void
    {
        $this->validate([
            'vatanApiKey'    => 'nullable|string|max:255',
            'vatanApiSecret' => 'nullable|string|max:255',
            'vatanSender'    => 'nullable|string|max:50',
            'vatanAccountId' => 'nullable|string|max:100',
        ]);

        User::findOrFail($this->userId)->update([
            'vatansms_api_key'    => trim($this->vatanApiKey)    ?: null,
            'vatansms_api_secret' => trim($this->vatanApiSecret) ?: null,
            'vatansms_sender'     => trim($this->vatanSender)    ?: null,
            'vatansms_account_id' => trim($this->vatanAccountId) ?: null,
            'document_approved'   => $this->documentApproved,
        ]);

        session()->flash('success', 'VatanSMS hesap bilgileri kaydedildi.');
    }

    public function testVatanSmsConnection(): void
    {
        $user = User::findOrFail($this->userId);
        // Geçici olarak form değerlerini kullan (kaydedilmeden test)
        $user->vatansms_api_key    = $this->vatanApiKey    ?: null;
        $user->vatansms_api_secret = $this->vatanApiSecret ?: null;

        $result = app(VatanSmsService::class)->testConnectionForUser($user);

        $this->vatanTestMsg = $result['message'];
        if (! empty($result['senders'])) {
            $this->vatanSenders = $result['senders'];
        }
    }

    public function toggleSuspend(): void
    {
        $user = User::findOrFail($this->userId);
        $user->update([
            'is_suspended'      => !$user->is_suspended,
            'suspended_at'      => !$user->is_suspended ? now() : null,
            'suspension_reason' => !$user->is_suspended ? 'Admin tarafından askıya alındı' : null,
        ]);
    }

    public function setSenderDefault(int $senderId): void
    {
        // Aynı kullanıcının tüm sender'larından default'u kaldır
        \App\Models\SenderName::where('user_id', $this->userId)->update(['is_default' => false]);
        \App\Models\SenderName::where('id', $senderId)->where('user_id', $this->userId)
            ->update(['is_default' => true]);
        session()->flash('success', 'Varsayılan gönderici güncellendi.');
    }

    public function deleteSender(int $senderId): void
    {
        \App\Models\SenderName::where('id', $senderId)->where('user_id', $this->userId)->delete();
        session()->flash('success', 'Gönderici adı silindi.');
    }
    public function render()
    {
        $user = User::withCount([
            'smsMessages', 'whatsappMessages', 'contacts',
            'subUsers', 'documents', 'senderNames', 'guardLogs',
            'smsTemplates', 'contactGroups', 'blacklistedNumbers',
            'loginLogs', 'creditLogs', 'paymentNotifications',
        ])->findOrFail($this->userId);

        $senderNames         = $user->senderNames()->latest()->get();
        $smsMessages         = $user->smsMessages()->latest()->take(30)->get();
        $whatsappMessages    = $user->whatsappMessages()->latest()->take(30)->get();
        $templates           = $user->smsTemplates()->latest()->get();
        $contactGroups       = $user->contactGroups()->withCount('contacts')->latest()->get();
        $contacts            = $user->contacts()->latest()->take(50)->get();
        $blacklisted         = $user->blacklistedNumbers()->latest()->get();
        $documents           = $user->documents()->latest()->get();
        $guardLogs           = $user->guardLogs()->latest()->take(30)->get();
        $loginLogs           = $user->loginLogs()->latest()->take(50)->get();
        $creditLogs          = $user->creditLogs()->latest()->take(100)->get();
        $paymentNotifications = $user->paymentNotifications()->latest()->get();
        $riskScore           = $user->riskScore;

        return view('livewire.admin.user-detail', compact(
            'user', 'senderNames', 'smsMessages', 'whatsappMessages',
            'templates', 'contactGroups', 'contacts', 'blacklisted',
            'documents', 'guardLogs', 'loginLogs', 'creditLogs',
            'paymentNotifications', 'riskScore'
        ))->layout('components.layouts.admin', ['title' => $user->name]);
    }
}
