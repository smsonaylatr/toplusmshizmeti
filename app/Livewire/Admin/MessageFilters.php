<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MessageFilter;

class MessageFilters extends Component
{
    use WithPagination;
    public string $pattern = '';
    public string $category = 'custom';
    public string $severity = 'medium';
    public bool $isRegex = false;
    public ?int $editingId = null;

    public function save()
    {
        $this->validate([
            'pattern' => 'required|min:2',
            'category' => 'required|in:bdk,spam,fraud,custom',
            'severity' => 'required|in:low,medium,high',
        ]);

        MessageFilter::updateOrCreate(
            ['id' => $this->editingId],
            [
                'pattern' => $this->pattern,
                'category' => $this->category,
                'severity' => $this->severity,
                'is_regex' => $this->isRegex,
                'created_by' => auth()->id(),
            ]
        );

        $this->reset(['pattern', 'category', 'severity', 'isRegex', 'editingId']);
        session()->flash('success', 'Filtre kaydedildi.');
    }

    public function edit(int $id)
    {
        $filter = MessageFilter::findOrFail($id);
        $this->editingId = $filter->id;
        $this->pattern = $filter->pattern;
        $this->category = $filter->category;
        $this->severity = $filter->severity;
        $this->isRegex = $filter->is_regex;
    }

    public function toggleActive(int $id)
    {
        $filter = MessageFilter::findOrFail($id);
        $filter->update(['is_active' => !$filter->is_active]);
    }

    public function delete(int $id)
    {
        MessageFilter::findOrFail($id)->delete();
        session()->flash('success', 'Filtre silindi.');
    }

    public function render()
    {
        $filters = MessageFilter::latest()->paginate(20);
        return view('livewire.admin.message-filters', compact('filters'))
            ->layout('components.layouts.admin', ['title' => 'Mesaj Filtreleri']);
    }
}
