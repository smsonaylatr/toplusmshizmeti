<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">WhatsApp Oturumları</h1><p class="text-sm text-gray-500 mt-0.5">Aktif WhatsApp bağlantı durumları</p></div>
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-left">Telefon</th><th class="text-left hidden sm:table-cell">Görüntü Adı</th><th class="text-center">Durum</th><th class="text-right">Bağlantı</th></tr></thead>
                <tbody>@forelse($sessions as $s)
                    <tr><td class="text-gray-300 text-[13px]">{{ $s->user?->name ?? '—' }}</td><td class="text-gray-400 text-[13px]">{{ $s->phone_number }}</td><td class="text-gray-500 text-[13px] hidden sm:table-cell">{{ $s->display_name ?? '—' }}</td><td class="text-center"><span class="{{ $s->is_active ? 'status-success' : 'status-danger' }}">{{ $s->is_active ? 'Aktif' : 'Pasif' }}</span></td><td class="text-right text-[11px] text-gray-600">{{ $s->connected_at?->format('d.m.Y H:i') ?? '—' }}</td></tr>
                @empty<tr><td colspan="5"><div class="text-center py-10"><p class="text-sm text-gray-600">Oturum bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $sessions->links() }}</div>
    </div>
</div>
