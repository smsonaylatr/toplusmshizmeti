<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139,92,246,.2), rgba(139,92,246,.08));">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight" style="color: var(--admin-text-primary);">Banka Hesapları</h1>
                <p class="text-sm mt-0.5" style="color: var(--admin-text-secondary);">Kullanıcılara gösterilen banka hesaplarını yönet</p>
            </div>
        </div>
        <button wire:click="openForm()" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Hesap Ekle
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash-success mb-4">{{ session('success') }}</div>
    @endif

    {{-- Hesap Listesi --}}
    <div class="glass-card overflow-hidden">
        @if(count($accounts) === 0)
            <div class="py-16 text-center">
                <svg class="w-10 h-10 mx-auto mb-3" style="color: var(--admin-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                <p class="text-sm" style="color: var(--admin-text-secondary);">Henüz banka hesabı eklenmemiş.</p>
                <button wire:click="openForm()" class="mt-3 btn-primary text-xs px-4 py-2">İlk Hesabı Ekle</button>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="text-left">Banka / Hesap</th>
                        <th class="text-left">IBAN</th>
                        <th class="text-left">Şube</th>
                        <th class="text-center">Durum</th>
                        <th class="text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $i => $acc)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(139,92,246,.1);">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[13px]" style="color: var(--admin-text-primary);">{{ $acc['bank_name'] }}</p>
                                        <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $acc['account_name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="text-[12px] font-mono" style="color: var(--admin-text-primary);">{{ wordwrap($acc['iban'], 4, ' ', true) }}</code>
                            </td>
                            <td>
                                <span class="text-[12px]" style="color: var(--admin-text-secondary);">{{ $acc['branch'] ?: '—' }}</span>
                            </td>
                            <td class="text-center">
                                <button wire:click="toggleActive({{ $i }})" class="{{ $acc['is_active'] ? 'status-success' : 'status-danger' }} cursor-pointer hover:opacity-80 transition-opacity">
                                    {{ $acc['is_active'] ? 'Aktif' : 'Pasif' }}
                                </button>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openForm({{ $i }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-all"
                                            style="background: rgba(37,99,235,.08); color: #60a5fa; border: 1px solid rgba(37,99,235,.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Düzenle
                                    </button>
                                    <button wire:click="delete({{ $i }})"
                                            wire:confirm="Bu banka hesabını silmek istediğinize emin misiniz?"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-all"
                                            style="background: rgba(239,68,68,.08); color: #f87171; border: 1px solid rgba(239,68,68,.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Sil
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Kullanıcı Önizleme Notu --}}
    <div class="mt-4 p-4 rounded-xl flex items-start gap-3" style="background: rgba(37,99,235,.05); border: 1px solid rgba(37,99,235,.1);">
        <svg class="w-4 h-4 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-[12px]" style="color: var(--admin-text-secondary);">
            Aktif hesaplar kullanıcı panelinde <strong style="color: var(--admin-text-primary);">Banka Hesapları</strong> sayfasında gösterilir. Pasif hesaplar gizlenir.
        </p>
    </div>

    {{-- Form Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,.6);">
            <div class="w-full max-w-lg rounded-2xl overflow-hidden" style="background: var(--admin-card); border: 1px solid var(--admin-border);">
                {{-- Modal Header --}}
                <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);">
                    <h3 class="text-[15px] font-bold" style="color: var(--admin-text-primary);">
                        {{ $editIndex !== null ? 'Hesabı Düzenle' : 'Yeni Banka Hesabı' }}
                    </h3>
                    <button wire:click="$set('showForm', false)" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-white/5 transition-colors" style="color: var(--admin-text-secondary);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form wire:submit="save" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">Banka Adı *</label>
                            <input wire:model="bankName" type="text" class="admin-input" placeholder="ör: Ziraat Bankası">
                            @error('bankName') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">Hesap Sahibi *</label>
                            <input wire:model="accountName" type="text" class="admin-input" placeholder="ör: Şirket Adı Ltd. Şti.">
                            @error('accountName') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">IBAN *</label>
                            <input wire:model="iban" type="text" class="admin-input font-mono" placeholder="TR00 0000 0000 0000 0000 0000 00">
                            @error('iban') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">Şube Adı</label>
                            <input wire:model="branch" type="text" class="admin-input" placeholder="ör: Merkez Şube">
                        </div>
                        <div>
                            <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">Durum</label>
                            <select wire:model="isActive" class="admin-input">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2" style="border-top: 1px solid var(--admin-border);">
                        <button type="button" wire:click="$set('showForm', false)"
                                class="px-4 py-2 rounded-xl text-[13px] font-medium transition-colors"
                                style="background: var(--admin-inner-bg); color: var(--admin-text-secondary); border: 1px solid var(--admin-border);">
                            İptal
                        </button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span wire:loading.remove wire:target="save">Kaydet</span>
                            <span wire:loading wire:target="save">Kaydediliyor...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
