<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Notification;
use App\Models\User;

class Notifications extends Component
{
    public string $title = '';
    public string $message = '';
    public string $type = 'info';
    public string $targetUser = '';

    public function send()
    {
        $this->validate([
            'title' => 'required|min:2|max:255',
            'message' => 'required|min:5',
            'type' => 'required|in:info,success,warning,error',
        ]);

        if ($this->targetUser) {
            Notification::create([
                'user_id' => $this->targetUser,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
            ]);
        } else {
            $users = User::where('is_admin', false)->pluck('id');
            foreach ($users as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                ]);
            }
        }

        $this->reset(['title', 'message', 'type', 'targetUser']);
        session()->flash('success', 'Bildirim gönderildi.');
    }

    public function render()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        $recentNotifications = Notification::with('user')->latest()->take(20)->get();

        return view('livewire.admin.notifications', compact('users', 'recentNotifications'))
            ->layout('components.layouts.admin', ['title' => 'Bildirimler']);
    }
}
