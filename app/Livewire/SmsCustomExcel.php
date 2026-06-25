<?php

namespace App\Livewire;

use App\Models\SenderName;
use Livewire\Component;
use Livewire\WithFileUploads;

class SmsCustomExcel extends Component
{
    use WithFileUploads;

    public $excelFile;
    public string $senderName = '';
    public string $smsType = 'Normal';
    public string $sendTime = 'Hemen Gönder';
    public string $messageType = '';
    public string $message = '';
    public int $step = 1;
    public array $columns = [];

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            ]);

            // TODO: Parse Excel headers to get column names
            $this->columns = ['Telefon', 'İsim', 'Soyisim', 'Borç'];
            $this->step = 2;
        }
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function send()
    {
        $this->validate([
            'senderName' => 'required',
            'message' => 'required|min:1',
        ]);

        session()->flash('success', 'Özel Excel ile SMS gönderimi kuyruğa eklendi.');
        $this->reset(['excelFile', 'message', 'columns']);
        $this->step = 1;
    }

    public function render()
    {
        $senders = SenderName::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->pluck('name')
            ->toArray();

        if (empty($senders)) {
            $senders = [auth()->user()->phone ?? '08507063457'];
        }

        return view('livewire.sms-custom-excel', [
            'senders' => $senders,
        ])->layout('components.layouts.panel', ['title' => 'Özel Excel ile SMS']);
    }
}
