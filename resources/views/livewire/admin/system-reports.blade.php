<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Sistem Raporları</h1><p class="text-sm text-gray-500 mt-0.5">Mesaj, kullanıcı ve gelir istatistikleri</p></div>
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['7' => 'Son 7 Gün', '30' => 'Son 30 Gün', '90' => 'Son 90 Gün'] as $val => $label)
        <button wire:click="$set('period', '{{ $val }}')" class="px-4 py-2 text-[12px] font-medium rounded-xl transition-all {{ $period === $val ? 'btn-primary' : 'glass-card text-gray-400 hover:text-white' }}" style="{{ $period !== $val ? 'padding: 8px 16px;' : '' }}">{{ $label }}</button>
        @endforeach
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(16,185,129,.08), rgba(16,185,129,.02));">
            <p class="text-[11px] text-gray-500 font-medium">SMS Gönderimi</p><p class="text-2xl font-extrabold text-emerald-400 mt-1">{{ number_format($smsStats) }}</p>
        </div>
        <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(34,197,94,.08), rgba(34,197,94,.02));">
            <p class="text-[11px] text-gray-500 font-medium">WhatsApp</p><p class="text-2xl font-extrabold text-green-400 mt-1">{{ number_format($whatsappStats) }}</p>
        </div>
        <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(245,158,11,.02));">
            <p class="text-[11px] text-gray-500 font-medium">Gelir</p><p class="text-2xl font-extrabold text-amber-400 mt-1">₺{{ number_format($revenue, 2) }}</p>
        </div>
        <div class="stat-card text-center" style="background: linear-gradient(135deg, rgba(59,130,246,.08), rgba(59,130,246,.02));">
            <p class="text-[11px] text-gray-500 font-medium">Yeni Kullanıcı</p><p class="text-2xl font-extrabold text-blue-400 mt-1">{{ $newUsers }}</p>
        </div>
    </div>
    <div class="glass-card overflow-hidden">
        <div class="px-5 py-3.5" style="border-bottom: 1px solid var(--admin-border);"><h3 class="text-sm font-semibold text-white">🏆 En Aktif Kullanıcılar</h3></div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-center">SMS</th><th class="text-center">WA</th><th class="text-center">Toplam</th></tr></thead>
                <tbody>@foreach($topUsers as $u)
                    <tr><td><a href="{{ route('admin.users.detail', $u->id) }}" class="text-white hover:text-indigo-400 font-medium text-[13px] transition-colors">{{ $u->name }}</a></td><td class="text-center text-gray-400 text-[13px]">{{ $u->sms_messages_count }}</td><td class="text-center text-gray-400 text-[13px]">{{ $u->whatsapp_messages_count }}</td><td class="text-center text-white font-semibold text-[13px]">{{ $u->sms_messages_count + $u->whatsapp_messages_count }}</td></tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
</div>
