<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Aksiyon Logları</h1><p class="text-sm text-gray-500 mt-0.5">AI GuardSystem tarafından alınan tüm aksiyonlar</p></div>
    <div class="flex flex-wrap gap-3 mb-4">
        <select wire:model.live="severityFilter" class="admin-select"><option value="">Tüm Seviyeler</option><option value="critical">🔴 Kritik</option><option value="high">🟠 Yüksek</option><option value="medium">🟡 Orta</option><option value="low">🟢 Düşük</option></select>
        <select wire:model.live="actionFilter" class="admin-select"><option value="">Tüm Aksiyonlar</option><option value="warn">Uyarı</option><option value="suspend">Askıya Al</option><option value="block_message">Engelle</option><option value="flag">İşaretle</option><option value="unsuspend">Askı Kaldır</option></select>
    </div>
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Seviye</th><th class="text-left">Kullanıcı</th><th class="text-left hidden sm:table-cell">Aksiyon</th><th class="text-left hidden md:table-cell">Sebep</th><th class="text-center">Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>@forelse($logs as $log)
                    <tr>
                        <td><span class="{{ $log->severity === 'critical' ? 'status-danger' : ($log->severity === 'high' ? 'status-warning' : ($log->severity === 'medium' ? 'status-info' : 'status-success')) }}">{{ strtoupper($log->severity) }}</span></td>
                        <td class="text-gray-300 text-[13px]">{{ $log->user?->name }}</td>
                        <td class="text-gray-500 text-[13px] hidden sm:table-cell">{{ $log->action }}</td>
                        <td class="text-gray-500 text-[12px] max-w-[220px] truncate hidden md:table-cell">{{ $log->reason }}</td>
                        <td class="text-center">@if($log->is_resolved)<span class="status-success">Çözüldü</span>@else<span class="status-warning">Açık</span>@endif</td>
                        <td class="text-right">@unless($log->is_resolved)<button wire:click="resolve({{ $log->id }})" class="btn-success">Çöz</button>@endunless</td>
                    </tr>
                @empty<tr><td colspan="6"><div class="text-center py-10"><p class="text-sm text-gray-600">Log bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $logs->links() }}</div>
    </div>
</div>
