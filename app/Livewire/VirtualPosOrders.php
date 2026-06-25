<?php

namespace App\Livewire;

use App\Models\VirtualPosOrder;
use Livewire\Component;
use Livewire\WithPagination;

class VirtualPosOrders extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function render()
    {
        $orders = VirtualPosOrder::where('user_id', auth()->id())
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.virtual-pos-orders', compact('orders'))
            ->layout('components.layouts.panel', ['title' => 'Siparişlerim']);
    }
}
