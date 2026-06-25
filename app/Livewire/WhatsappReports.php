<?php

namespace App\Livewire;

use App\Models\WhatsappMessage;
use Livewire\Component;
use Livewire\WithPagination;

class WhatsappReports extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $query = WhatsappMessage::where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('recipient', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $messages = $query->latest()->paginate(20);

        $userQuery = WhatsappMessage::where('user_id', auth()->id());
        $stats = [
            'total' => (clone $userQuery)->count(),
            'delivered' => (clone $userQuery)->where('status', 'delivered')->count(),
            'pending' => (clone $userQuery)->where('status', 'pending')->count(),
            'failed' => (clone $userQuery)->where('status', 'failed')->count(),
        ];

        return view('livewire.whatsapp-reports', [
            'messages' => $messages,
            'stats' => $stats,
        ])->layout('components.layouts.panel', ['title' => 'WhatsApp Raporları']);
    }
}
