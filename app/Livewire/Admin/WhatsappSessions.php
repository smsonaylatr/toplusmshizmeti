<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WhatsappSession;

class WhatsappSessions extends Component
{
    use WithPagination;

    public function render()
    {
        $sessions = WhatsappSession::with('user')->latest()->paginate(20);
        return view('livewire.admin.whatsapp-sessions', compact('sessions'))
            ->layout('components.layouts.admin', ['title' => 'WhatsApp Oturumları']);
    }
}
