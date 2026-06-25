<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Livewire\Component;

class BankAccounts extends Component
{
    // Liste
    public array $accounts = [];

    // Form alanları
    public string $bankName    = '';
    public string $accountName = '';
    public string $iban        = '';
    public string $branch      = '';
    public bool   $isActive    = true;

    // Düzenleme
    public ?int $editIndex = null;

    // Modal
    public bool $showForm = false;

    public function mount(): void
    {
        $this->loadAccounts();
    }

    private function loadAccounts(): void
    {
        $raw = SystemSetting::get('bank_accounts', '[]');
        $this->accounts = json_decode($raw, true) ?? [];
    }

    private function saveAccounts(): void
    {
        SystemSetting::set('bank_accounts', json_encode(array_values($this->accounts)));
    }

    public function openForm(?int $index = null): void
    {
        $this->resetForm();
        if ($index !== null && isset($this->accounts[$index])) {
            $acc = $this->accounts[$index];
            $this->bankName    = $acc['bank_name']    ?? '';
            $this->accountName = $acc['account_name'] ?? '';
            $this->iban        = $acc['iban']          ?? '';
            $this->branch      = $acc['branch']        ?? '';
            $this->isActive    = $acc['is_active']     ?? true;
            $this->editIndex   = $index;
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'bankName'    => 'required|string|max:100',
            'accountName' => 'required|string|max:100',
            'iban'        => 'required|string|max:40',
            'branch'      => 'nullable|string|max:100',
        ], [
            'bankName.required'    => 'Banka adı zorunludur.',
            'accountName.required' => 'Hesap sahibi adı zorunludur.',
            'iban.required'        => 'IBAN zorunludur.',
        ]);

        $entry = [
            'bank_name'    => trim($this->bankName),
            'account_name' => trim($this->accountName),
            'iban'         => strtoupper(preg_replace('/\s+/', '', $this->iban)),
            'branch'       => trim($this->branch),
            'is_active'    => $this->isActive,
        ];

        if ($this->editIndex !== null) {
            $this->accounts[$this->editIndex] = $entry;
        } else {
            $this->accounts[] = $entry;
        }

        $this->saveAccounts();
        $this->resetForm();
        $this->showForm = false;
        session()->flash('success', 'Banka hesabı kaydedildi.');
    }

    public function toggleActive(int $index): void
    {
        if (isset($this->accounts[$index])) {
            $this->accounts[$index]['is_active'] = ! $this->accounts[$index]['is_active'];
            $this->saveAccounts();
        }
    }

    public function delete(int $index): void
    {
        unset($this->accounts[$index]);
        $this->accounts = array_values($this->accounts);
        $this->saveAccounts();
        session()->flash('success', 'Banka hesabı silindi.');
    }

    private function resetForm(): void
    {
        $this->bankName    = '';
        $this->accountName = '';
        $this->iban        = '';
        $this->branch      = '';
        $this->isActive    = true;
        $this->editIndex   = null;
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts')
            ->layout('components.layouts.admin', ['title' => 'Banka Hesapları']);
    }
}
