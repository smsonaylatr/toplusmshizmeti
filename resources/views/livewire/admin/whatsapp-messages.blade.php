<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">WhatsApp Mesajları</h1><p class="text-sm text-gray-500 mt-0.5">Tüm WhatsApp mesajlarını izleyin</p></div>
    <div class="glass-card overflow-hidden">
        <div class="p-4 flex flex-col sm:flex-row gap-3" style="border-bottom: 1px solid var(--admin-border);">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Numara veya mesaj ara..." class="admin-input !pl-10">
            </div>
            <select wire:model.live="statusFilter" class="admin-select w-full sm:w-auto">
                <option value="">Tüm Durumlar</option><option value="sent">Gönderildi</option><option value="delivered">İletildi</option><option value="failed">Başarısız</option><option value="pending">Bekliyor</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-left">Alıcı</th><th class="text-left hidden md:table-cell">Mesaj</th><th class="text-center">Durum</th><th class="text-right">Tarih</th></tr></thead>
                <tbody>@forelse($messages as $msg)
                    <tr><td class="text-gray-300 text-[13px]">{{ $msg->user?->name ?? '—' }}</td><td class="text-gray-400 text-[13px]">{{ $msg->recipient }}</td><td class="text-gray-500 text-[12px] max-w-[280px] truncate hidden md:table-cell">{{ $msg->message }}</td><td class="text-center"><span class="{{ $msg->status === 'delivered' ? 'status-success' : ($msg->status === 'sent' ? 'status-info' : ($msg->status === 'failed' ? 'status-danger' : 'status-warning')) }}">{{ $msg->status }}</span></td><td class="text-right text-[11px] text-gray-600">{{ $msg->created_at->format('d.m.Y H:i') }}</td></tr>
                @empty<tr><td colspan="5"><div class="text-center py-10"><p class="text-sm text-gray-600">Mesaj bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $messages->links() }}</div>
    </div>
</div>
