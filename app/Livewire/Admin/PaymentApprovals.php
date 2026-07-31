<?php

namespace App\Livewire\Admin;

use App\Models\CreditLog;
use App\Models\Notification;
use App\Models\PaymentNotification;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentApprovals extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    // Onay modalı
    public ?int   $approveId     = null;
    public string $approveMode   = 'package'; // 'package' | 'custom'
    public string $approveType   = 'sms';     // 'sms' | 'whatsapp'
    public int    $packageIndex  = -1;         // seçili paket indeksi
    public int    $customCredits = 0;         // manuel kredi

    // Paket listesi (SMS için)
    public const PACKAGES = [
        ['name' => '1.000 SMS',   'sms' => 1000,   'price' => 350.00],
        ['name' => '2.500 SMS',   'sms' => 2500,   'price' => 750.00],
        ['name' => '5.000 SMS',   'sms' => 5000,   'price' => 1250.00],
        ['name' => '10.000 SMS',  'sms' => 10000,  'price' => 2000.00],
        ['name' => '25.000 SMS',  'sms' => 25000,  'price' => 4375.00],
        ['name' => '50.000 SMS',  'sms' => 50000,  'price' => 7500.00],
        ['name' => '100.000 SMS', 'sms' => 100000, 'price' => 13000.00],
    ];

    public function getActivePackages(): array
    {
        if ($this->approveType === 'whatsapp') {
            return \App\Livewire\WhatsappPricing::PACKAGES;
        }
        return self::PACKAGES;
    }

    /**
     * Onay modali aç — tutara en yakın paketi otomatik seç.
     */
    public function openApprove(int $id): void
    {
        $this->approveId     = $id;
        $this->approveMode   = 'package';
        $this->customCredits = 0;

        $payment = PaymentNotification::find($id);
        $this->approveType   = $payment->credit_type ?? 'sms';
        $this->packageIndex  = $this->suggestPackage((float) ($payment->amount ?? 0));
    }

    /**
     * Tutara göre en uygun paketi öner.
     */
    private function suggestPackage(float $amount): int
    {
        $best = -1;
        $min  = PHP_INT_MAX;
        $packages = $this->getActivePackages();
        foreach ($packages as $i => $pkg) {
            $totalWithVat = round($pkg['price'] * 1.2, 2);
            $diff = abs($amount - $totalWithVat);
            if ($diff < $min) {
                $min  = $diff;
                $best = $i;
            }
        }
        return $best;
    }

    /**
     * Ödemeyi onayla ve SMS/WhatsApp yükle.
     */
    public function approve(): void
    {
        if (! $this->approveId) return;

        $payment = PaymentNotification::with('user')->findOrFail($this->approveId);
        $type = $payment->credit_type ?? 'sms';
        $creditField = $type === 'whatsapp' ? 'whatsapp_credits' : 'sms_credits';
        $creditLabel = $type === 'whatsapp' ? 'WhatsApp Kredisi' : 'SMS';

        if ($this->approveMode === 'package' && $this->packageIndex >= 0) {
            $packages = $this->getActivePackages();
            $pkg      = $packages[$this->packageIndex];
            $credits  = $type === 'whatsapp' ? $pkg['credits'] : $pkg['sms'];
            $label    = $pkg['name'];
        } else {
            $this->validate(['customCredits' => 'required|integer|min:1'], [
                'customCredits.min' => "En az 1 {$creditLabel} giriniz.",
            ]);
            $credits = (int) $this->customCredits;
            $label   = number_format($credits) . " {$creditLabel} (Manuel)";
        }

        $payment->update([
            'status'           => 'approved',
            'approved_credits' => $credits,
        ]);

        $user = User::findOrFail($payment->user_id);
        $user->increment($creditField, $credits);

        CreditLog::record(
            $user->id, $type, 'add', $credits,
            $user->fresh()->{$creditField},
            "Havale onayı: {$payment->bank} — {$payment->amount} ₺ → {$label}",
            (string) $payment->id
        );

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Ödeme Onaylandı',
            'message' => "Havale/EFT ödemeniz onaylandı. {$label} hesabınıza yüklendi.",
            'type'    => 'success',
        ]);

        $this->approveId = null;
        session()->flash('success', "Ödeme onaylandı. {$credits} {$creditLabel} yüklendi.");
    }

    /**
     * Ödemeyi reddet.
     */
    public function reject(int $id): void
    {
        $payment = PaymentNotification::with('user')->findOrFail($id);
        $payment->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $payment->user_id,
            'title'   => 'Ödeme Reddedildi',
            'message' => 'Havale/EFT bildiriminiz reddedildi. Lütfen destek ekibimizle iletişime geçin.',
            'type'    => 'error',
        ]);

        session()->flash('success', 'Ödeme reddedildi.');
    }

    public function render()
    {
        $payments = PaymentNotification::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(20);

        $stats = [
            'pending'  => PaymentNotification::where('status', 'pending')->count(),
            'approved' => PaymentNotification::where('status', 'approved')->count(),
            'rejected' => PaymentNotification::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.payment-approvals', compact('payments', 'stats'))
            ->layout('components.layouts.admin', ['title' => 'Ödeme Onayları']);
    }
}
