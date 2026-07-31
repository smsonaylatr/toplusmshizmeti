<?php

namespace App\Livewire;

use App\Models\WhatsappSession;
use Livewire\Component;

class WhatsappSetup extends Component
{
    public string $status = 'disconnected'; // disconnected, waiting
    public string $sessionId = '';
    public string $qrCode = '';

    public function generateQr()
    {
        $this->sessionId = 'session_' . auth()->id() . '_' . time();
        $this->status = 'waiting';
        $this->qrCode = '';

        try {
            $response = \Illuminate\Support\Facades\Http::post('http://localhost:3000/session/start', [
                'sessionId' => $this->sessionId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['qr'])) {
                    $this->qrCode = $data['qr'];
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'WhatsApp sunucusuna ulaşılamıyor.');
        }
    }

    public function checkConnection()
    {
        if ($this->status !== 'waiting' || empty($this->sessionId)) {
            return;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:3000/session/status/' . $this->sessionId);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'connected' && isset($data['number'])) {
                    $user = auth()->user();
                    $sessionCount = WhatsappSession::where('user_id', $user->id)->count();
                    $isDefault = $sessionCount === 0;

                    WhatsappSession::create([
                        'user_id' => $user->id,
                        'phone_number' => $data['number'],
                        'display_name' => $user->name . ' #' . ($sessionCount + 1),
                        'is_active' => true,
                        'is_default' => $isDefault,
                        'session_id' => $this->sessionId,
                        'connected_at' => now(),
                    ]);

                    $this->status = 'disconnected';
                    $this->qrCode = '';
                    $this->sessionId = '';
                    session()->flash('success', 'WhatsApp numarası başarıyla bağlandı: ' . $data['number']);
                } elseif ($data['status'] === 'qr_ready') {
                    // Start it again to get the latest QR if it changed, or wait
                }
            }
        } catch (\Exception $e) {
            // silent fail for polling
        }
    }

    public function setDefault($id)
    {
        $user = auth()->user();
        WhatsappSession::where('user_id', $user->id)->update(['is_default' => false]);
        WhatsappSession::where('id', $id)->where('user_id', $user->id)->update(['is_default' => true]);
    }

    public function disconnect($id)
    {
        $session = WhatsappSession::where('id', $id)->where('user_id', auth()->id())->first();
        if ($session) {
            if ($session->session_id) {
                try {
                    \Illuminate\Support\Facades\Http::post('http://localhost:3000/session/logout/' . $session->session_id);
                } catch (\Exception $e) {}
            }
            $session->delete();
        }

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
