<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Askıya Alınan Kullanıcılar</h1><p class="text-sm text-gray-500 mt-0.5">AI veya admin tarafından askıya alınan hesaplar</p></div>
    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-left hidden sm:table-cell">Sebep</th><th class="text-center hidden sm:table-cell">Tarih</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>@forelse($users as $user)
                    <tr>
                        <td><a href="{{ route('admin.users.detail', $user->id) }}" class="text-white hover:text-indigo-400 font-medium text-[13px] transition-colors">{{ $user->name }}</a><p class="text-[11px] text-gray-600">{{ $user->email }}</p></td>
                        <td class="text-gray-500 text-[13px] hidden sm:table-cell">{{ $user->suspension_reason ?? '—' }}</td>
                        <td class="text-center text-[11px] text-gray-600 hidden sm:table-cell">{{ $user->suspended_at?->format('d.m.Y H:i') ?? '—' }}</td>
                        <td class="text-right"><button wire:click="unsuspend({{ $user->id }})" wire:confirm="Bu kullanıcının askısını kaldırmak istediğinize emin misiniz?" class="btn-success">✅ Askıyı Kaldır</button></td>
                    </tr>
                @empty<tr><td colspan="4"><div class="text-center py-10"><div class="w-12 h-12 rounded-2xl mx-auto mb-3 flex items-center justify-center" style="background: var(--admin-bg);"><span class="text-2xl">✨</span></div><p class="text-sm text-gray-600">Askıda kullanıcı yok</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $users->links() }}</div>
    </div>
</div>
