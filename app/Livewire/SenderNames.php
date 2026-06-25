<?php

namespace App\Livewire;

use App\Models\SenderName;
use Livewire\Component;

class SenderNames extends Component
{
    public string $newSenderName = '';

    public function addSender(): void
    {
        $this->validate(['newSenderName' => 'required|string|max:11|min:3']);

        $exists = SenderName::where('user_id', auth()->id())
            ->where('name', $this->newSenderName)->exists();

        if ($exists) {
            $this->addError('newSenderName', 'Bu gönderici adı zaten mevcut.');
            return;
        }

        SenderName::create([
            'user_id' => auth()->id(),
            'name'    => $this->newSenderName,
            'status'  => 'pending',
        ]);

        session()->flash('success', 'Gönderici adı talebi gönderildi. Onay bekleniyor.');
        $this->reset('newSenderName');
    }

    public function deleteSender(int $senderId): void
    {
        SenderName::where('user_id', auth()->id())->findOrFail($senderId)->delete();
        session()->flash('success', 'Gönderici adı silindi.');
    }

    public function setDefault(int $senderId): void
    {
        $sender = SenderName::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->findOrFail($senderId);
        $sender->setAsDefault();
        session()->flash('success', '"' . $sender->name . '" varsayılan gönderici olarak ayarlandı.');
    }

    public function render()
    {
        $senderNames = SenderName::where('user_id', auth()->id())->latest()->get();
        return view('livewire.sender-names', ['senderNames' => $senderNames])
            ->layout('components.layouts.panel', ['title' => 'Gönderici Adları']);
    }
}
