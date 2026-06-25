<div x-data="{ activeTab: 'overview' }">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Dashboard</h1>
            <p class="text-[12px] text-slate-500">Sistem durumu, trafik ve muhasebe özeti</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></span> Canlı
            </span>
            <span class="text-[11px] text-slate-600">{{ now()->translatedFormat('d M Y, H:i') }}</span>
        </div>
    </div>

    {{-- ═══ ANA İSTATİSTİK KARTLARI ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        {{-- Toplam Kullanıcı --}}
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(99,102,241,.1), rgba(99,102,241,.02));">
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Toplam Kullanıcı</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ number_format($stats['totalUsers']) }}</p>
                    <p class="text-[10px] text-emerald-400 mt-0.5">+{{ $stats['newUsersWeek'] }} bu hafta</p>
                </div>
            </div>
        </div>

        {{-- Aktif Kullanıcı --}}
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(16,185,129,.1), rgba(16,185,129,.02));">
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #059669, #10b981);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Aktif Kullanıcı</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ number_format($stats['activeUsers']) }}</p>
                    <p class="text-[10px] text-rose-400 mt-0.5">{{ $stats['suspendedUsers'] }} askıda</p>
                </div>
            </div>
        </div>

        {{-- Bugün Mesaj --}}
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(59,130,246,.1), rgba(59,130,246,.02));">
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Bugün Mesaj</p>
                    <p class="text-2xl font-black text-white tracking-tighter">{{ number_format($traffic['todaySms'] + $traffic['todayWhatsapp']) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $traffic['todaySms'] }} SMS · {{ $traffic['todayWhatsapp'] }} WA</p>
                </div>
            </div>
        </div>

        {{-- Aylık Gelir --}}
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(245,158,11,.1), rgba(245,158,11,.02));">
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Bu Ay Gelir</p>
                    <p class="text-2xl font-black text-white tracking-tighter">₺{{ number_format($accounting['monthRevenue'], 0) }}</p>
                    @if($accounting['lastMonthRevenue'] > 0)
                        @php $change = round((($accounting['monthRevenue'] - $accounting['lastMonthRevenue']) / $accounting['lastMonthRevenue']) * 100); @endphp
                        <p class="text-[10px] {{ $change >= 0 ? 'text-emerald-400' : 'text-rose-400' }} mt-0.5">{{ $change >= 0 ? '+' : '' }}{{ $change }}% geçen aya göre</p>
                    @else
                        <p class="text-[10px] text-slate-500 mt-0.5">Toplam: ₺{{ number_format($accounting['totalRevenue'], 0) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ GÜNLÜK TRAFİK GRAFİĞİ ═══ --}}
    <div class="glass-card p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
            <h3 class="text-[14px] font-bold text-white">📊 Haftalık Trafik</h3>
            <div class="flex items-center gap-4 text-[10px]">
                <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded-full" style="background: linear-gradient(90deg, #6366f1, #818cf8);"></span> <span class="text-slate-500">SMS</span></span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded-full" style="background: linear-gradient(90deg, #10b981, #34d399);"></span> <span class="text-slate-500">WhatsApp</span></span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded-full" style="background: linear-gradient(90deg, #f59e0b, #fbbf24);"></span> <span class="text-slate-500">Gelir (₺)</span></span>
            </div>
        </div>
        {{-- Bar Chart --}}
        <div class="flex items-end gap-2 h-[120px]">
            @foreach($chartData as $day)
            <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end group">
                <div class="w-full flex flex-col items-center gap-0.5 relative">
                    {{-- Tooltip --}}
                    <div class="absolute -top-16 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                        <div class="rounded-xl px-3 py-2 text-center whitespace-nowrap" style="background: var(--admin-card); border: 1px solid var(--admin-border);">
                            <p class="text-[10px] text-slate-400">{{ $day['date'] }}</p>
                            <p class="text-[11px] text-indigo-400 font-semibold">SMS: {{ $day['sms'] }}</p>
                            <p class="text-[11px] text-emerald-400 font-semibold">WA: {{ $day['whatsapp'] }}</p>
                            <p class="text-[11px] text-amber-400 font-semibold">₺{{ number_format($day['revenue'], 0) }}</p>
                        </div>
                    </div>
                    {{-- SMS Bar --}}
                    <div class="w-full max-w-[32px] rounded-t-lg transition-all duration-500 group-hover:opacity-100 opacity-80"
                         style="height: {{ $maxTraffic > 0 ? max(($day['sms'] / $maxTraffic) * 100, 4) : 4 }}px; background: linear-gradient(180deg, #818cf8, #6366f1);"></div>
                    {{-- WA Bar --}}
                    <div class="w-full max-w-[32px] rounded-t-lg transition-all duration-500 group-hover:opacity-100 opacity-80"
                         style="height: {{ $maxTraffic > 0 ? max(($day['whatsapp'] / $maxTraffic) * 100, 4) : 4 }}px; background: linear-gradient(180deg, #34d399, #10b981);"></div>
                </div>
                <p class="text-[10px] text-slate-600 font-medium mt-1">{{ $day['day'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ TRAFİK + MUHASEBE + İLETİM + ONAYLAR ═══ --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px;">
        {{-- 1️⃣ Trafik --}}
        <div class="glass-card p-4">
            <h3 class="text-[13px] font-bold text-white flex items-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(59,130,246,.15), rgba(59,130,246,.05));">📨</span>
                Mesaj Trafiği
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center rounded-xl p-2" style="background: rgba(99,102,241,.04); border: 1px solid rgba(99,102,241,.08);">
                    <div><p class="text-[10px] text-slate-500 font-medium">Bugün</p><p class="text-[14px] font-bold text-white">{{ number_format($traffic['todaySms'] + $traffic['todayWhatsapp']) }}</p></div>
                    <div class="text-right"><p class="text-[10px] text-indigo-400">{{ $traffic['todaySms'] }} SMS</p><p class="text-[10px] text-emerald-400">{{ $traffic['todayWhatsapp'] }} WA</p></div>
                </div>
                <div class="flex justify-between items-center rounded-xl p-2" style="background: rgba(99,102,241,.04); border: 1px solid rgba(99,102,241,.08);">
                    <div><p class="text-[10px] text-slate-500 font-medium">Bu Hafta</p><p class="text-[14px] font-bold text-white">{{ number_format($traffic['weekSms'] + $traffic['weekWhatsapp']) }}</p></div>
                    <div class="text-right"><p class="text-[10px] text-indigo-400">{{ $traffic['weekSms'] }} SMS</p><p class="text-[10px] text-emerald-400">{{ $traffic['weekWhatsapp'] }} WA</p></div>
                </div>
                <div class="flex justify-between items-center rounded-xl p-2" style="background: rgba(99,102,241,.04); border: 1px solid rgba(99,102,241,.08);">
                    <div><p class="text-[10px] text-slate-500 font-medium">Bu Ay</p><p class="text-[14px] font-bold text-white">{{ number_format($traffic['monthSms'] + $traffic['monthWhatsapp']) }}</p></div>
                    <div class="text-right"><p class="text-[10px] text-indigo-400">{{ $traffic['monthSms'] }} SMS</p><p class="text-[10px] text-emerald-400">{{ $traffic['monthWhatsapp'] }} WA</p></div>
                </div>
                <div class="flex justify-between items-center rounded-xl p-2" style="background: rgba(245,158,11,.04); border: 1px solid rgba(245,158,11,.08);">
                    <div><p class="text-[10px] text-slate-500 font-medium">Toplam</p><p class="text-[14px] font-bold text-amber-400">{{ number_format($traffic['totalSms'] + $traffic['totalWhatsapp']) }}</p></div>
                    <div class="text-right"><p class="text-[10px] text-indigo-400">{{ number_format($traffic['totalSms']) }} SMS</p><p class="text-[10px] text-emerald-400">{{ number_format($traffic['totalWhatsapp']) }} WA</p></div>
                </div>
            </div>
        </div>

        {{-- 2️⃣ Muhasebe --}}
        <div class="glass-card p-4">
            <h3 class="text-[13px] font-bold text-white flex items-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(245,158,11,.15), rgba(245,158,11,.05));">💰</span>
                Muhasebe
            </h3>
            <div class="space-y-2">
                <div class="rounded-xl p-2 text-center" style="background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(245,158,11,.02)); border: 1px solid rgba(245,158,11,.12);">
                    <p class="text-[10px] text-slate-500 font-medium">Toplam Gelir</p>
                    <p class="text-lg font-black text-amber-400">₺{{ number_format($accounting['totalRevenue'], 2) }}</p>
                </div>
                <div class="flex gap-2">
                    <div class="flex-1 rounded-xl p-2 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[9px] text-slate-500 font-medium">Bu Ay</p>
                        <p class="text-[13px] font-bold text-white">₺{{ number_format($accounting['monthRevenue'], 0) }}</p>
                    </div>
                    <div class="flex-1 rounded-xl p-2 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[9px] text-slate-500 font-medium">Geçen Ay</p>
                        <p class="text-[13px] font-bold text-slate-400">₺{{ number_format($accounting['lastMonthRevenue'], 0) }}</p>
                    </div>
                </div>
                @if($accounting['pendingPayments'] > 0)
                <div class="rounded-xl p-2 flex items-center justify-between" style="background: rgba(239,68,68,.04); border: 1px solid rgba(239,68,68,.1);">
                    <div><p class="text-[9px] text-rose-400/60 font-medium">Bekleyen</p><p class="text-[12px] font-bold text-rose-400">{{ $accounting['pendingPayments'] }} adet</p></div>
                    <p class="text-[12px] font-bold text-rose-400">₺{{ number_format($accounting['pendingAmount'], 0) }}</p>
                </div>
                @endif
                <div class="flex gap-2">
                    <div class="flex-1 rounded-xl p-2 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[9px] text-slate-500 font-medium">SMS Kredi</p>
                        <p class="text-[13px] font-bold text-indigo-400">{{ number_format($accounting['totalSmsCredits']) }}</p>
                    </div>
                    <div class="flex-1 rounded-xl p-2 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[9px] text-slate-500 font-medium">WA Kredi</p>
                        <p class="text-[13px] font-bold text-emerald-400">{{ number_format($accounting['totalWaCredits']) }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-2" style="border-top: 1px solid rgba(148,163,184,.08);">
                <p class="text-[9px] text-slate-500 mb-1">Son 7 Gün Gelir</p>
                <svg viewBox="0 0 120 24" class="w-full" style="height: 24px;">
                    <defs><linearGradient id="gelirGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#f59e0b" stop-opacity=".3"/><stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/></linearGradient></defs>
                    @php
                        $spark = collect($chartData)->slice(-7)->pluck('revenue')->map(fn($v) => $v ?? 0)->values()->toArray();
                        $sparkMax = max(max($spark), 1); $pts = [];
                        foreach($spark as $i => $v) { $x = ($i / max(count($spark)-1, 1)) * 116 + 2; $y = 22 - ($v / $sparkMax) * 18; $pts[] = "$x,$y"; }
                        $pStr = implode(' ', $pts);
                    @endphp
                    <polygon points="2,22 {{ $pStr }} 118,22" fill="url(#gelirGrad)"/>
                    <polyline points="{{ $pStr }}" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- 3️⃣ İletim + Guard (TEK KART) --}}
        <div class="glass-card p-4">
            <h3 class="text-[13px] font-bold text-white flex items-center gap-2 mb-2">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,.15), rgba(16,185,129,.05));">✅</span>
                İletim Oranı
            </h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="relative w-14 h-14 shrink-0">
                    <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0-31.831" fill="none" stroke="rgba(99,102,241,.1)" stroke-width="3"/>
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0-31.831" fill="none" stroke="{{ $deliveryRate >= 90 ? '#10b981' : ($deliveryRate >= 70 ? '#f59e0b' : '#ef4444') }}" stroke-width="3" stroke-dasharray="{{ $deliveryRate }}, 100" stroke-linecap="round"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-[13px] font-black text-white">%{{ $deliveryRate }}</span>
                </div>
                <div class="flex-1 space-y-1 text-[10px]">
                    <div class="flex justify-between"><span class="text-slate-500">İletildi</span><span class="text-emerald-400 font-semibold">{{ number_format($deliveryStats['delivered']) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Gönderildi</span><span class="text-blue-400 font-semibold">{{ number_format($deliveryStats['sent']) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Başarısız</span><span class="text-rose-400 font-semibold">{{ number_format($deliveryStats['failed']) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Bekliyor</span><span class="text-amber-400 font-semibold">{{ number_format($deliveryStats['pending']) }}</span></div>
                </div>
            </div>
            <div class="pt-3 mb-3" style="border-top: 1px solid rgba(148,163,184,.08);">
                <h3 class="text-[13px] font-bold text-white flex items-center justify-between mb-2">
                    <span class="flex items-center gap-2"><span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139,92,246,.15), rgba(139,92,246,.05));">🤖</span>AI Guard</span>
                    <a href="{{ route('admin.guard') }}" class="text-[10px] text-purple-400 hover:text-purple-300 transition-colors">Detay →</a>
                </h3>
                <div class="flex gap-1.5">
                    <div class="flex-1 rounded-lg p-1.5 text-center" style="background: var(--admin-bg); border: 1px solid {{ $guard['alerts'] > 0 ? 'rgba(239,68,68,.15)' : 'var(--admin-border)' }};">
                        <p class="text-[8px] text-slate-500">Uyarı</p><p class="text-[14px] font-bold {{ $guard['alerts'] > 0 ? 'text-rose-400' : 'text-slate-600' }}">{{ $guard['alerts'] }}</p>
                    </div>
                    <div class="flex-1 rounded-lg p-1.5 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[8px] text-slate-500">Bugün</p><p class="text-[14px] font-bold text-amber-400">{{ $guard['todayFlags'] }}</p>
                    </div>
                    <div class="flex-1 rounded-lg p-1.5 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[8px] text-slate-500">Risk</p><p class="text-[14px] font-bold text-purple-400">{{ $guard['highRisk'] }}</p>
                    </div>
                    <div class="flex-1 rounded-lg p-1.5 text-center" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <p class="text-[8px] text-slate-500">Engel</p><p class="text-[14px] font-bold text-orange-400">{{ $guard['blocked'] }}</p>
                    </div>
                </div>
            </div>
            <div class="pt-2" style="border-top: 1px solid rgba(148,163,184,.08);">
                <p class="text-[9px] text-slate-500 mb-1">7 Gün Mesaj Trendi</p>
                <svg viewBox="0 0 120 24" class="w-full" style="height: 24px;">
                    <defs><linearGradient id="msgGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#6366f1" stop-opacity=".25"/><stop offset="100%" stop-color="#6366f1" stop-opacity="0"/></linearGradient></defs>
                    @php
                        $msgSpark = collect($chartData)->slice(-7)->map(fn($d) => ($d['sms'] ?? 0) + ($d['whatsapp'] ?? 0))->values()->toArray();
                        $msgMax = max(max($msgSpark ?: [0]), 1); $mPts = [];
                        foreach($msgSpark as $i => $v) { $x = ($i / max(count($msgSpark)-1, 1)) * 116 + 2; $y = 22 - ($v / $msgMax) * 18; $mPts[] = "$x,$y"; }
                        $mStr = implode(' ', $mPts);
                    @endphp
                    <polygon points="2,22 {{ $mStr }} 118,22" fill="url(#msgGrad)"/>
                    <polyline points="{{ $mStr }}" fill="none" stroke="#818cf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- 4️⃣ Onaylar + Durum (TEK KART) --}}
        <div class="glass-card p-4">
            <h3 class="text-[13px] font-bold text-white flex items-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(245,158,11,.15), rgba(245,158,11,.05));">⚡</span>
                Bekleyen Onaylar
            </h3>
            <div class="space-y-2 mb-3">
                <a href="{{ route('admin.approvals.senders') }}" class="flex items-center justify-between rounded-xl p-2 hover:bg-white/[.02] transition-colors" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                    <div class="flex items-center gap-2"><span class="text-sm">✉️</span><span class="text-[11px] text-slate-400">Gönderici</span></div>
                    <span class="text-[14px] font-bold {{ $pending['senders'] > 0 ? 'text-amber-400' : 'text-slate-700' }}">{{ $pending['senders'] }}</span>
                </a>
                <a href="{{ route('admin.approvals.documents') }}" class="flex items-center justify-between rounded-xl p-2 hover:bg-white/[.02] transition-colors" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                    <div class="flex items-center gap-2"><span class="text-sm">📄</span><span class="text-[11px] text-slate-400">Evrak</span></div>
                    <span class="text-[14px] font-bold {{ $pending['documents'] > 0 ? 'text-blue-400' : 'text-slate-700' }}">{{ $pending['documents'] }}</span>
                </a>
                <a href="{{ route('admin.approvals.payments') }}" class="flex items-center justify-between rounded-xl p-2 hover:bg-white/[.02] transition-colors" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                    <div class="flex items-center gap-2"><span class="text-sm">💳</span><span class="text-[11px] text-slate-400">Ödeme</span></div>
                    <span class="text-[14px] font-bold {{ $pending['payments'] > 0 ? 'text-rose-400' : 'text-slate-700' }}">{{ $pending['payments'] }}</span>
                </a>
            </div>
            <div class="pt-3 mb-3" style="border-top: 1px solid rgba(148,163,184,.08);">
                @php $totalAll = max($traffic['totalSms'] + $traffic['totalWhatsapp'], 1); $smsR = round(($traffic['totalSms'] / $totalAll) * 100); $waR = 100 - $smsR; @endphp
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10 shrink-0">
                        <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                            <circle cx="18" cy="18" r="14" fill="none" stroke="rgba(16,185,129,.15)" stroke-width="4"/>
                            <circle cx="18" cy="18" r="14" fill="none" stroke="#6366f1" stroke-width="4" stroke-dasharray="{{ $smsR * 0.88 }}, 100" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full" style="background:#6366f1;"></span><span class="text-[9px] text-slate-400">SMS</span><span class="text-[10px] font-bold text-indigo-400 ml-auto">%{{ $smsR }}</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full" style="background:#10b981;"></span><span class="text-[9px] text-slate-400">WA</span><span class="text-[10px] font-bold text-emerald-400 ml-auto">%{{ $waR }}</span></div>
                    </div>
                </div>
            </div>
            <div class="pt-3 space-y-1" style="border-top: 1px solid rgba(148,163,184,.08);">
                <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span><span class="text-[10px] text-slate-400">SMS API</span></div><span class="text-[9px] font-semibold text-emerald-400">Aktif</span></div>
                <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span><span class="text-[10px] text-slate-400">WA API</span></div><span class="text-[9px] font-semibold text-emerald-400">Aktif</span></div>
                <div class="flex items-center justify-between"><div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span><span class="text-[10px] text-slate-400">Guard</span></div><span class="text-[9px] font-semibold text-emerald-400">Aktif</span></div>
            </div>
        </div>
    </div>
    {{-- ═══ SON İŞLEMLER ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        {{-- Son Kullanıcılar --}}
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);">
                <h3 class="text-[13px] font-bold text-white">👥 Son Kullanıcılar</h3>
                <a href="{{ route('admin.users') }}" class="text-[10px] text-indigo-400 hover:text-indigo-300 transition-colors font-medium">Tümü →</a>
            </div>
            <div class="divide-y" style="--tw-divide-opacity: 1; --tw-divide-color: rgba(99,102,241,.06);">
                @forelse($recentUsers as $user)
                <a href="{{ route('admin.users.detail', $user->id) }}" class="px-5 py-3 flex items-center justify-between hover:bg-white/[.02] transition-colors block">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-[12px] font-bold shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">{{ mb_substr($user->name, 0, 1) }}</div>
                        <div><p class="text-[13px] text-white font-medium">{{ $user->name }}</p><p class="text-[10px] text-slate-600">{{ $user->email }}</p></div>
                    </div>
                    <span class="text-[9px] text-slate-600 shrink-0">{{ $user->created_at->diffForHumans() }}</span>
                </a>
                @empty
                <div class="text-center py-8"><p class="text-[12px] text-slate-600">Henüz kullanıcı yok</p></div>
                @endforelse
            </div>
        </div>

        {{-- Son Ödemeler --}}
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);">
                <h3 class="text-[13px] font-bold text-white">💰 Son Ödemeler</h3>
                <a href="{{ route('admin.approvals.payments') }}" class="text-[10px] text-amber-400 hover:text-amber-300 transition-colors font-medium">Tümü →</a>
            </div>
            <div class="divide-y" style="--tw-divide-opacity: 1; --tw-divide-color: rgba(99,102,241,.06);">
                @forelse($recentPayments as $pay)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div><p class="text-[13px] text-white font-medium">{{ $pay->user?->name ?? '—' }}</p><p class="text-[10px] text-slate-600">{{ $pay->bank ?? '—' }}</p></div>
                    <span class="text-[14px] font-bold text-amber-400">₺{{ number_format($pay->amount, 0) }}</span>
                </div>
                @empty
                <div class="text-center py-8"><p class="text-[12px] text-slate-600">Henüz ödeme yok</p></div>
                @endforelse
            </div>
        </div>

        {{-- Son Guard Aksiyonları --}}
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom: 1px solid var(--admin-border);">
                <h3 class="text-[13px] font-bold text-white">🤖 Guard Aksiyonları</h3>
                <a href="{{ route('admin.guard.logs') }}" class="text-[10px] text-purple-400 hover:text-purple-300 transition-colors font-medium">Tümü →</a>
            </div>
            <div class="divide-y" style="--tw-divide-opacity: 1; --tw-divide-color: rgba(99,102,241,.06);">
                @forelse($recentLogs as $log)
                <div class="px-5 py-3 flex items-center gap-3">
                    <span class="text-[9px] px-2 py-0.5 rounded-lg font-bold shrink-0
                        {{ $log->severity === 'critical' ? 'status-danger' : '' }}
                        {{ $log->severity === 'high' ? 'status-warning' : '' }}
                        {{ $log->severity === 'medium' ? 'status-info' : '' }}
                        {{ $log->severity === 'low' ? 'status-success' : '' }}">{{ strtoupper($log->severity) }}</span>
                    <div class="flex-1 min-w-0"><p class="text-[12px] text-white truncate">{{ $log->user?->name ?? 'Sistem' }}</p><p class="text-[10px] text-slate-600 truncate">{{ $log->reason }}</p></div>
                    <span class="text-[9px] text-slate-600 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-[12px] text-slate-600">Henüz aksiyon yok</p>
                    <p class="text-[10px] text-slate-700 mt-0.5">Sistem güvenli ✨</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
