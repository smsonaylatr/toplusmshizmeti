<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Settings extends Component
{
    // İletişim
    public string $name     = '';
    public string $email    = '';
    public string $phone    = '';

    // Hesap Türü
    public string $accountType    = 'individual';
    public string $companyName    = '';
    public string $contactPerson  = '';

    // Kimlik / Vergi
    public string $tcNo       = '';
    public string $taxNo      = '';
    public string $taxOffice  = '';
    public string $mersisNo   = '';

    // Adres
    public string $city     = '';
    public string $district = '';
    public string $address  = '';

    // Şifre
    public string $currentPassword        = '';
    public string $newPassword            = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name          = $user->name         ?? '';
        $this->email         = $user->email        ?? '';
        $this->phone         = $user->phone        ?? '';
        $this->accountType   = $user->account_type ?? 'individual';
        $this->companyName   = $user->company_name ?? '';
        $this->contactPerson = $user->contact_person ?? '';
        $this->tcNo          = $user->tc_no        ?? '';
        $this->taxNo         = $user->tax_no       ?? '';
        $this->taxOffice     = $user->tax_office   ?? '';
        $this->mersisNo      = $user->mersis_no    ?? '';
        $this->city          = $user->city         ?? '';
        $this->district      = $user->district     ?? '';
        $this->address       = $user->address      ?? '';
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone'         => 'nullable|string|max:20',
            'accountType'   => 'required|in:individual,corporate',
            'companyName'   => 'nullable|string|max:255',
            'contactPerson' => 'nullable|string|max:255',
            'tcNo'          => 'nullable|digits:11',
            'taxNo'         => 'nullable|digits_between:10,11',
            'taxOffice'     => 'nullable|string|max:100',
            'mersisNo'      => 'nullable|string|max:30',
            'city'          => 'nullable|string|max:255',
            'district'      => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:1000',
        ]);

        Auth::user()->update([
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'account_type'   => $this->accountType,
            'company_name'   => $this->companyName  ?: null,
            'contact_person' => $this->contactPerson ?: null,
            'tc_no'          => $this->tcNo         ?: null,
            'tax_no'         => $this->taxNo        ?: null,
            'tax_office'     => $this->taxOffice    ?: null,
            'mersis_no'      => $this->mersisNo     ?: null,
            'city'           => $this->city         ?: null,
            'district'       => $this->district     ?: null,
            'address'        => $this->address      ?: null,
        ]);

        session()->flash('profile_success', 'Bilgileriniz güncellendi.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword'        => 'required',
            'newPassword'            => 'required|min:8',
            'newPasswordConfirmation' => 'required|same:newPassword',
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Mevcut şifre hatalı.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->newPassword)]);
        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        session()->flash('password_success', 'Şifre başarıyla güncellendi.');
    }

    public function render()
    {
        return view('livewire.settings')
            ->layout('components.layouts.panel', ['title' => 'Ayarlar']);
    }
}
