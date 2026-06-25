<?php

namespace App\Livewire;

use Livewire\Component;

class PricingList extends Component
{
    public function render()
    {
        return view('livewire.pricing-list')
            ->layout('components.layouts.panel', ['title' => 'Paket Fiyat Listesi']);
    }
}
