<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $docFilter = '';     // '' | 'approved' | 'pending'
    public string $vatanFilter = '';   // '' | 'has_api' | 'no_api'
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingStatusFilter(){ $this->resetPage(); }
    public function updatingDocFilter()   { $this->resetPage(); }
    public function updatingVatanFilter() { $this->resetPage(); }

    public function toggleSuspend(int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_suspended'     => !$user->is_suspended,
            'suspended_at'     => !$user->is_suspended ? now() : null,
            'suspension_reason'=> !$user->is_suspended ? 'Admin tarafından askıya alındı' : null,
        ]);
    }

    public function sort(string $column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $query = User::where('is_admin', false)
            ->when($this->search, fn($q) => $q->where(function($sub) {
                $sub->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter === 'active',    fn($q) => $q->where('is_suspended', false))
            ->when($this->statusFilter === 'suspended', fn($q) => $q->where('is_suspended', true))
            ->when($this->docFilter === 'approved', fn($q) => $q->where('document_approved', true))
            ->when($this->docFilter === 'pending',  fn($q) => $q->where('document_approved', false))
            ->when($this->vatanFilter === 'has_api', fn($q) => $q->whereNotNull('vatansms_api_key'))
            ->when($this->vatanFilter === 'no_api',  fn($q) => $q->whereNull('vatansms_api_key'))
            ->withCount(['smsMessages', 'whatsappMessages', 'contacts']);

        $total     = User::where('is_admin', false)->count();
        $active    = User::where('is_admin', false)->where('is_suspended', false)->count();
        $suspended = User::where('is_admin', false)->where('is_suspended', true)->count();
        $docOk     = User::where('is_admin', false)->where('document_approved', true)->count();

        $users = $query->orderBy($this->sortBy, $this->sortDir)->paginate(15);

        return view('livewire.admin.users', compact('users', 'total', 'active', 'suspended', 'docOk'))
            ->layout('components.layouts.admin', ['title' => 'Kullanıcı Yönetimi']);
    }
}
