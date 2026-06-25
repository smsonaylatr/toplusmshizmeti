<div>
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139,92,246,.2), rgba(99,102,241,.1));"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
            <div><h1 class="text-2xl font-bold text-white tracking-tight">AI GuardSystem</h1><p class="text-sm text-gray-500 mt-0.5">Yapay zekâ güvenlik kontrol merkezi</p></div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(239,68,68,.08), rgba(239,68,68,.02));"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,.12);"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5C2.5 17.333 3.462 19 5.002 19z"/></svg></div><div><p class="text-[10px] text-gray-500">Çözülmemiş</p><p class="text-xl font-extrabold text-red-400">{{ $stats['unresolvedFlags'] }}</p></div></div></div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(245,158,11,.02));"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,.12);"><svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg></div><div><p class="text-[10px] text-gray-500">Askıdaki</p><p class="text-xl font-extrabold text-amber-400">{{ $stats['suspendedUsers'] }}</p></div></div></div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(139,92,246,.08), rgba(139,92,246,.02));"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(139,92,246,.12);"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div><div><p class="text-[10px] text-gray-500">Yüksek Risk</p><p class="text-xl font-extrabold text-purple-400">{{ $stats['highRiskUsers'] }}</p></div></div></div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(59,130,246,.08), rgba(59,130,246,.02));"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,.12);"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg></div><div><p class="text-[10px] text-gray-500">Aktif Filtre</p><p class="text-xl font-extrabold text-blue-400">{{ $stats['activeFilters'] }}</p></div></div></div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="glass-card p-3.5 text-center"><p class="text-[10px] text-gray-600">Toplam Flag</p><p class="text-lg font-bold text-white mt-0.5">{{ $stats['totalFlags'] }}</p></div>
        <div class="glass-card p-3.5 text-center"><p class="text-[10px] text-gray-600">Kritik</p><p class="text-lg font-bold text-red-400 mt-0.5">{{ $stats['criticalFlags'] }}</p></div>
        <div class="glass-card p-3.5 text-center"><p class="text-[10px] text-gray-600">Bugün</p><p class="text-lg font-bold text-amber-400 mt-0.5">{{ $stats['todayFlags'] }}</p></div>
        <div class="glass-card p-3.5 text-center"><p class="text-[10px] text-gray-600">Engellenen</p><p class="text-lg font-bold text-orange-400 mt-0.5">{{ $stats['blockedMessages'] }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);"><h3 class="text-sm font-semibold text-white">Son Aksiyonlar</h3><a href="{{ route('admin.guard.logs') }}" class="text-[11px] text-purple-400 hover:text-purple-300">Tümü →</a></div>
            <div class="divide-y" style="--tw-divide-color: rgba(99,102,241,.06);">
                @forelse($recentLogs as $log)
                <div class="px-5 py-3 flex items-center gap-3">
                    <span class="text-[9px] px-2 py-0.5 rounded-lg font-bold shrink-0 {{ $log->severity === 'critical' ? 'status-danger' : ($log->severity === 'high' ? 'status-warning' : ($log->severity === 'medium' ? 'status-info' : 'status-success')) }}">{{ strtoupper($log->action) }}</span>
                    <div class="flex-1 min-w-0"><p class="text-[13px] text-white truncate">{{ $log->user?->name }}</p><p class="text-[11px] text-gray-600 truncate">{{ $log->reason }}</p></div>
                    <span class="text-[10px] text-gray-600 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @empty<div class="text-center py-10"><p class="text-sm text-gray-600">Henüz aksiyon yok ✨</p></div>@endforelse
            </div>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);"><h3 class="text-sm font-semibold text-white">Yüksek Riskli Kullanıcılar</h3><a href="{{ route('admin.guard.risks') }}" class="text-[11px] text-purple-400 hover:text-purple-300">Tümü →</a></div>
            <div class="divide-y" style="--tw-divide-color: rgba(99,102,241,.06);">
                @forelse($highRiskUsers as $risk)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div><a href="{{ route('admin.users.detail', $risk->user_id) }}" class="text-[13px] text-white hover:text-purple-400 transition-colors font-medium">{{ $risk->user?->name }}</a>
                        <div class="flex gap-2 mt-0.5"><span class="text-[10px] text-gray-600">S:{{ $risk->spam_score }}</span><span class="text-[10px] text-gray-600">U:{{ $risk->compliance_score }}</span><span class="text-[10px] text-gray-600">D:{{ $risk->behavior_score }}</span></div>
                    </div>
                    <div class="text-right"><span class="text-xl font-extrabold {{ $risk->risk_score >= 80 ? 'text-red-400' : ($risk->risk_score >= 60 ? 'text-orange-400' : 'text-amber-400') }}">{{ $risk->risk_score }}</span><p class="text-[9px] text-gray-600">/100</p></div>
                </div>
                @empty<div class="text-center py-10"><p class="text-sm text-gray-600">Yüksek riskli kullanıcı yok</p></div>@endforelse
            </div>
        </div>
    </div>
</div>
