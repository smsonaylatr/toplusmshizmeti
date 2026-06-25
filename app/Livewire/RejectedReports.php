<?php

namespace App\Livewire;

use App\Models\SmsMessage;
use Livewire\Component;
use Livewire\WithPagination;

class RejectedReports extends Component
{
    use WithPagination;

    public string $dateFrom = '';
    public string $dateTo = '';
    public bool $futureOnly = false;

    public function clearFilters()
    {
        $this->reset(['dateFrom', 'dateTo', 'futureOnly']);
        $this->resetPage();
    }

    public function render()
    {
        $query = SmsMessage::where('user_id', auth()->id())
            ->where('status', 'failed');

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if ($this->futureOnly) {
            $query->where('created_at', '>', now());
        }

        $messages = $query->latest()->paginate(20);

        return view('livewire.rejected-reports', [
            'messages' => $messages,
        ])->layout('components.layouts.panel', ['title' => 'Ret Raporları']);
    }
}
