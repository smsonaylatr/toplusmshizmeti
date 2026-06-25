<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserRiskScore;

class UserRisks extends Component
{
    use WithPagination;
    public string $riskFilter = '';

    public function render()
    {
        $risks = UserRiskScore::with('user')
            ->when($this->riskFilter === 'critical', fn($q) => $q->where('risk_score', '>=', 80))
            ->when($this->riskFilter === 'high', fn($q) => $q->whereBetween('risk_score', [60, 79]))
            ->when($this->riskFilter === 'medium', fn($q) => $q->whereBetween('risk_score', [30, 59]))
            ->when($this->riskFilter === 'low', fn($q) => $q->where('risk_score', '<', 30))
            ->orderByDesc('risk_score')
            ->paginate(20);

        return view('livewire.admin.user-risks', compact('risks'))
            ->layout('components.layouts.admin', ['title' => 'Risk Skorları']);
    }
}
