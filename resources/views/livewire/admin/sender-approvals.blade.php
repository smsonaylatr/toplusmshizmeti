<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(245,158,11,.2), rgba(245,158,11,.1));">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Gönderici Adı Onayları</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Başvuruları onaylayın veya VatanSMS'ten gönderici atayın</p>
                </div>
            </div>

            {{-- VatanSMS'ten Gönderici Ata butonu (kullanıcı seç) --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all"
                        style="background: rgba(37,99,235,.15); color: #60a5fa; border: 1px solid rgba(37,99,235,.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    VatanSMS'ten Gönderici Ata
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                {{-- Kullanıcı dropdown --}}
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute right-0 mt-2 w-64 rounded-xl z-50 overflow-hidden"
                     style="background: var(--admin-card); border: 1px solid var(--admin-border); box-shadow: 0 10px 30px rgba(0,0,0,.3);" x-cloak>
                    <div class="px-4 py-2.5" style="border-bottom: 1px solid var(--admin-border);">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Müşteriyi Seç</p>
                    </div>
                    <div class="max-h-52 overflow-y-auto py-1">
                        @forelse($users as $u)
                            <button wire:click="openAssignModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                    @click="open = false"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-left text-[13px] hover:bg-white/5 transition-colors" style="color: var(--admin-text);">
                                <div class="w-7 h-7 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400 text-[11px] font-bold shrink-0">
                                    {{ mb_substr($u->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium truncate text-white">{{ $u->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ $u->email }}</p>
                                </div>
                            </button>
                        @empty
                            <div class="px-4 py-6 text-center">
                                <p class="text-[12px] text-gray-500">Henüz gönderici başvurusu yapan kullanıcı yok</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="flash-success mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['pending' => '⏳ Bekleyen', 'approved' => '✅ Onaylı', 'rejected' => '❌ Reddedilen', '' => 'Tümü'] as $val => $label)
            <button wire:click="$set('statusFilter', '{{ $val }}')"
                    class="px-4 py-2 text-[12px] font-medium rounded-xl transition-all {{ $statusFilter === $val ? 'btn-primary' : 'glass-card text-gray-400 hover:text-white' }}"
                    style="{{ $statusFilter !== $val ? 'padding: 8px 16px;' : '' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="text-left">Kullanıcı</th>
                        <th class="text-left">Gönderici Adı</th>
                        <th class="text-center">Kaynak</th>
                        <th class="text-center">Durum</th>
                        <th class="text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($senders as $s)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400 text-[11px] font-bold shrink-0">
                                        {{ mb_substr($s->user?->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-[13px] text-white font-medium">{{ $s->user?->name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $s->user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-white font-semibold text-[13px]">{{ $s->name }}</span>
                            </td>
                            <td class="text-center">
                                @if($s->vatansms_assigned ?? false)
                                    <span class="status-info">VatanSMS</span>
                                @else
                                    <span style="background: rgba(148,163,184,.1); color: #94a3b8; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600;">Manuel</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="{{ $s->status === 'approved' ? 'status-success' : ($s->status === 'pending' ? 'status-warning' : 'status-danger') }}">
                                    {{ $s->status === 'approved' ? 'Onaylı' : ($s->status === 'pending' ? 'Bekliyor' : 'Reddedildi') }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Bu kullanıcıya VatanSMS'ten gönderici ata --}}
                                    <button wire:click="openAssignModal({{ $s->user_id }}, '{{ addslashes($s->user?->name ?? '') }}')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold transition-all"
                                            style="background: rgba(37,99,235,.1); color: #60a5fa; border: 1px solid rgba(37,99,235,.2);">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                        VatanSMS'ten Ata
                                    </button>
                                    @if($s->status === 'pending')
                                        <button wire:click="approve({{ $s->id }})" class="btn-success">Onayla</button>
                                        <button wire:click="reject({{ $s->id }})" class="btn-danger">Reddet</button>
                                    @endif
                                    {{-- Sil --}}
                                    <button wire:click="delete({{ $s->id }})"
                                            wire:confirm="'{{ $s->name }}' başlığını silmek istediğinize emin misiniz?"
                                            class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-all"
                                            style="background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Sil
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-10">
                                    <p class="text-sm text-gray-600">Kayıt bulunamadı</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $senders->links() }}</div>
    </div>

    {{-- ═══════════════════════════════════════════════
         VatanSMS Gönderici Atama Modal
    ═══════════════════════════════════════════════ --}}
    @if($showAssignModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal --}}
            <div class="relative w-full max-w-lg z-10 rounded-2xl overflow-hidden"
                 style="background: var(--admin-card); border: 1px solid var(--admin-border); box-shadow: 0 25px 50px rgba(0,0,0,.4);">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid var(--admin-border);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(37,99,235,.15);">
                            <svg class="w-4.5 h-4.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-bold text-white">VatanSMS Gönderici Ata</h3>
                            <p class="text-[12px] text-gray-500 mt-0.5">{{ $assigningUserName }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-white/5 transition-colors text-gray-500 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    @if($fetchError)
                        <div class="p-4 rounded-xl mb-4" style="background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2);">
                            <div class="flex items-center gap-2 text-red-400 text-[13px]">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $fetchError }}
                            </div>
                        </div>
                    @elseif(empty($vatanSenders))
                        <div class="flex items-center justify-center py-8 gap-3">
                            <svg class="w-5 h-5 animate-spin text-blue-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span class="text-[13px] text-gray-400">VatanSMS'ten gönderici listesi çekiliyor...</span>
                        </div>
                    @else
                        <p class="text-[12px] text-gray-500 mb-4">
                            VatanSMS hesabınızdaki onaylı gönderici başlıklarından seçin. Seçilenler <strong class="text-white">{{ $assigningUserName }}</strong> adlı müşteriye atanacak.
                        </p>

                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            @foreach($vatanSenders as $sender)
                                @php
                                    $senderVal = is_array($sender) ? ($sender['sender'] ?? $sender['name'] ?? (string)$sender) : (string)$sender;
                                @endphp
                                <label class="flex items-center gap-3 p-3.5 rounded-xl cursor-pointer transition-all"
                                       style="background: var(--admin-bg); border: 1px solid {{ in_array($senderVal, $selectedSenders) ? 'rgba(37,99,235,.4)' : 'var(--admin-border)' }};">
                                    <input type="checkbox"
                                           wire:model="selectedSenders"
                                           value="{{ $senderVal }}"
                                           class="w-4 h-4 rounded accent-blue-500">
                                    <div class="flex-1">
                                        <p class="text-[13px] text-white font-semibold">{{ $senderVal }}</p>
                                        @if(is_array($sender) && isset($sender['status']))
                                            <p class="text-[11px] text-emerald-400 mt-0.5">✓ VatanSMS onaylı</p>
                                        @else
                                            <p class="text-[11px] text-emerald-400 mt-0.5">✓ VatanSMS onaylı</p>
                                        @endif
                                    </div>
                                    @if(in_array($senderVal, $alreadyAssigned))
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background: rgba(16,185,129,.1); color: #10b981;">Zaten Atanmış</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                        @if(count($selectedSenders) > 0)
                            <div class="mt-4 p-3 rounded-xl" style="background: rgba(37,99,235,.08); border: 1px solid rgba(37,99,235,.2);">
                                <p class="text-[12px] text-blue-400">
                                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ count($selectedSenders) }} gönderici seçildi: <strong>{{ implode(', ', $selectedSenders) }}</strong>
                                </p>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Footer --}}
                @if(!$fetchError && !empty($vatanSenders))
                    <div class="flex items-center justify-end gap-3 px-6 py-4" style="border-top: 1px solid var(--admin-border);">
                        <button wire:click="closeModal"
                                class="px-4 py-2.5 rounded-xl text-[13px] font-medium transition-colors"
                                style="background: var(--admin-bg); color: var(--admin-text); border: 1px solid var(--admin-border);">
                            İptal
                        </button>
                        <button wire:click="assignSenders"
                                wire:loading.attr="disabled"
                                class="btn-primary transition-all"
                                :disabled="$wire.selectedSenders.length === 0"
                                :style="$wire.selectedSenders.length === 0 ? 'opacity:.45; cursor:not-allowed;' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span wire:loading.remove wire:target="assignSenders">Gönderici{{ count($selectedSenders) > 1 ? 'leri' : 'yi' }} Ata</span>
                            <span wire:loading wire:target="assignSenders">Atanıyor...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
