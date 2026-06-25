<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlacklistedNumber;

class SystemBlacklist extends Component
{
    use WithPagination;
    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete(int $id)
    {
        BlacklistedNumber::findOrFail($id)->delete();
        session()->flash('success', 'Numara kara listeden kaldırıldı.');
    }

    public function render()
    {
        $numbers = BlacklistedNumber::with('user')
            ->when($this->search, fn($q) => $q->where('phone_number', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.system-blacklist', compact('numbers'))
            ->layout('components.layouts.admin', ['title' => 'Kara Liste']);
    }
}
