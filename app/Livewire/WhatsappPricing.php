<?php

namespace App\Livewire;

use Livewire\Component;

class WhatsappPricing extends Component
{
    public const PACKAGES = [
        ['name' => 'Başlangıç', 'credits' => 500, 'price' => 250, 'perMsg' => '0,50', 'color' => 'green', 'popular' => false],
        ['name' => 'Profesyonel', 'credits' => 2000, 'price' => 800, 'perMsg' => '0,40', 'color' => 'blue', 'popular' => true],
        ['name' => 'İşletme', 'credits' => 5000, 'price' => 1750, 'perMsg' => '0,35', 'color' => 'purple', 'popular' => false],
        ['name' => 'Kurumsal', 'credits' => 10000, 'price' => 3000, 'perMsg' => '0,30', 'color' => 'amber', 'popular' => false],
        ['name' => 'Enterprise', 'credits' => 25000, 'price' => 6250, 'perMsg' => '0,25', 'color' => 'red', 'popular' => false],
    ];

    public function render()
    {
        return view('livewire.whatsapp-pricing', [
            'packages' => self::PACKAGES,
        ])->layout('components.layouts.panel', ['title' => 'WhatsApp Paket Fiyatları']);
    }
}
