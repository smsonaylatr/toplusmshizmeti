<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSession;
use App\Services\SpamGuard;
use Livewire\Component;

class WhatsappSingle extends Component
{
    public string $phone = '';
    public string $sessionId = '';
    public string $sendSpeed = 'orta';
    public string $message = '';
    public int $charCount = 0;
    public array $spamWarnings = [];

    public function mount()
    {
        $default = WhatsappSession::where('user_id', auth()->id())->where('is_default', true)->first();
        if ($default) $this->sessionId = (string) $default->id;
    }

    public function updatedMessage($value)
    {
        $this->charCount = mb_strlen($value);
        $this->spamWarnings = SpamGuard::checkSpamContent($value);
    }

    public function send()
    {
        $this->validate([
            'phone' => 'required',
            'sessionId' => 'required',
            'message' => 'required|min:1',
        ]);

        $user = auth()->user();

        // Telefon numarası format doğrulama
        if (!SpamGuard::validatePhone($this->phone)) {
            $this->addError('phone', 'Geçersiz telefon numarası formatı. Örnek: 5301234567');
            return;
        }

        // Güvenlik: Session sahipliği
        if ($error = SpamGuard::validateSession($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->where('phone_number', $this->phone)
            ->exists();

        if ($blacklisted) {
            $this->addError('phone', 'Bu numara kara listede.');
            return;
        }

        // Anti-spam kontrolleri
        if ($error = SpamGuard::checkCredits($user, 1)) {
            $this->addError('phone', $error);
            return;
        }
        if ($error = SpamGuard::checkDailyLimit($user->id)) {
            $this->addError('phone', $error);
            return;
        }
        if ($error = SpamGuard::checkWarmup($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        WhatsappMessage::create([
            'user_id' => $user->id,
            'whatsapp_session_id' => $this->sessionId,
            'recipient' => $this->phone,
            'message' => $this->message,
            'status' => 'pending',
            'message_type' => 'text',
            'send_speed' => $this->sendSpeed,
        ]);

        $user->decrement('whatsapp_credits', 1);

        session()->flash('success', 'WhatsApp mesajı kuyruğa eklendi.');
        $this->reset(['phone', 'message', 'charCount', 'spamWarnings', 'sendSpeed']);
    }

    public function render()
    {
        $sessions = WhatsappSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        $dailyStats = SpamGuard::getDailyStats(auth()->id());

        return view('livewire.whatsapp-single', [
            'sessions' => $sessions,
            'dailyStats' => $dailyStats,
        ])->layout('components.layouts.panel', ['title' => 'Tek Numaraya WhatsApp']);
    }
}
