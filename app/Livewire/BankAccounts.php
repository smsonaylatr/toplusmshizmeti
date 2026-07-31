<?php

namespace App\Livewire;

use App\Models\PaymentNotification;
use App\Models\SystemSetting;
use Livewire\Component;

class BankAccounts extends Component
{
    public array  $bankAccounts = [];

    // Ödeme bildirimi formu
    public string $senderName = '';
    public string $bank       = '';
    public string $amount     = '';
    public string $creditType = 'sms';

    public function mount(): void
    {
        $raw = SystemSetting::get('bank_accounts', '[]');
        $all = json_decode($raw, true) ?? [];
        $this->bankAccounts = array_values(array_filter($all, fn($a) => $a['is_active'] ?? true));
        $this->senderName = auth()->user()->name ?? '';
    }

    public function submitNotification(): void
    {
        // İncelemede bekleyen bildirim varsa yenisine izin verme
        $hasPending = PaymentNotification::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            $this->addError('senderName', 'İncelemede bekleyen bir ödeme bildiriminiz zaten mevcut. Lütfen sonuçlanmasını bekleyin.');
            return;
        }

        $this->validate([
            'senderName' => 'required|string|min:3|max:255',
            'bank'       => 'required|string',
            'amount'     => 'required|numeric|min:1',
            'creditType' => 'required|in:sms,whatsapp',
        ], [
            'senderName.required' => 'Gönderici adı zorunludur.',
            'bank.required'       => 'Banka seçiniz.',
            'amount.required'     => 'Tutar zorunludur.',
            'amount.numeric'      => 'Geçerli bir tutar girin.',
            'amount.min'          => 'Tutar 1₺\'den büyük olmalıdır.',
            'creditType.in'       => 'Geçersiz kredi türü.',
        ]);

        PaymentNotification::create([
            'user_id'      => auth()->id(),
            'sender_name'  => $this->senderName,
            'phone'        => auth()->user()->phone ?? '',
            'bank'         => $this->bank,
            'amount'       => $this->amount,
            'credit_type'  => $this->creditType,
            'payment_date' => now()->toDateString(),
            'status'       => 'pending',
        ]);

        session()->flash('success', 'Ödeme bildirimi başarıyla gönderildi! Onay sürecinde incelenecektir.');
        $this->senderName = auth()->user()->name ?? '';
        $this->bank   = '';
        $this->amount = '';
        $this->creditType = 'sms';
    }

    public function render()
    {
        return view('livewire.bank-accounts')
            ->layout('components.layouts.panel', ['title' => 'Banka Hesapları']);
    }
}

