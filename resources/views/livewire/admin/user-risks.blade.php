<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Risk Skorları</h1><p class="text-sm text-gray-500 mt-0.5">Kullanıcı risk değerlendirmeleri</p></div>
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['' => 'Tümü', 'critical' => '🔴 Kritik', 'high' => '🟠 Yüksek', 'medium' => '🟡 Orta', 'low' => '🟢 Düşük'] as $val => $label)
        <button wire:click="$set('riskFilter', '{{ $val }}')" class="px-4 py-2 text-[12px] font-medium rounded-xl transition-all {{ $riskFilter === $val ? 'btn-primary' : 'glass-card text-gray-400 hover:text-white' }}" style="{{ $riskFilter !== $val ? 'padding: 8px 16px;' : '' }}">{{ $label }}</button>
        @endforeach
    </div>
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-center">Risk</th><th class="text-center hidden sm:table-cell">Spam</th><th class="text-center hidden sm:table-cell">Uyum</th><th class="text-center hidden sm:table-cell">Davranış</th><th class="text-center hidden lg:table-cell">Bayrak</th><th class="text-right hidden lg:table-cell">Son</th></tr></thead>
                <tbody>@forelse($risks as $r)
                    <tr><td><a href="{{ route('admin.users.detail', $r->user_id) }}" class="text-white hover:text-indigo-400 font-medium text-[13px] transition-colors">{{ $r->user?->name }}</a></td>
                    <td class="text-center"><span class="text-lg font-extrabold {{ $r->risk_score >= 80 ? 'text-red-400' : ($r->risk_score >= 60 ? 'text-orange-400' : ($r->risk_score >= 30 ? 'text-amber-400' : 'text-green-400')) }}">{{ $r->risk_score }}</span></td>
                    <td class="text-center text-gray-500 text-[13px] hidden sm:table-cell">{{ $r->spam_score }}</td><td class="text-center text-gray-500 text-[13px] hidden sm:table-cell">{{ $r->compliance_score }}</td><td class="text-center text-gray-500 text-[13px] hidden sm:table-cell">{{ $r->behavior_score }}</td><td class="text-center text-gray-500 text-[13px] hidden lg:table-cell">{{ $r->total_flags }}</td><td class="text-right text-[11px] text-gray-600 hidden lg:table-cell">{{ $r->last_flag_at?->diffForHumans() ?? '—' }}</td></tr>
                @empty<tr><td colspan="7"><div class="text-center py-10"><p class="text-sm text-gray-600">Risk kaydı bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $risks->links() }}</div>
    </div>
</div>
