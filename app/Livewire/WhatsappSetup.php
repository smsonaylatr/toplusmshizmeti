<?php

namespace App\Livewire;

use App\Models\WhatsappSession;
use Livewire\Component;

class WhatsappSetup extends Component
{
    public string $status = 'disconnected'; // disconnected, waiting
    public string $qrCode = '';

    public function generateQr()
    {
        $this->status = 'waiting';
        $this->qrCode = 'whatsapp://qr/' . bin2hex(random_bytes(32));
    }

    public function simulateConnect()
    {
        $user = auth()->user();
        $sessionCount = WhatsappSession::where('user_id', $user->id)->count();
        $isDefault = $sessionCount === 0;

        // Simulate connecting a new number
        $phone = '5' . rand(30, 55) . rand(1000000, 9999999);

        WhatsappSession::create([
            'user_id' => $user->id,
            'phone_number' => $phone,
            'display_name' => $user->name . ' #' . ($sessionCount + 1),
            'is_active' => true,
            'is_default' => $isDefault,
            'connected_at' => now(),
        ]);

        $this->status = 'disconnected';
        $this->qrCode = '';
        session()->flash('success', 'WhatsApp numarası başarıyla bağlandı: ' . $phone);
    }

    public function setDefault($sessionId)
    {
        $user = auth()->user();
        WhatsappSession::where('user_id', $user->id)->update(['is_default' => false]);
        WhatsappSession::where('id', $sessionId)->where('user_id', $user->id)->update(['is_default' => true]);
    }

    public function disconnect($sessionId)
    {
        WhatsappSession::where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        // If deleted was default, set first remaining as default
        $remaining = WhatsappSession::where('user_id', auth()->id())->first();
        if ($remaining && !WhatsappSession::where('user_id', auth()->id())->where('is_default', true)->exists()) {
            $remaining->update(['is_default' => true]);
        }

        session()->flash('success', 'WhatsApp numarası kaldırıldı.');
    }

    public function render()
    {
        $sessions = WhatsappSession::where('user_id', auth()->id())
            ->orderByDesc('is_default')
            ->orderByDesc('connected_at')
            ->get();

        return view('livewire.whatsapp-setup', [
            'sessions' => $sessions,
        ])->layout('components.layouts.panel', ['title' => 'WhatsApp Kurulum']);
    }
}
