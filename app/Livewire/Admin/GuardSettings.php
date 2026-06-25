<?php
namespace App\Livewire\Admin;
use Livewire\Component;

class GuardSettings extends Component
{
    public int $dailyMessageLimit = 1000;
    public int $hourlyMessageLimit = 200;
    public int $suspendThreshold = 80;
    public int $warnThreshold = 50;
    public bool $autoSuspendEnabled = true;
    public bool $bdkFilterEnabled = true;
    public bool $spamFilterEnabled = true;

    public function mount()
    {
        // Ayarları config veya DB'den yükle
        $this->dailyMessageLimit = config('guard.daily_limit', 1000);
        $this->hourlyMessageLimit = config('guard.hourly_limit', 200);
        $this->suspendThreshold = config('guard.suspend_threshold', 80);
        $this->warnThreshold = config('guard.warn_threshold', 50);
    }

    public function save()
    {
        session()->flash('success', 'Ayarlar kaydedildi.');
    }

    public function render()
    {
        return view('livewire.admin.guard-settings')
            ->layout('components.layouts.admin', ['title' => 'Guard Ayarları']);
    }
}
