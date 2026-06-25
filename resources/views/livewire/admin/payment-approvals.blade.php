<div>
    {{-- Header + İstatistikler + Arama tek satırda --}}
    <div class="mb-5">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, rgba(245,158,11,.2), rgba(245,158,11,.08));">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight" style="color: var(--admin-text-primary);">Ödeme Onayları</h1>
                    <p class="text-xs mt-0.5" style="color: var(--admin-text-secondary);">Havale/EFT bildirimlerini incele ve onayla</p>
                </div>
            </div>
            {{-- İstatistik Pilleri --}}
            <div class="flex items-center gap-2 flex-wrap">
                <button wire:click="$set('statusFilter','')"
                        class="px-3 py-1.5 rounded-lg text-[12px] font-semibold border transition-all"
                        style="{{ $statusFilter === '' ? 'background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.4); color: #a5b4fc;' : 'background: var(--admin-inner-bg); border-color: var(--admin-border); color: var(--admin-text-muted);' }}">
                    Tümü
                </button>
                <button wire:click="$set('statusFilter','pending')"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[12px] font-semibold border transition-all"
                        style="{{ $statusFilter === 'pending' ? 'background: rgba(245,158,11,.12); border-color: rgba(245,158,11,.4); color: #fbbf24;' : 'background: var(--admin-inner-bg); border-color: var(--admin-border); color: var(--admin-text-muted);' }}">
                    <span class="w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold" style="background: rgba(245,158,11,.2); color: #fbbf24;">{{ $stats['pending'] }}</span>
                    Bekleyen
                </button>
                <button wire:click="$set('statusFilter','approved')"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[12px] font-semibold border transition-all"
                        style="{{ $statusFilter === 'approved' ? 'background: rgba(16,185,129,.1); border-color: rgba(16,185,129,.4); color: #34d399;' : 'background: var(--admin-inner-bg); border-color: var(--admin-border); color: var(--admin-text-muted);' }}">
                    <span class="w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold" style="background: rgba(16,185,129,.15); color: #34d399;">{{ $stats['approved'] }}</span>
                    Onaylanan
                </button>
                <button wire:click="$set('statusFilter','rejected')"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[12px] font-semibold border transition-all"
                        style="{{ $statusFilter === 'rejected' ? 'background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.4); color: #f87171;' : 'background: var(--admin-inner-bg); border-color: var(--admin-border); color: var(--admin-text-muted);' }}">
                    <span class="w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-bold" style="background: rgba(239,68,68,.15); color: #f87171;">{{ $stats['rejected'] }}</span>
                    Reddedilen
                </button>
            </div>
        </div>
    </div>


    {{-- Arama --}}
    <div class="mb-4 flex gap-3">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color: var(--admin-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Kullanıcı adı veya e-posta..."
                   class="admin-input" style="padding-left: 2.25rem !important;">
        </div>
        <select wire:model.live="statusFilter" class="admin-select min-w-[160px]">
            <option value="pending">Bekleyen</option>
            <option value="approved">Onaylanan</option>
            <option value="rejected">Reddedilen</option>
            <option value="">Tümü</option>
        </select>
    </div>

    {{-- Tablo --}}
    <div class="glass-card overflow-hidden">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="text-left">Kullanıcı</th>
                    <th class="text-left">Banka / Gönderi</th>
                    <th class="text-right">Tutar</th>
                    <th class="text-left">Tarih</th>
                    <th class="text-center">Durum</th>
                    <th class="text-right">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>
                            <div class="font-semibold text-[13px]" style="color: var(--admin-text-primary);">{{ $p->user?->name }}</div>
                            <div class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $p->user?->email }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-[13px]" style="color: var(--admin-text-primary);">{{ $p->bank }}</div>
                            <div class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $p->sender_name }} · {{ $p->phone }}</div>
                        </td>
                        <td class="text-right">
                            <span class="font-bold text-[14px]" style="color: var(--admin-text-primary);">{{ number_format($p->amount, 2, ',', '.') }} ₺</span>
                            @php
                                // Tutara göre en yakın paketi bul
                                $suggested = null; $minDiff = PHP_INT_MAX;
                                foreach(\App\Livewire\Admin\PaymentApprovals::PACKAGES as $pkg) {
                                    $diff = abs($p->amount - round($pkg['price'] * 1.2, 2));
                                    if ($diff < $minDiff) { $minDiff = $diff; $suggested = $pkg; }
                                }
                            @endphp
                            @if($suggested && $minDiff < 1000)
                                <div class="text-[10px] mt-0.5 text-emerald-400">≈ {{ $suggested['name'] }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="text-[12px]" style="color: var(--admin-text-secondary);">
                                {{ $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('d.m.Y') : '—' }}
                            </span>
                            <div class="text-[11px]" style="color: var(--admin-text-muted);">{{ $p->created_at->format('H:i') }}</div>
                        </td>
                        <td class="text-center">
                            @if($p->status === 'approved')
                                <span class="status-success">Onaylandı</span>
                            @elseif($p->status === 'rejected')
                                <span class="status-danger">Reddedildi</span>
                            @else
                                <span class="status-warning">Bekliyor</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($p->status === 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openApprove({{ $p->id }})"
                                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-[12px] font-semibold transition-all"
                                            style="background: rgba(16,185,129,.1); color: #10b981; border: 1px solid rgba(16,185,129,.25);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Onayla
                                    </button>
                                    <button wire:click="reject({{ $p->id }})"
                                            wire:confirm="Bu ödemeyi reddetmek istediğinize emin misiniz?"
                                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-[12px] font-semibold transition-all"
                                            style="background: rgba(239,68,68,.08); color: #f87171; border: 1px solid rgba(239,68,68,.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reddet
                                    </button>
                                </div>
                            @elseif($p->status === 'approved')
                                <span class="text-[11px]" style="color: var(--admin-text-muted);">{{ $p->approved_credits ? number_format($p->approved_credits).' SMS yüklendi' : '—' }}</span>
                            @else
                                <span class="text-[11px]" style="color: var(--admin-text-muted);">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <svg class="w-10 h-10 mx-auto mb-3" style="color: var(--admin-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm" style="color: var(--admin-text-secondary);">Kayıt bulunamadı.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--admin-border);">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    {{-- ═══ Onay Modalı ═══ --}}
    @if($approveId)
        @php
            $pm = $payments->firstWhere('id', $approveId)
                ?? \App\Models\PaymentNotification::with('user')->find($approveId);
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,.65);">
            <div class="w-full max-w-lg rounded-2xl overflow-hidden" style="background: var(--admin-card); border: 1px solid var(--admin-border);">

                {{-- Modal Header --}}
                <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);">
                    <div>
                        <h3 class="text-[15px] font-bold" style="color: var(--admin-text-primary);">Ödeme Onaylama</h3>
                        <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $pm?->user?->name }} · {{ number_format($pm?->amount, 2, ',', '.') }} ₺</p>
                    </div>
                    <button wire:click="$set('approveId', null)" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-white/5 transition-colors" style="color: var(--admin-text-muted);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Mod Seçimi --}}
                <div class="px-6 pt-5">
                    <div class="flex rounded-xl overflow-hidden" style="border: 1px solid var(--admin-border);">
                        <button wire:click="$set('approveMode','package')"
                                class="flex-1 py-2 text-[12px] font-semibold transition-all"
                                style="{{ $approveMode === 'package' ? 'background: rgba(37,99,235,.15); color: #60a5fa;' : 'color: var(--admin-text-muted);' }}">
                            📦 Paket Seç
                        </button>
                        <button wire:click="$set('approveMode','custom')"
                                class="flex-1 py-2 text-[12px] font-semibold transition-all"
                                style="{{ $approveMode === 'custom' ? 'background: rgba(37,99,235,.15); color: #60a5fa;' : 'color: var(--admin-text-muted);' }}">
                            ✏️ Manuel Kredi
                        </button>
                    </div>
                </div>

                {{-- Paket Seçimi --}}
                @if($approveMode === 'package')
                    <div class="px-6 py-4 space-y-2">
                        @foreach($this::PACKAGES as $i => $pkg)
                            @php $vatPrice = round($pkg['price'] * 1.2, 2); @endphp
                            <label wire:click="$set('packageIndex', {{ $i }})"
                                   class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all"
                                   style="{{ $packageIndex === $i ? 'background: rgba(37,99,235,.1); border: 1.5px solid rgba(37,99,235,.4);' : 'background: var(--admin-inner-bg); border: 1.5px solid var(--admin-border);' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                                         style="{{ $packageIndex === $i ? 'border-color: #3b82f6; background: #3b82f6;' : 'border-color: var(--admin-border);' }}">
                                        @if($packageIndex === $i)
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-[13px]" style="color: var(--admin-text-primary);">{{ $pkg['name'] }}</span>
                                    @php $diff = abs($pm?->amount - $vatPrice); @endphp
                                    @if($diff < 50)
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" style="background: rgba(16,185,129,.15); color: #10b981;">ÖNERİLEN</span>
                                    @endif
                                </div>
                                <span class="text-[12px] font-mono" style="color: var(--admin-text-secondary);">{{ number_format($vatPrice, 2, ',', '.') }} ₺ (KDV dahil)</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    {{-- Manuel Kredi --}}
                    <div class="px-6 py-6">
                        <label class="text-[12px] font-medium mb-1.5 block" style="color: var(--admin-text-secondary);">Yüklenecek SMS Kredisi</label>
                        <input wire:model="customCredits" type="number" min="1"
                               class="admin-input" placeholder="ör: 5000">
                        @error('customCredits') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                        <p class="text-[11px] mt-2" style="color: var(--admin-text-muted);">Tutara göre öneri: {{ $pm ? number_format(round($pm->amount * 10)) : '—' }} SMS (1 TL = 10 SMS)</p>
                    </div>
                @endif

                {{-- Modal Footer --}}
                <div class="px-6 py-4 flex items-center justify-end gap-3" style="border-top: 1px solid var(--admin-border);">
                    <button wire:click="$set('approveId', null)"
                            class="px-4 py-2 rounded-xl text-[13px] font-medium"
                            style="background: var(--admin-inner-bg); color: var(--admin-text-secondary); border: 1px solid var(--admin-border);">
                        İptal
                    </button>
                    <button wire:click="approve" wire:loading.attr="disabled"
                            class="btn-primary"
                            @if($approveMode === 'package' && $packageIndex < 0) disabled @endif>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span wire:loading.remove wire:target="approve">Onayla ve SMS Yükle</span>
                        <span wire:loading wire:target="approve">Yükleniyor...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
