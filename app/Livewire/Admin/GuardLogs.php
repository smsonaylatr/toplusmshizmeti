<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GuardLog;

class GuardLogs extends Component
{
    use WithPagination;
    public string $severityFilter = '';
    public string $actionFilter = '';

    public function updatingSeverityFilter() { $this->resetPage(); }
    public function updatingActionFilter() { $this->resetPage(); }

    public function resolve(int $id)
    {
        GuardLog::findOrFail($id)->update([
            'is_resolved' => true,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);
    }

    public function render()
    {
        $logs = GuardLog::with(['user', 'resolver'])
            ->when($this->severityFilter, fn($q) => $q->where('severity', $this->severityFilter))
            ->when($this->actionFilter, fn($q) => $q->where('action', $this->actionFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.guard-logs', compact('logs'))
            ->layout('components.layouts.admin', ['title' => 'Aksiyon Logları']);
    }
}
