<?php

namespace App\Livewire;

use App\Models\WhatsappMessage;
use App\Models\WhatsappSession;
use App\Services\SpamGuard;
use Livewire\Component;
use Livewire\WithFileUploads;

class WhatsappExcel extends Component
{
    use WithFileUploads;

    public $excelFile;
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
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'sessionId' => 'required',
            'message' => 'required|min:1',
        ]);

        $user = auth()->user();

        // Güvenlik: Session sahipliği
        if ($error = SpamGuard::validateSession($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        // Anti-spam kontrolleri
        if ($error = SpamGuard::checkDailyLimit($user->id)) {
            $this->addError('excelFile', $error);
            return;
        }
        if ($error = SpamGuard::checkWarmup($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        // TODO: Parse Excel file and extract phone numbers with SpamGuard::validatePhone()
        session()->flash('success', 'Excel dosyası işleniyor, WhatsApp mesajları kuyruğa eklenecek.');
        $this->reset(['excelFile', 'message', 'charCount', 'spamWarnings', 'sendSpeed']);
    }

    public function render()
    {
        $sessions = WhatsappSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        $dailyStats = SpamGuard::getDailyStats(auth()->id());

        return view('livewire.whatsapp-excel', [
            'sessions' => $sessions,
            'dailyStats' => $dailyStats,
        ])->layout('components.layouts.panel', ['title' => 'Excel ile WhatsApp']);
    }
}
