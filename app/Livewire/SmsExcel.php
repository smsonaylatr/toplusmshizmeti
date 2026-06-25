<?php

namespace App\Livewire;

use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\SenderName;
use Livewire\Component;
use Livewire\WithFileUploads;

class SmsExcel extends Component
{
    use WithFileUploads;

    public $excelFile;
    public string $senderName = '';
    public string $smsType = 'Normal';
    public string $sendTime = 'Hemen Gönder';
    public string $messageType = '';
    public string $message = '';
    public int $charCount = 0;
    public int $smsCount = 1;

    /** Alpine submit'ten önce mesajı sync et */
    public function setMessage(string $value): void
    {
        $this->message   = $value;
        $this->charCount = mb_strlen($value);
        $this->smsCount  = $this->charCount > 0 ? (int) ceil($this->charCount / 160) : 1;
    }

    public function send()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'senderName' => 'required',
            'message' => 'required|min:1',
        ]);

        // TODO: Parse Excel file and extract phone numbers
        // For now, show success message
        session()->flash('success', 'Excel dosyası işleniyor, SMS\'ler kuyruğa eklenecek.');
        $this->reset(['excelFile', 'message', 'charCount']);
        $this->smsCount = 1;
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

        return view('livewire.sms-excel', [
            'senders' => $senders,
        ])->layout('components.layouts.panel', ['title' => 'Excel ile SMS']);
    }
}
