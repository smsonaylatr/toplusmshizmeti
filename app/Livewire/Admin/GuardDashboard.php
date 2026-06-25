<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\GuardLog;
use App\Models\UserRiskScore;
use App\Models\MessageFilter;
use App\Models\User;

class GuardDashboard extends Component
{
    public function render()
    {
        $stats = [
            'totalFlags' => GuardLog::count(),
            'unresolvedFlags' => GuardLog::where('is_resolved', false)->count(),
            'criticalFlags' => GuardLog::where('severity', 'critical')->where('is_resolved', false)->count(),
            'suspendedUsers' => User::where('is_suspended', true)->count(),
            'highRiskUsers' => UserRiskScore::where('risk_score', '>=', 60)->count(),
            'activeFilters' => MessageFilter::where('is_active', true)->count(),
            'todayFlags' => GuardLog::whereDate('created_at', today())->count(),
            'blockedMessages' => GuardLog::where('action', 'block_message')->count(),
        ];

        $recentLogs = GuardLog::with('user')->latest()->take(10)->get();
        $highRiskUsers = UserRiskScore::with('user')->orderByDesc('risk_score')->take(5)->get();

        return view('livewire.admin.guard-dashboard', compact('stats', 'recentLogs', 'highRiskUsers'))
            ->layout('components.layouts.admin', ['title' => 'AI GuardSystem']);
    }
}
