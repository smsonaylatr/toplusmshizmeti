<?php

namespace App\Livewire;

use App\Models\SmsTemplate;
use Livewire\Component;

class BirthdayTemplate extends Component
{
    public string $platform = 'SMS';
    public string $language = 'turkce';
    public string $senderName = '';
    public string $content = '';

    public function insertVariable(string $variable)
    {
        $this->content .= "|{$variable}|";
    }

    public function save()
    {
        $this->validate([
            'content' => 'required|min:5',
        ]);

        SmsTemplate::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'name' => '__birthday_template__',
            ],
            [
                'content' => $this->content,
            ]
        );

        session()->flash('success', 'Doğum günü şablonu kaydedildi.');
    }

    public function removeTemplate()
    {
        SmsTemplate::where('user_id', auth()->id())
            ->where('name', '__birthday_template__')
            ->delete();

        $this->content = '';
        session()->flash('success', 'Doğum günü şablonu kaldırıldı.');
    }

    public function mount()
    {
        $existing = SmsTemplate::where('user_id', auth()->id())
            ->where('name', '__birthday_template__')
            ->first();

        if ($existing) {
            $this->content = $existing->content;
        }
    }

    public function render()
    {
        return view('livewire.birthday-template')
            ->layout('components.layouts.panel', ['title' => 'Doğum Günü Şablonu']);
    }
}
