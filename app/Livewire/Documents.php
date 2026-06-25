<?php

namespace App\Livewire;

use App\Models\Document as DocumentModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use WithFileUploads;

    public $contractFile;
    public $identityFile;
    public $residenceFile;
    public $taxPlateFile;
    public $activityFile;
    public $signatureFile;

    public function uploadDocument($type, $propertyName)
    {
        $this->validate([
            $propertyName => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $this->$propertyName;
        $path = $file->store('documents/' . auth()->id(), 'public');

        // Delete old document of same type
        DocumentModel::where('user_id', auth()->id())
            ->where('type', $type)
            ->delete();

        DocumentModel::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        $this->reset($propertyName);
        session()->flash('success', 'Evrak başarıyla yüklendi. Onay bekleniyor.');
    }

    public function uploadContract() { $this->uploadDocument('contract', 'contractFile'); }
    public function uploadIdentity() { $this->uploadDocument('identity', 'identityFile'); }
    public function uploadResidence() { $this->uploadDocument('residence', 'residenceFile'); }
    public function uploadTaxPlate() { $this->uploadDocument('tax_plate', 'taxPlateFile'); }
    public function uploadActivity() { $this->uploadDocument('activity_certificate', 'activityFile'); }
    public function uploadSignature() { $this->uploadDocument('signature_circular', 'signatureFile'); }

    public function render()
    {
        $documents = DocumentModel::where('user_id', auth()->id())
            ->get()
            ->keyBy('type');

        return view('livewire.documents', [
            'documents' => $documents,
        ])->layout('components.layouts.panel', ['title' => 'Evrak İşlemleri']);
    }
}
