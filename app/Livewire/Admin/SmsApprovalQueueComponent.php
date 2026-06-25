<?php

namespace App\Livewire\Admin;

use App\Models\SmsApprovalQueue;
use App\Services\VatanSmsService;
use Livewire\Component;
use Livewire\WithPagination;

class SmsApprovalQueueComponent extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';
    public string $rejectReason = '';
    public ?int   $rejectingId  = null;

    public function approve(int $id): void
    {
        $item = SmsApprovalQueue::where('id', $id)->where('status', 'pending')->firstOrFail();

        $user    = $item->user;
        $service = app(VatanSmsService::class);
        $phones  = array_column($item->recipients, 'phone');

        $result = $service->sendSmsForUser($user, $item->message, $phones, $item->sender_name);

        $item->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'sent_count'  => $result['success'] ?? false ? count($phones) : 0,
        ]);

        session()->flash(
            $result['success'] ?? false ? 'success' : 'error',
            $result['success'] ?? false
                ? 'SMS onaylandı ve gönderildi.'
                : 'Onaylandı fakat gönderim başarısız: ' . ($result['message'] ?? 'Bilinmeyen hata')
        );
    }

    public function startReject(int $id): void
    {
        $this->rejectingId  = $id;
        $this->rejectReason = '';
    }

    public function confirmReject(): void
    {
        if (! $this->rejectingId) return;

        SmsApprovalQueue::where('id', $this->rejectingId)->where('status', 'pending')->update([
            'status'        => 'rejected',
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
            'reject_reason' => $this->rejectReason ?: 'Admin tarafından reddedildi.',
        ]);

        $this->rejectingId  = null;
        $this->rejectReason = '';
        session()->flash('success', 'SMS reddedildi.');
    }

    public function cancelReject(): void
    {
        $this->rejectingId  = null;
        $this->rejectReason = '';
    }

    public function render()
    {
        $items = SmsApprovalQueue::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        $pendingCount = SmsApprovalQueue::pending()->count();

        return view('livewire.admin.sms-approval-queue', compact('items', 'pendingCount'))
            ->layout('components.layouts.admin', ['title' => 'SMS Onay Kuyruğu']);
    }
}
