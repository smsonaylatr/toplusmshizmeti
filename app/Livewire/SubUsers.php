<?php

namespace App\Livewire;

use App\Models\SubUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SubUsers extends Component
{
    public string $name = '';
    public string $surname = '';
    public string $username = '';
    public string $password = '';
    public string $phone = '';
    public string $verificationCode = '';
    public int $smsLimit = 0;
    public int $whatsappLimit = 0;

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:sub_users,username',
            'password' => 'required|min:6',
            'phone' => 'required|min:10',
            'smsLimit' => 'required|integer|min:0',
            'whatsappLimit' => 'required|integer|min:0',
        ]);

        SubUser::create([
            'parent_user_id' => auth()->id(),
            'name' => $this->name,
            'surname' => $this->surname,
            'username' => $this->username,
            'password' => $this->password,
            'phone' => $this->phone,
            'sms_limit' => $this->smsLimit,
            'whatsapp_limit' => $this->whatsappLimit,
        ]);

        session()->flash('success', 'Alt kullanıcı başarıyla oluşturuldu.');
        $this->reset(['name', 'surname', 'username', 'password', 'phone', 'verificationCode', 'smsLimit', 'whatsappLimit']);
    }

    public function toggleStatus($subUserId)
    {
        $subUser = SubUser::where('parent_user_id', auth()->id())->findOrFail($subUserId);
        $subUser->update(['is_active' => !$subUser->is_active]);
    }

    public function deleteSubUser($subUserId)
    {
        SubUser::where('parent_user_id', auth()->id())->findOrFail($subUserId)->delete();
        session()->flash('success', 'Alt kullanıcı silindi.');
    }

    public function render()
    {
        $subUsers = SubUser::where('parent_user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.sub-users', [
            'subUsers' => $subUsers,
        ])->layout('components.layouts.panel', ['title' => 'Alt Kullanıcı İşlemleri']);
    }
}
