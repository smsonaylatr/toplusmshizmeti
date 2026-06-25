<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WhatsappMessage;

class WhatsappMessages extends Component
{
    use WithPagination;
    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $messages = WhatsappMessage::with('user')
            ->when($this->search, fn($q) => $q->where('recipient', 'like', "%{$this->search}%")
                ->orWhere('message', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.whatsapp-messages', compact('messages'))
            ->layout('components.layouts.admin', ['title' => 'WhatsApp Mesajları']);
    }
}
