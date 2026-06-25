<?php

namespace App\Livewire;

use App\Models\SmsTemplate;
use Livewire\Component;

class TemplateCreate extends Component
{
    public string $name = '';
    public string $content = '';

    public function insertVariable(string $variable)
    {
        $this->content .= "|{$variable}|";
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2|max:255',
            'content' => 'required|min:5',
        ]);

        SmsTemplate::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'content' => $this->content,
        ]);

        $this->reset(['name', 'content']);
        session()->flash('success', 'Şablon başarıyla oluşturuldu.');
    }

    public function render()
    {
        return view('livewire.template-create')
            ->layout('components.layouts.panel', ['title' => 'Şablon Oluştur']);
    }
}
