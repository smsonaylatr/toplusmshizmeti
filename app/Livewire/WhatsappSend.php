<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSession;
use App\Services\SpamGuard;
use Livewire\Component;

class WhatsappSend extends Component
{
    public string $customNumbers = '';
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
            'customNumbers' => 'required',
            'sessionId' => 'required',
            'message' => 'required|min:1',
        ]);

        $user = auth()->user();

        // Güvenlik: Session sahipliği
        if ($error = SpamGuard::validateSession($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        $recipients = array_filter(
            array_map('trim', preg_split('/[\n,;]+/', $this->customNumbers))
        );

        // Telefon numarası format doğrulama
        $valid = [];
        $invalid = 0;
        foreach ($recipients as $phone) {
            if (SpamGuard::validatePhone($phone)) {
                $valid[] = preg_replace('/[\s\-\(\)\+]/', '', $phone);
            } else {
                $invalid++;
            }
        }
        $recipients = $valid;

        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->pluck('phone_number')
            ->toArray();

        $recipients = array_values(array_diff($recipients, $blacklisted));

        if (empty($recipients)) {
            $this->addError('customNumbers', 'Geçerli numara bulunamadı.' . ($invalid > 0 ? " ({$invalid} geçersiz format)" : ''));
            return;
        }

        // Anti-spam kontrolleri
        if ($error = SpamGuard::checkCredits($user, count($recipients))) {
            $this->addError('customNumbers', $error);
            return;
        }
        if ($error = SpamGuard::checkDailyLimit($user->id)) {
            $this->addError('customNumbers', $error);
            return;
        }
        if ($error = SpamGuard::checkWarmup($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        foreach ($recipients as $phone) {
            WhatsappMessage::create([
                'user_id' => $user->id,
                'recipient' => $phone,
                'message' => $this->message,
                'status' => 'pending',
                'message_type' => 'text',
            ]);
        }

        $user->decrement('whatsapp_credits', count($recipients));

        $msg = count($recipients) . ' alıcıya WhatsApp mesajı kuyruğa eklendi.';
        if ($invalid > 0) $msg .= " ({$invalid} geçersiz numara atlandı)";
        $msg .= ' Gönderim hızı: ' . SpamGuard::getSpeedLabel($this->sendSpeed);

        session()->flash('success', $msg);
        $this->reset(['customNumbers', 'message', 'charCount', 'spamWarnings', 'sendSpeed']);
    }

    public function render()
    {
        $sessions = WhatsappSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        $dailyStats = SpamGuard::getDailyStats(auth()->id());

        return view('livewire.whatsapp-send', [
            'sessions' => $sessions,
            'dailyStats' => $dailyStats,
        ])->layout('components.layouts.panel', ['title' => 'Toplu Numaralara WhatsApp']);
    }
}
