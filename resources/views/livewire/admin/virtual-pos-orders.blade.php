<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,.2), rgba(16,185,129,.08));">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight" style="color: var(--admin-text-primary);">Sanal POS Siparişleri</h1>
            <p class="text-sm mt-0.5" style="color: var(--admin-text-secondary);">PayTR Online Ödemeler</p>
        </div>
    </div>

    {{-- İstatistikler --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(16,185,129,.06), rgba(16,185,129,.02));">
            <div class="w-8 h-8 rounded-lg mb-3 flex items-center justify-center" style="background: rgba(16,185,129,.12);">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-[11px] font-medium mb-1" style="color: var(--admin-text-secondary);">Toplam Başarılı</p>
            <p class="text-3xl font-bold text-emerald-400">{{ $stats['total'] }}</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(37,99,235,.06), rgba(37,99,235,.02));">
            <div class="w-8 h-8 rounded-lg mb-3 flex items-center justify-center" style="background: rgba(37,99,235,.12);">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-[11px] font-medium mb-1" style="color: var(--admin-text-secondary);">Toplam Gelir</p>
            <p class="text-3xl font-bold text-blue-400">{{ number_format($stats['revenue'], 2, ',', '.') }} ₺</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(245,158,11,.06), rgba(245,158,11,.02));">
            <div class="w-8 h-8 rounded-lg mb-3 flex items-center justify-center" style="background: rgba(245,158,11,.12);">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-[11px] font-medium mb-1" style="color: var(--admin-text-secondary);">Bekleyen</p>
            <p class="text-3xl font-bold text-amber-400">{{ $stats['pending'] }}</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(239,68,68,.06), rgba(239,68,68,.02));">
            <div class="w-8 h-8 rounded-lg mb-3 flex items-center justify-center" style="background: rgba(239,68,68,.12);">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-[11px] font-medium mb-1" style="color: var(--admin-text-secondary);">Başarısız</p>
            <p class="text-3xl font-bold text-red-400">{{ $stats['failed'] }}</p>
        </div>
    </div>

    {{-- Arama & Filtre --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color: var(--admin-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Kullanıcı, e-posta veya sipariş no..."
                   class="admin-input"
                   style="padding-left: 2.25rem !important;">
        </div>

        <select wire:model.live="statusFilter" class="admin-select min-w-[160px]">
            <option value="">Tüm Durumlar</option>
            <option value="paid">Ödendi</option>
            <option value="pending">Bekliyor</option>
            <option value="failed">Başarısız</option>
            <option value="cancelled">İptal</option>
        </select>
    </div>

    {{-- Tablo --}}
    <div class="glass-card overflow-hidden">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="text-left">Kullanıcı</th>
                    <th class="text-left">Paket</th>
                    <th class="text-right">Tutar</th>
                    <th class="text-center">Durum</th>
                    <th class="text-right">Tarih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <div class="font-semibold text-[13px]" style="color: var(--admin-text-primary);">{{ $order->user?->name }}</div>
                            <div class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $order->user?->email }}</div>
                            <div class="text-[10px] font-mono mt-0.5" style="color: var(--admin-text-muted);">{{ $order->merchant_oid }}</div>
                        </td>
                        <td>
                            <div class="font-semibold text-[13px]" style="color: var(--admin-text-primary);">{{ $order->package_name }}</div>
                            <div class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">{{ $order->sms_amount_formatted }} SMS</div>
                        </td>
                        <td class="text-right">
                            <span class="font-bold text-[14px]" style="color: var(--admin-text-primary);">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</span>
                        </td>
                        <td class="text-center">
                            @if($order->status === 'paid')
                                <span class="status-success">Ödendi</span>
                            @elseif($order->status === 'pending')
                                <span class="status-warning">Bekliyor</span>
                            @elseif($order->status === 'failed')
                                <span class="status-danger">Başarısız</span>
                            @else
                                <span style="background: var(--admin-inner-bg); color: var(--admin-text-secondary); padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600;">İptal</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <span class="text-[12px]" style="color: var(--admin-text-secondary);">{{ $order->created_at->format('d.m.Y') }}</span>
                            <div class="text-[11px]" style="color: var(--admin-text-muted);">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-16">
                            <svg class="w-10 h-10 mx-auto mb-3" style="color: var(--admin-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="text-sm" style="color: var(--admin-text-secondary);">Kayıt bulunamadı.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--admin-border);">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
