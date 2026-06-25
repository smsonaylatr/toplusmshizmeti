<?php

namespace App\Livewire;

use App\Models\PaymentNotification as PaymentNotificationModel;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentNotification extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function render()
    {
        $query = PaymentNotificationModel::where('user_id', auth()->id())->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.payment-notification', [
            'notifications' => $query->paginate(10),
            'totals' => [
                'all'      => PaymentNotificationModel::where('user_id', auth()->id())->count(),
                'pending'  => PaymentNotificationModel::where('user_id', auth()->id())->where('status', 'pending')->count(),
                'approved' => PaymentNotificationModel::where('user_id', auth()->id())->where('status', 'approved')->count(),
                'rejected' => PaymentNotificationModel::where('user_id', auth()->id())->where('status', 'rejected')->count(),
            ],
        ])->layout('components.layouts.panel', ['title' => 'Ödeme Bildirimleri']);
    }
}
