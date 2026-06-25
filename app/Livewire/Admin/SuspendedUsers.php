<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\GuardLog;

class SuspendedUsers extends Component
{
    use WithPagination;

    public function unsuspend(int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_suspended' => false,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        GuardLog::create([
            'user_id' => $id,
            'action' => 'unsuspend',
            'reason' => 'Admin tarafından askı kaldırıldı',
            'severity' => 'low',
        ]);

        session()->flash('success', 'Kullanıcı askıdan çıkarıldı.');
    }

    public function render()
    {
        $users = User::where('is_suspended', true)
            ->latest('suspended_at')
            ->paginate(20);

        return view('livewire.admin.suspended-users', compact('users'))
            ->layout('components.layouts.admin', ['title' => 'Askıya Alınanlar']);
    }
}
