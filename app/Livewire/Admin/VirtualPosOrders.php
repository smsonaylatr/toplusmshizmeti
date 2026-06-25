<?php

namespace App\Livewire\Admin;

use App\Models\VirtualPosOrder;
use Livewire\Component;
use Livewire\WithPagination;

class VirtualPosOrders extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    public function render()
    {
        $orders = VirtualPosOrder::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))
                  ->orWhere('merchant_oid', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => VirtualPosOrder::paid()->count(),
            'revenue'  => VirtualPosOrder::paid()->sum('total_amount'),
            'pending'  => VirtualPosOrder::pending()->count(),
            'failed'   => VirtualPosOrder::failed()->count(),
        ];

        return view('livewire.admin.virtual-pos-orders', compact('orders', 'stats'))
            ->layout('components.layouts.admin', ['title' => 'Sanal POS Siparişleri']);
    }
}
