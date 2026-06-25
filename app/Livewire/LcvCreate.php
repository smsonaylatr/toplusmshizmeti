<?php

namespace App\Livewire;

use Livewire\Component;

class LcvCreate extends Component
{
    public function render()
    {
        return view('livewire.lcv-create')
            ->layout('components.layouts.panel', ['title' => 'LCV Oluştur']);
    }
}
