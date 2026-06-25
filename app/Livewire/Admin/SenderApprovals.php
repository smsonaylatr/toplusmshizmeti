<?php

namespace App\Livewire\Admin;

use App\Models\SenderName;
use App\Services\VatanSmsService;
use Livewire\Component;
use Livewire\WithPagination;

class SenderApprovals extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';

    // Gönderici atama modal
    public bool   $showAssignModal  = false;
    public ?int   $assigningUserId  = null;
    public string $assigningUserName = '';
    public array  $vatanSenders     = [];   // VatanSMS'ten gelen liste
    public array  $selectedSenders  = [];   // Admin seçimleri
    public ?string $fetchError      = null;
    public bool   $fetching         = false;
    public array  $alreadyAssigned  = []; // Bu kullanıcıya zaten atanmış gönderici isimleri

    public function approve(int $id): void
    {
        SenderName::findOrFail($id)->update(['status' => 'approved']);
        session()->flash('success', 'Gönderici adı onaylandı.');
    }

    public function reject(int $id): void
    {
        SenderName::findOrFail($id)->update(['status' => 'rejected']);
        session()->flash('success', 'Gönderici adı reddedildi.');
    }

    public function delete(int $id): void
    {
        SenderName::findOrFail($id)->delete();
        session()->flash('success', 'Gönderici adı silindi.');
    }

    // ── VatanSMS'ten onaylı gönderici listesini çek + modal aç ──
    public function openAssignModal(int $userId, string $userName): void
    {
        $this->assigningUserId   = $userId;
        $this->assigningUserName = $userName;
        $this->selectedSenders   = [];
        $this->fetchError        = null;
        $this->vatanSenders      = [];
        $this->fetching          = true;

        // Kullanıcıya zaten atanmış gönderici isimlerini hazırla
        $this->alreadyAssigned = SenderName::where('user_id', $userId)
            ->pluck('name')
            ->toArray();

        $service  = new VatanSmsService();
        $response = $service->getSenders();

        $this->fetching = false;

        if (($response['success'] ?? false) && !empty($response['senders'])) {
            $this->vatanSenders = $response['senders'];
        } elseif (($response['success'] ?? false) && isset($response['data'])) {
            // bazı API versiyonları data key altında döner
            $this->vatanSenders = is_array($response['data']) ? $response['data'] : [];
        } else {
            // Düz liste döndürüyorsa (success + array içeriği)
            $allKeys = array_keys($response);
            $senderList = array_filter($response, fn($v) => is_string($v) || is_array($v));
            if (!empty($senderList)) {
                $this->vatanSenders = array_values($senderList);
            } else {
                $this->fetchError = $response['message'] ?? ($response['description'] ?? 'VatanSMS gönderici listesi alınamadı. API ayarlarını kontrol edin.');
            }
        }

        $this->showAssignModal = true;
    }

    // ── Seçili gönderici adlarını müşteriye ata ──
    public function assignSenders(): void
    {
        if (empty($this->selectedSenders)) {
            $this->fetchError = 'En az bir gönderici seçmelisiniz.';
            return;
        }

        // Kullanıcının mevcut sender sayısı (default belirlemek için)
        $existingCount = SenderName::where('user_id', $this->assigningUserId)->count();
        $hasDefault    = SenderName::where('user_id', $this->assigningUserId)
            ->where('is_default', true)->exists();

        $firstNew = true;

        foreach ($this->selectedSenders as $senderName) {
            $senderName = trim((string) $senderName);
            if (empty($senderName)) continue;

            $exists = SenderName::where('user_id', $this->assigningUserId)
                ->where('name', $senderName)
                ->exists();

            if (!$exists) {
                // Hiç sender yoksa ya da default yoksa ilk yeniye default ver
                $isDefault = ($existingCount === 0 && !$hasDefault && $firstNew);

                SenderName::create([
                    'user_id'    => $this->assigningUserId,
                    'name'       => $senderName,
                    'status'     => 'approved',
                    'is_default' => $isDefault,
                ]);

                if ($isDefault) $firstNew = false;
                $existingCount++;
            }
        }

        $this->showAssignModal = false;
        $this->reset(['assigningUserId', 'assigningUserName', 'selectedSenders', 'vatanSenders', 'fetchError']);
        session()->flash('success', count($this->selectedSenders) . ' gönderici başarıyla atandı.');
    }

    public function closeModal(): void
    {
        $this->showAssignModal = false;
        $this->reset(['assigningUserId', 'assigningUserName', 'selectedSenders', 'vatanSenders', 'fetchError']);
    }

    public function render()
    {
        $senders = SenderName::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        // Unique kullanıcılar (gönderici atamak için)
        $users = SenderName::with('user')
            ->select('user_id')
            ->distinct()
            ->latest()
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id');

        return view('livewire.admin.sender-approvals', compact('senders', 'users'))
            ->layout('components.layouts.admin', ['title' => 'Gönderici Adı Onayları']);
    }
}
