<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Document;

class DocumentApprovals extends Component
{
    use WithPagination;
    public string $statusFilter = 'pending';
    public string $rejectionReason = '';
    public ?int $rejectingId = null;

    public function approve(int $id)
    {
        Document::findOrFail($id)->update(['status' => 'approved', 'rejection_reason' => null]);
        session()->flash('success', 'Evrak onaylandı.');
    }

    public function startReject(int $id)
    {
        $this->rejectingId = $id;
        $this->rejectionReason = '';
    }

    public function confirmReject()
    {
        Document::findOrFail($this->rejectingId)->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);
        $this->reset(['rejectingId', 'rejectionReason']);
        session()->flash('success', 'Evrak reddedildi.');
    }

    public function render()
    {
        $documents = Document::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.document-approvals', compact('documents'))
            ->layout('components.layouts.admin', ['title' => 'Evrak Onayları']);
    }
}
