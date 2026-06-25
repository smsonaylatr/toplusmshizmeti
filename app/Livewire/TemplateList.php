<?php

namespace App\Livewire;

use App\Models\SmsTemplate;
use Livewire\Component;

class TemplateList extends Component
{
    public function deleteTemplate($id)
    {
        SmsTemplate::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();
    }

    public function render()
    {
        $templates = SmsTemplate::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.template-list', [
            'templates' => $templates,
        ])->layout('components.layouts.panel', ['title' => 'Tüm Şablonlar']);
    }
}
