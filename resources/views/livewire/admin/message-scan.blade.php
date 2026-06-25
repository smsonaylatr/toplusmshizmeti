<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Mesaj Tarama Aracı</h1><p class="text-sm text-gray-500 mt-0.5">Bir mesajı AI filtreleme motorunda test edin</p></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-4"><span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139,92,246,.2), rgba(99,102,241,.1));"><svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>Test Mesajı</h3>
            <form wire:submit="scan" class="space-y-4">
                <div><textarea wire:model="testMessage" rows="6" class="admin-input" placeholder="Taramak istediğiniz mesajı buraya yazın..."></textarea>@error('testMessage')<span class="text-red-400 text-[11px]">{{ $message }}</span>@enderror</div>
                <button type="submit" class="btn-primary w-full" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    🤖 AI ile Tara
                </button>
            </form>
        </div>

        @if($scanResult)
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Tarama Sonucu</h3>
            <div class="mb-4 p-4 rounded-xl stat-card {{ $scanResult['status'] === 'block' ? 'border-red-500/30' : ($scanResult['status'] === 'warn' ? 'border-amber-500/30' : 'border-green-500/30') }}" style="background: linear-gradient(135deg, {{ $scanResult['status'] === 'block' ? 'rgba(239,68,68,.08), rgba(239,68,68,.02)' : ($scanResult['status'] === 'warn' ? 'rgba(245,158,11,.08), rgba(245,158,11,.02)' : 'rgba(16,185,129,.08), rgba(16,185,129,.02)') }});">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold {{ $scanResult['status'] === 'block' ? 'text-red-400' : ($scanResult['status'] === 'warn' ? 'text-amber-400' : 'text-green-400') }}">
                        {{ $scanResult['status'] === 'block' ? '🚫 ENGELLENDİ' : ($scanResult['status'] === 'warn' ? '⚠️ UYARI' : '✅ GEÇTİ') }}
                    </span>
                    <span class="text-3xl font-extrabold {{ $scanResult['score'] >= 70 ? 'text-red-400' : ($scanResult['score'] >= 30 ? 'text-amber-400' : 'text-green-400') }}">{{ $scanResult['score'] }}<span class="text-sm text-gray-600">/100</span></span>
                </div>
            </div>
            @if(count($scanResult['flags']) > 0)
            <p class="text-[12px] text-gray-500 font-medium mb-2">Tespit edilen sorunlar:</p>
            <div class="space-y-2">
                @foreach($scanResult['flags'] as $flag)
                <div class="flex items-center gap-2 p-2.5 rounded-xl" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                    <span class="{{ $flag['severity'] === 'high' ? 'status-danger' : ($flag['severity'] === 'medium' ? 'status-warning' : 'status-success') }}">{{ strtoupper($flag['severity']) }}</span>
                    <span class="status-info">{{ $flag['type'] }}</span>
                    <span class="text-[12px] text-gray-400 truncate">{{ $flag['message'] }}</span>
                </div>
                @endforeach
            </div>
            @else<p class="text-sm text-green-400 mt-2">✨ Sorun tespit edilmedi.</p>@endif
            <p class="mt-3 text-[11px] text-gray-600">Toplam kontrol: {{ $scanResult['details']['total_checks'] }} | Eşleşen: {{ $scanResult['details']['matched_flags'] }}</p>
        </div>
        @endif
    </div>
</div>
