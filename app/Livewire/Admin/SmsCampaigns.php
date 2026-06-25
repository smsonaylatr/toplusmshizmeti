<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SmsCampaign;

class SmsCampaigns extends Component
{
    use WithPagination;
    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $campaigns = SmsCampaign::with('user')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.sms-campaigns', compact('campaigns'))
            ->layout('components.layouts.admin', ['title' => 'SMS Kampanyaları']);
    }
}
