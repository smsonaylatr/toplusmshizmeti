<div>
    {{-- ===== BAŞLIK + ÖZET ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight" style="color:var(--admin-text-primary)">Kullanıcı Yönetimi</h1>
            <p class="text-[12px] mt-0.5" style="color:var(--admin-text-secondary)">Tüm müşterileri yönetin ve izleyin</p>
        </div>
        <a href="{{ route('admin.users.detail', 'new') }}" class="btn-primary text-[12px] px-4 py-2 hidden">+ Yeni Kullanıcı</a>
    </div>

    {{-- ===== İSTATİSTİK KARTI SÜTUNU ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
        <div class="glass-card px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(99,102,241,.12);">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:var(--admin-text-secondary)">Toplam</p>
                <p class="text-xl font-black" style="color:var(--admin-text-primary)">{{ $total }}</p>
            </div>
        </div>
        <div class="glass-card px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(16,185,129,.12);">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:var(--admin-text-secondary)">Aktif</p>
                <p class="text-xl font-black text-emerald-500">{{ $active }}</p>
            </div>
        </div>
        <div class="glass-card px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(239,68,68,.1);">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:var(--admin-text-secondary)">Askıda</p>
                <p class="text-xl font-black text-red-500">{{ $suspended }}</p>
            </div>
        </div>
        <div class="glass-card px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(245,158,11,.1);">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:var(--admin-text-secondary)">Evrak Onaylı</p>
                <p class="text-xl font-black text-amber-500">{{ $docOk }}</p>
            </div>
        </div>
    </div>

    {{-- ===== TABLO KARTI ===== --}}
    <div class="glass-card overflow-hidden">

        {{-- FİLTRE BARI --}}
        <div class="p-4 flex flex-col sm:flex-row gap-2 items-stretch sm:items-center" style="border-bottom:1px solid var(--admin-border);">
            {{-- Arama --}}
            <div class="flex-1 relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(99,102,241,.12);">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="İsim, email veya telefon..."
                       class="admin-input"
                       style="padding-left:48px !important;">
            </div>
            {{-- Filtreler --}}
            <select wire:model.live="statusFilter" class="admin-select sm:w-36">
                <option value="">Tüm Durumlar</option>
                <option value="active">✅ Aktif</option>
                <option value="suspended">🚫 Askıda</option>
            </select>
            <select wire:model.live="docFilter" class="admin-select sm:w-36">
                <option value="">Evrak Durumu</option>
                <option value="approved">✅ Onaylı</option>
                <option value="pending">⏳ Onaysız</option>
            </select>
            <select wire:model.live="vatanFilter" class="admin-select sm:w-36">
                <option value="">VatanSMS</option>
                <option value="has_api">🔑 API Girilmiş</option>
                <option value="no_api">➖ API Yok</option>
            </select>
        </div>

        {{-- TABLO --}}
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="text-left cursor-pointer select-none" wire:click="sort('name')">
                            Kullanıcı
                            @if($sortBy==='name') <span class="ml-1 opacity-60">{{ $sortDir==='asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="text-left hidden md:table-cell">İletişim</th>
                        <th class="text-center hidden lg:table-cell cursor-pointer select-none" wire:click="sort('sms_messages_count')">
                            SMS <span class="font-normal opacity-50">/</span> WA
                        </th>
                        <th class="text-center hidden xl:table-cell">Kredi</th>
                        <th class="text-center hidden lg:table-cell">Evrak</th>
                        <th class="text-center hidden lg:table-cell">VatanSMS</th>
                        <th class="text-center">Durum</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        {{-- KULLANICI BİLGİSİ --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="shrink-0 flex items-center justify-center"
                                     style="width:40px;height:40px;min-width:40px;border-radius:50%;
                                            background:{{ $user->is_suspended ? 'linear-gradient(135deg,#dc2626,#ef4444)' : 'linear-gradient(135deg,#6366f1,#8b5cf6)' }}">
                                    <svg class="w-5 h-5" style="color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.detail', $user->id) }}"
                                       class="font-semibold text-[13px] transition-colors hover:text-indigo-500"
                                       style="color:var(--admin-text-primary)">{{ $user->name }}</a>
                                    <p class="text-[11px]" style="color:var(--admin-text-secondary)">
                                        Kayıt: {{ $user->created_at->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- İLETİŞİM --}}
                        <td class="hidden md:table-cell">
                            <p class="text-[13px]" style="color:var(--admin-text-primary)">{{ $user->email }}</p>
                            <p class="text-[11px]" style="color:var(--admin-text-secondary)">{{ $user->phone ?? '—' }}</p>
                        </td>

                        {{-- SMS / WA --}}
                        <td class="text-center hidden lg:table-cell">
                            <span class="text-[13px] font-semibold text-indigo-400">{{ number_format($user->sms_messages_count) }}</span>
                            <span class="text-[11px] mx-1" style="color:var(--admin-text-secondary)">/</span>
                            <span class="text-[13px] font-semibold text-emerald-500">{{ number_format($user->whatsapp_messages_count) }}</span>
                        </td>

                        {{-- KREDİ --}}
                        <td class="text-center hidden xl:table-cell">
                            <div class="flex flex-col items-center gap-0.5">
                                <span class="text-[12px] font-bold" style="color:var(--admin-text-primary)">
                                    {{ number_format($user->sms_credit ?? 0) }}
                                </span>
                                <span class="text-[10px]" style="color:var(--admin-text-secondary)">SMS</span>
                            </div>
                        </td>

                        {{-- EVRAK --}}
                        <td class="text-center hidden lg:table-cell">
                            @if($user->document_approved)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Onaylı
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Bekliyor
                                </span>
                            @endif
                        </td>

                        {{-- VATANSMS --}}
                        <td class="text-center hidden lg:table-cell">
                            @if($user->hasVatanSmsAccount())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    API Var
                                </span>
                            @else
                                <span class="text-[11px]" style="color:var(--admin-text-secondary)">—</span>
                            @endif
                        </td>

                        {{-- DURUM --}}
                        <td class="text-center">
                            @if($user->is_suspended)
                                <span class="status-danger">Askıda</span>
                            @else
                                <span class="status-success">Aktif</span>
                            @endif
                        </td>

                        {{-- İŞLEM --}}
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Detay --}}
                                <a href="{{ route('admin.users.detail', $user->id) }}"
                                   title="Detay"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                                   style="color:var(--admin-text-secondary)"
                                   onmouseover="this.style.color='#3b82f6';this.style.background='rgba(59,130,246,.1)'"
                                   onmouseout="this.style.color='var(--admin-text-secondary)';this.style.background='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                {{-- Askıya Al / Kaldır --}}
                                <button wire:click="toggleSuspend({{ $user->id }})"
                                        title="{{ $user->is_suspended ? 'Askıyı Kaldır' : 'Askıya Al' }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                                        style="color:var(--admin-text-secondary)"
                                        onmouseover="this.style.color='{{ $user->is_suspended ? '#10b981' : '#f59e0b' }}';this.style.background='{{ $user->is_suspended ? 'rgba(16,185,129,.1)' : 'rgba(245,158,11,.1)' }}'"
                                        onmouseout="this.style.color='var(--admin-text-secondary)';this.style.background='transparent'">
                                    @if($user->is_suspended)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="text-center py-14">
                                <div class="w-14 h-14 rounded-2xl mx-auto mb-3 flex items-center justify-center" style="background:var(--admin-inner-bg);">
                                    <svg class="w-7 h-7" style="color:var(--admin-text-secondary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p class="text-[13px] font-medium" style="color:var(--admin-text-secondary)">Kullanıcı bulunamadı</p>
                                <p class="text-[11px] mt-1" style="color:var(--admin-text-muted)">Filtre veya arama kriterlerini değiştirin</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- SAYFALAMA --}}
        <div class="p-4" style="border-top:1px solid var(--admin-border);">
            {{ $users->links() }}
        </div>
    </div>
</div>
