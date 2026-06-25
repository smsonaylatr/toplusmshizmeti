<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Services\Guard\MessageFilter as MessageFilterService;

class MessageScan extends Component
{
    public string $testMessage = '';
    public ?array $scanResult = null;

    public function scan()
    {
        $this->validate(['testMessage' => 'required|min:3']);

        $filter = new MessageFilterService();
        $this->scanResult = $filter->analyze($this->testMessage);
    }

    public function render()
    {
        return view('livewire.admin.message-scan')
            ->layout('components.layouts.admin', ['title' => 'Mesaj Tarama']);
    }
}
