<?php

namespace App\Livewire;

use App\Models\BlacklistedNumber;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSession;
use App\Services\SpamGuard;
use Livewire\Component;

class WhatsappBulk extends Component
{
    public string $groupId = '';
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
            'groupId' => 'required',
            'sessionId' => 'required',
            'message' => 'required|min:1',
        ]);

        $user = auth()->user();

        // Güvenlik: Session sahipliği kontrolü
        if ($error = SpamGuard::validateSession($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        // Güvenlik: Grup sahipliği kontrolü (IDOR)
        $group = ContactGroup::where('id', $this->groupId)
            ->where('user_id', $user->id)
            ->first();

        if (!$group) {
            $this->addError('groupId', 'Bu gruba erişim yetkiniz yok.');
            return;
        }

        $recipients = Contact::where('group_id', $group->id)
            ->pluck('phone')
            ->toArray();

        $blacklisted = BlacklistedNumber::where('user_id', $user->id)
            ->pluck('phone_number')
            ->toArray();

        $recipients = array_values(array_diff($recipients, $blacklisted));

        // Telefon numarası format doğrulama
        $recipients = array_filter($recipients, fn($p) => SpamGuard::validatePhone($p));

        if (empty($recipients)) {
            $this->addError('groupId', 'Bu grupta geçerli numara bulunamadı.');
            return;
        }

        // Anti-spam: Kredi kontrolü
        if ($error = SpamGuard::checkCredits($user, count($recipients))) {
            $this->addError('groupId', $error);
            return;
        }

        // Anti-spam: Günlük limit
        if ($error = SpamGuard::checkDailyLimit($user->id)) {
            $this->addError('groupId', $error);
            return;
        }

        // Anti-spam: Numara ısınma
        if ($error = SpamGuard::checkWarmup($this->sessionId, $user->id)) {
            $this->addError('sessionId', $error);
            return;
        }

        foreach ($recipients as $phone) {
            $contact = Contact::where('phone', $phone)
                ->where('group_id', $group->id)
                ->first();

            $msg = $this->message;
            if ($contact) {
                $msg = str_replace('[isim]', $contact->name ?? '', $msg);
                $msg = str_replace('[soyisim]', $contact->surname ?? '', $msg);
            }

            WhatsappMessage::create([
                'user_id' => $user->id,
                'recipient' => $phone,
                'message' => $msg,
                'status' => 'pending',
                'message_type' => 'text',
            ]);
        }

        $user->decrement('whatsapp_credits', count($recipients));

        session()->flash('success', count($recipients) . ' alıcıya WhatsApp mesajı kuyruğa eklendi. Gönderim hızı: ' . SpamGuard::getSpeedLabel($this->sendSpeed));
        $this->reset(['groupId', 'message', 'charCount', 'spamWarnings', 'sendSpeed']);
    }

    public function render()
    {
        $groups = ContactGroup::where('user_id', auth()->id())
            ->withCount('contacts')
            ->get();

        $sessions = WhatsappSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->get();

        $dailyStats = SpamGuard::getDailyStats(auth()->id());

        return view('livewire.whatsapp-bulk', [
            'groups' => $groups,
            'sessions' => $sessions,
            'dailyStats' => $dailyStats,
        ])->layout('components.layouts.panel', ['title' => 'Gruplara WhatsApp Mesaj']);
    }
}
