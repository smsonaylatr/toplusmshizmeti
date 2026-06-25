<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\ContactGroup;
use Livewire\Component;
use Livewire\WithPagination;

class Contacts extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterGroup = null;

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public ?int $groupId = null;
    public string $notes = '';

    // Group form
    public bool $showGroupForm = false;
    public string $groupName = '';
    public string $groupColor = '#6366f1';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openForm(?int $id = null)
    {
        $this->resetValidation();
        if ($id) {
            $contact = Contact::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $contact->id;
            $this->name = $contact->name;
            $this->phone = $contact->phone;
            $this->email = $contact->email ?? '';
            $this->groupId = $contact->group_id;
            $this->notes = $contact->notes ?? '';
        } else {
            $this->editingId = null;
            $this->reset(['name', 'phone', 'email', 'groupId', 'notes']);
        }
        $this->showForm = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'groupId' => 'nullable|exists:contact_groups,id',
            'notes' => 'nullable|string',
        ]);

        $isEditing = (bool) $this->editingId;

        Contact::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'group_id' => $this->groupId,
                'notes' => $this->notes ?: null,
            ]
        );

        $this->showForm = false;
        $this->reset(['name', 'phone', 'email', 'groupId', 'notes', 'editingId']);
        session()->flash('success', $isEditing ? 'Kişi güncellendi.' : 'Kişi eklendi.');
    }

    public function delete(int $id)
    {
        Contact::where('user_id', auth()->id())->where('id', $id)->delete();
        session()->flash('success', 'Kişi silindi.');
    }

    public function saveGroup()
    {
        $this->validate([
            'groupName' => 'required|string|max:255',
            'groupColor' => 'required|string|max:7',
        ]);

        ContactGroup::create([
            'user_id' => auth()->id(),
            'name' => $this->groupName,
            'color' => $this->groupColor,
        ]);

        $this->showGroupForm = false;
        $this->reset(['groupName', 'groupColor']);
    }

    public function deleteGroup(int $id)
    {
        ContactGroup::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function render()
    {
        $query = Contact::where('user_id', auth()->id())
            ->with('group');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterGroup) {
            $query->where('group_id', $this->filterGroup);
        }

        $contacts = $query->latest()->paginate(15);
        $groups = ContactGroup::where('user_id', auth()->id())->withCount('contacts')->get();

        return view('livewire.contacts', [
            'contacts' => $contacts,
            'groups' => $groups,
        ])->layout('components.layouts.panel', ['title' => 'Rehber']);
    }
}
