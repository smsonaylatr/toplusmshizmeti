<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Sistem Kara Listesi</h1><p class="text-sm text-gray-500 mt-0.5">Engellenen telefon numaralarını yönetin</p></div>
    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif
    <div class="glass-card overflow-hidden">
        <div class="p-4" style="border-bottom: 1px solid var(--admin-border);">
            <div class="relative max-w-sm"><svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><input wire:model.live.debounce.300ms="search" type="text" placeholder="Numara ara..." class="admin-input !pl-10"></div>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Numara</th><th class="text-left hidden sm:table-cell">Kullanıcı</th><th class="text-right hidden sm:table-cell">Tarih</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>@forelse($numbers as $n)
                    <tr><td class="text-white font-mono text-[13px]">{{ $n->phone_number }}</td><td class="text-gray-400 text-[13px] hidden sm:table-cell">{{ $n->user?->name ?? '—' }}</td><td class="text-right text-[11px] text-gray-600 hidden sm:table-cell">{{ $n->created_at->format('d.m.Y H:i') }}</td><td class="text-right"><button wire:click="delete({{ $n->id }})" wire:confirm="Bu numarayı kara listeden kaldırmak istediğinize emin misiniz?" class="btn-danger">Kaldır</button></td></tr>
                @empty<tr><td colspan="4"><div class="text-center py-10"><p class="text-sm text-gray-600">Kayıt bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $numbers->links() }}</div>
    </div>
</div>
