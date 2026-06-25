<div x-data="{ tab: 'sms' }">
    <a href="{{ route('admin.users') }}" class="text-gray-500 hover:text-indigo-400 text-[13px] mb-4 inline-flex items-center gap-1.5 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kullanıcılara Dön
    </a>

    {{-- Başlık --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">{{ mb_substr($user->name, 0, 1) }}</div>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[13px] text-gray-500">{{ $user->email }}</span>
                @if($user->is_suspended)
                    <span class="status-danger">Askıda</span>
                @else
                    <span class="status-success">Aktif</span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif

    {{-- Üst Kartlar --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        {{-- Profil --}}
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(99,102,241,.1)"><svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                Profil
            </h3>

            {{-- Hesap Türü --}}
            <div class="mb-3 flex items-center gap-1.5 flex-wrap">
                @if(($user->account_type ?? 'individual') === 'corporate')
                    <span class="px-2 py-0.5 bg-purple-900/40 text-purple-300 text-[10px] font-bold rounded-full">🏢 Kurumsal</span>
                @else
                    <span class="px-2 py-0.5 bg-blue-900/40 text-blue-300 text-[10px] font-bold rounded-full">👤 Bireysel</span>
                @endif
                @if($user->customer_code)
                    <span class="px-2 py-0.5 bg-gray-800 text-gray-400 text-[10px] font-mono rounded-full">{{ $user->customer_code }}</span>
                @endif
            </div>

            <div class="space-y-1.5 text-[12px]">
                {{-- İletişim --}}
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">İletişim</p>
                <div class="flex justify-between"><span class="text-gray-500">Ad Soyad</span><span class="text-gray-200 font-medium">{{ $user->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">E-posta</span><span class="text-gray-300 truncate max-w-[140px] text-right">{{ $user->email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Telefon</span><span class="text-gray-300">{{ $user->phone ?? '—' }}</span></div>
                @if($user->contact_person)
                <div class="flex justify-between"><span class="text-gray-500">Yetkili Kişi</span><span class="text-gray-300">{{ $user->contact_person }}</span></div>
                @endif

                {{-- Kimlik / Vergi --}}
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-wider pt-1.5">Kimlik / Vergi</p>
                @if($user->company_name)
                <div class="flex justify-between"><span class="text-gray-500">Şirket Adı</span><span class="text-gray-200 font-semibold">{{ $user->company_name }}</span></div>
                @else
                <div class="flex justify-between"><span class="text-gray-500">Şirket Adı</span><span class="text-gray-600">—</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-500">TC Kimlik No</span>
                    <span class="text-gray-300 font-mono">{{ $user->tc_no ? substr($user->tc_no,0,3).'****'.substr($user->tc_no,-4) : '—' }}</span>
                </div>
                <div class="flex justify-between"><span class="text-gray-500">Vergi No</span><span class="text-gray-300 font-mono">{{ $user->tax_no ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Vergi Dairesi</span><span class="text-gray-300">{{ $user->tax_office ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">MERSİS No</span><span class="text-gray-300 font-mono text-[11px]">{{ $user->mersis_no ?? '—' }}</span></div>

                {{-- Adres --}}
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-wider pt-1.5">Adres</p>
                <div class="flex justify-between"><span class="text-gray-500">Şehir / İlçe</span><span class="text-gray-300">{{ ($user->city ?? '—') . ($user->district ? ' / '.$user->district : '') }}</span></div>
                <div class="flex justify-between items-start gap-2"><span class="text-gray-500 shrink-0">Açık Adres</span><span class="text-gray-400 text-right text-[11px] break-words max-w-[150px]">{{ $user->address ?? '—' }}</span></div>

                {{-- Sistem --}}
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-wider pt-1.5">Sistem</p>
                <div class="flex justify-between"><span class="text-gray-500">Kayıt Tarihi</span><span class="text-gray-300">{{ $user->created_at->format('d.m.Y H:i') }}</span></div>
                @if($riskScore)
                <div class="flex justify-between"><span class="text-gray-500">Risk Skoru</span>
                    <span class="font-bold {{ $riskScore->risk_score >= 60 ? 'text-red-400' : ($riskScore->risk_score >= 30 ? 'text-amber-400' : 'text-green-400') }}">{{ $riskScore->risk_score }}/100</span>
                </div>
                @endif
            </div>

            <button wire:click="toggleSuspend"
                    class="mt-4 w-full py-2 text-[13px] font-semibold rounded-xl transition-all {{ $user->is_suspended ? 'btn-success' : 'btn-danger' }}"
                    style="padding:8px;">
                {{ $user->is_suspended ? '✅ Askıyı Kaldır' : '🚫 Askıya Al' }}
            </button>
        </div>

        {{-- Kredi Yönetimi --}}
        <div class="glass-card p-5" x-data="{ mode: 'add' }">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-4">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(245,158,11,.1)"><svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg></span>
                Kredi
            </h3>

            {{-- Mevcut Bakiye --}}
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">SMS</p>
                    <p class="text-xl font-bold text-emerald-400">{{ number_format($user->sms_credits ?? 0) }}</p>
                </div>
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">WA</p>
                    <p class="text-xl font-bold text-green-400">{{ number_format($user->whatsapp_credits ?? 0) }}</p>
                </div>
            </div>

            {{-- Ekle / Çıkar Tab --}}
            <div class="flex rounded-lg overflow-hidden mb-3" style="border:1px solid var(--admin-border)">
                <button x-on:click="mode='add'" type="button"
                        :class="mode==='add' ? 'bg-emerald-500/20 text-emerald-400 font-bold' : 'text-gray-500 hover:text-gray-300'"
                        class="flex-1 py-1.5 text-xs transition-colors">
                    + Ekle
                </button>
                <button x-on:click="mode='deduct'" type="button"
                        :class="mode==='deduct' ? 'bg-red-500/20 text-red-400 font-bold' : 'text-gray-500 hover:text-gray-300'"
                        class="flex-1 py-1.5 text-xs transition-colors">
                    − Çıkar
                </button>
            </div>

            {{-- Ekle Formu --}}
            <div x-show="mode==='add'" class="space-y-2">
                <input wire:model="addSmsCredits" type="number" min="0" placeholder="SMS Kredi Miktarı" class="admin-input">
                <input wire:model="addWhatsappCredits" type="number" min="0" placeholder="WA Kredi Miktarı" class="admin-input">
                <button wire:click="addCredits" class="btn-primary w-full text-sm">Kredi Ekle</button>
            </div>

            {{-- Çıkar Formu --}}
            <div x-show="mode==='deduct'" style="display:none" class="space-y-2">
                <input wire:model="deductSmsCredits" type="number" min="0" placeholder="SMS Kredi Miktarı" class="admin-input">
                <input wire:model="deductWhatsappCredits" type="number" min="0" placeholder="WA Kredi Miktarı" class="admin-input">
                <button wire:click="deductCredits"
                        class="w-full py-2 rounded-lg bg-red-500/80 hover:bg-red-600 text-white text-sm font-bold transition-colors">
                    Kredi Çıkar
                </button>
            </div>
        </div>

        {{-- SMS Kısa Kod --}}
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-4">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(99,102,241,.1)"><svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg></span>
                SMS Kısa Kod
            </h3>
            @if($user->sms_short_code)
                <div class="mb-3 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-indigo-900/50 text-indigo-300 text-xs font-bold rounded">{{ $user->sms_short_code }}</span>
                    @if($user->sms_cancel_number)<span class="text-[11px] text-gray-500 truncate">{{ $user->sms_cancel_number }}</span>@endif
                </div>
            @endif
            <div class="space-y-2">
                <input wire:model="smsShortCode" type="text" maxlength="20" placeholder="Kısa Kod (ör: SAR)" class="admin-input" style="text-transform:uppercase">
                <input wire:model="smsCancelNumber" type="text" maxlength="200" placeholder="İptal Linki" class="admin-input">
                <button wire:click="saveSmsCodes" class="btn-primary w-full text-sm">Kaydet</button>
                @error('smsShortCode')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- İstatistikler --}}
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-4">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(6,182,212,.1)"><svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6M3 21h18"/></svg></span>
                İstatistikler
            </h3>
            <div class="space-y-2.5">
                @php
                    $stats = [
                        ['SMS', $user->sms_messages_count, 'text-emerald-400'],
                        ['WhatsApp', $user->whatsapp_messages_count, 'text-green-400'],
                        ['Kişiler', $user->contacts_count, 'text-blue-400'],
                        ['Gruplar', $user->contact_groups_count, 'text-sky-400'],
                        ['Şablonlar', $user->sms_templates_count, 'text-violet-400'],
                        ['Gönderici', $user->sender_names_count, 'text-amber-400'],
                        ['Kara Liste', $user->blacklisted_numbers_count, 'text-red-400'],
                        ['Belgeler', $user->documents_count, 'text-pink-400'],
                        ['IP Log', $user->login_logs_count, 'text-cyan-400'],
                        ['Kredi Hareketleri', $user->credit_logs_count, 'text-lime-400'],
                        ['Ödemeler', $user->payment_notifications_count, 'text-yellow-400'],
                        ['Guard Log', $user->guard_logs_count, 'text-purple-400'],
                    ];
                @endphp
                @foreach($stats as [$label, $value, $color])
                <div class="flex justify-between items-center text-[12px]">
                    <span class="text-gray-500">{{ $label }}</span>
                    <span class="font-semibold {{ $color }}">{{ number_format($value) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab Navigasyon --}}
    <div class="glass-card overflow-hidden">
        <div class="flex border-b overflow-x-auto" style="border-color:var(--admin-border)">
            @php
                $tabs = [
                    'sms'        => ['label' => 'SMS Mesajları', 'count' => $user->sms_messages_count],
                    'senders'    => ['label' => 'Başlıklar', 'count' => $user->sender_names_count],
                    'templates'  => ['label' => 'Şablonlar', 'count' => $user->sms_templates_count],
                    'groups'     => ['label' => 'Gruplar', 'count' => $user->contact_groups_count],
                    'contacts'   => ['label' => 'Kişiler', 'count' => $user->contacts_count],
                    'blacklist'  => ['label' => 'Kara Liste', 'count' => $user->blacklisted_numbers_count],
                    'documents'  => ['label' => 'Belgeler', 'count' => $user->documents_count],
                    'whatsapp'   => ['label' => 'WhatsApp', 'count' => $user->whatsapp_messages_count],
                    'iplogin'    => ['label' => 'IP Logları', 'count' => $user->login_logs_count],
                    'creditlog'  => ['label' => 'Bakiye Hareketleri', 'count' => $user->credit_logs_count],
                    'payments'   => ['label' => 'Ödeme Bildirimleri', 'count' => $user->payment_notifications_count],
                    'guardlogs'  => ['label' => 'Guard', 'count' => $user->guard_logs_count],
                ];
            @endphp
            @foreach($tabs as $key => $tab)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-indigo-400/5' : 'text-gray-500 hover:text-gray-300'"
                    class="px-5 py-3.5 text-[12px] font-medium whitespace-nowrap transition-colors flex items-center gap-1.5 shrink-0">
                {{ $tab['label'] }}
                @if($tab['count'] > 0)
                    <span class="inline-flex items-center justify-center w-4 h-4 text-[10px] rounded-full bg-indigo-500/20 text-indigo-400 font-bold">{{ $tab['count'] > 99 ? '99+' : $tab['count'] }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- ===================== SMS MESAJLARI ===================== --}}
        <div x-show="tab === 'sms'" class="p-4">
            @if($smsMessages->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Henüz SMS gönderilmemiş.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Alıcı</th>
                            <th class="pb-2 pr-4">Gönderici</th>
                            <th class="pb-2 pr-4">Mesaj</th>
                            <th class="pb-2 pr-4">Durum</th>
                            <th class="pb-2">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($smsMessages as $sms)
                        <tr class="hover:bg-white/2 transition-colors">
                            <td class="py-2 pr-4 text-gray-300 font-mono">{{ $sms->recipient ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400">{{ $sms->sender_name ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400 max-w-xs truncate">{{ $sms->message ?? '—' }}</td>
                            <td class="py-2 pr-4">
                                @if(($sms->status ?? '') === 'sent')
                                    <span class="status-success">Gönderildi</span>
                                @elseif(($sms->status ?? '') === 'failed')
                                    <span class="status-danger">Başarısız</span>
                                @else
                                    <span class="status-warning">{{ $sms->status ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="py-2 text-gray-500">{{ $sms->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ===================== GÖNDERİCİ ADLARI ===================== --}}
        <div x-show="tab === 'senders'" class="p-4"
             x-data="{ deletingId: null }">

            {{-- SİL ONAY MODALI --}}
            <div x-show="deletingId !== null"
                 x-transition.opacity
                 style="display:none"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="bg-[#1a1d2e] border border-red-500/30 rounded-2xl p-6 w-80 shadow-2xl text-center" @click.stop>
                    <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base mb-1">Gönderici Adını Sil</h3>
                    <p class="text-gray-400 text-sm mb-5">Bu gönderici adını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                    <div class="flex gap-3">
                        <button @click="deletingId = null"
                                class="flex-1 py-2 rounded-lg border border-gray-600 text-gray-300 text-sm hover:bg-white/5 transition-colors">
                            İptal
                        </button>
                        <button @click="$wire.deleteSender(deletingId); deletingId = null"
                                class="flex-1 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-bold transition-colors">
                            Evet, Sil
                        </button>
                    </div>
                </div>
            </div>

            @if($senderNames->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Gönderici adı eklenmemiş.</p>
            @else
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                        <th class="pb-2 pr-4">Ad</th>
                        <th class="pb-2 pr-4">Durum</th>
                        <th class="pb-2 pr-4">Varsayılan</th>
                        <th class="pb-2 pr-4">Tarih</th>
                        <th class="pb-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:var(--admin-border)">
                    @foreach($senderNames as $s)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="py-2 pr-4 text-white font-semibold">{{ $s->name }}</td>
                        <td class="py-2 pr-4">
                            @if($s->status === 'approved')
                                <span class="status-success">Onaylı</span>
                            @elseif($s->status === 'rejected')
                                <span class="status-danger">Reddedildi</span>
                            @else
                                <span class="status-warning">Bekliyor</span>
                            @endif
                        </td>
                        <td class="py-2 pr-4">
                            @if($s->is_default ?? false)
                                <span class="px-2 py-0.5 bg-indigo-900/40 text-indigo-300 text-[10px] font-bold rounded">VARSAYILAN</span>
                            @else
                                <span class="text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="py-2 text-gray-500">{{ $s->created_at->format('d.m.Y') }}</td>
                        <td class="py-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @unless($s->is_default ?? false)
                                <button
                                    wire:click="setSenderDefault({{ $s->id }})"
                                    wire:loading.attr="disabled"
                                    title="Varsayılan yap"
                                    class="p-1.5 rounded hover:bg-yellow-500/20 text-gray-500 hover:text-yellow-400 transition-colors disabled:opacity-40">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                </button>
                                @endunless
                                <button
                                    @click="deletingId = {{ $s->id }}"
                                    title="Sil"
                                    class="p-1.5 rounded hover:bg-red-500/20 text-gray-500 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ===================== ŞABLONLAR ===================== --}}
        <div x-show="tab === 'templates'" class="p-4">
            @if($templates->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Şablon oluşturulmamış.</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($templates as $tpl)
                <div class="rounded-xl p-4" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[13px] font-semibold text-white">{{ $tpl->name }}</span>
                        <span class="text-[10px] text-gray-500">{{ $tpl->created_at->format('d.m.Y') }}</span>
                    </div>
                    <p class="text-[12px] text-gray-400 leading-relaxed">{{ $tpl->content }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ===================== GRUPLAR ===================== --}}
        <div x-show="tab === 'groups'" class="p-4">
            @if($contactGroups->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Grup oluşturulmamış.</p>
            @else
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                        <th class="pb-2 pr-4">Grup Adı</th>
                        <th class="pb-2 pr-4">Kişi Sayısı</th>
                        <th class="pb-2">Oluşturulma</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:var(--admin-border)">
                    @foreach($contactGroups as $g)
                    <tr>
                        <td class="py-2 pr-4 text-white font-semibold">{{ $g->name }}</td>
                        <td class="py-2 pr-4 text-blue-400 font-semibold">{{ number_format($g->contacts_count) }}</td>
                        <td class="py-2 text-gray-500">{{ $g->created_at->format('d.m.Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ===================== KİŞİLER ===================== --}}
        <div x-show="tab === 'contacts'" class="p-4">
            @if($contacts->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Kişi eklenmemiş.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Ad Soyad</th>
                            <th class="pb-2 pr-4">Telefon</th>
                            <th class="pb-2 pr-4">Grup</th>
                            <th class="pb-2">Eklenme</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($contacts as $c)
                        <tr>
                            <td class="py-2 pr-4 text-gray-300">{{ $c->name ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400 font-mono">{{ $c->phone ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-500">{{ $c->group->name ?? '—' }}</td>
                            <td class="py-2 text-gray-500">{{ $c->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($user->contacts_count > 50)
                    <p class="text-center text-gray-600 text-[11px] mt-3">İlk 50 kişi gösteriliyor. Toplam: {{ number_format($user->contacts_count) }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- ===================== KARA LİSTE ===================== --}}
        <div x-show="tab === 'blacklist'" class="p-4">
            @if($blacklisted->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Kara listede numara yok.</p>
            @else
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                        <th class="pb-2 pr-4">Telefon Numarası</th>
                        <th class="pb-2 pr-4">Açıklama</th>
                        <th class="pb-2">Eklenme</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:var(--admin-border)">
                    @foreach($blacklisted as $b)
                    <tr>
                        <td class="py-2 pr-4 text-red-400 font-mono font-semibold">{{ $b->phone_number ?? '—' }}</td>
                        <td class="py-2 pr-4 text-gray-500">{{ $b->reason ?? '—' }}</td>
                        <td class="py-2 text-gray-500">{{ $b->created_at->format('d.m.Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ===================== BELGELER ===================== --}}
        <div x-show="tab === 'documents'" class="p-4">
            @if($documents->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Belge yüklenmemiş.</p>
            @else
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                        <th class="pb-2 pr-4">Ad</th>
                        <th class="pb-2 pr-4">Tür</th>
                        <th class="pb-2 pr-4">Durum</th>
                        <th class="pb-2">Yükleme</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:var(--admin-border)">
                    @foreach($documents as $doc)
                    <tr>
                        <td class="py-2 pr-4 text-gray-300">{{ $doc->name ?? $doc->original_name ?? '—' }}</td>
                        <td class="py-2 pr-4 text-gray-500">{{ $doc->type ?? '—' }}</td>
                        <td class="py-2 pr-4">
                            @if(($doc->status ?? '') === 'approved')
                                <span class="status-success">Onaylı</span>
                            @elseif(($doc->status ?? '') === 'rejected')
                                <span class="status-danger">Reddedildi</span>
                            @else
                                <span class="status-warning">Bekliyor</span>
                            @endif
                        </td>
                        <td class="py-2 text-gray-500">{{ $doc->created_at->format('d.m.Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ===================== WHATSAPP ===================== --}}
        <div x-show="tab === 'whatsapp'" class="p-4">
            @if($whatsappMessages->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">WhatsApp mesajı yok.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Alıcı</th>
                            <th class="pb-2 pr-4">Mesaj</th>
                            <th class="pb-2 pr-4">Durum</th>
                            <th class="pb-2">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($whatsappMessages as $wa)
                        <tr>
                            <td class="py-2 pr-4 text-gray-300 font-mono">{{ $wa->recipient ?? $wa->to ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400 max-w-xs truncate">{{ $wa->message ?? $wa->body ?? '—' }}</td>
                            <td class="py-2 pr-4">
                                <span class="{{ ($wa->status ?? '') === 'sent' ? 'status-success' : 'status-warning' }}">{{ $wa->status ?? '—' }}</span>
                            </td>
                            <td class="py-2 text-gray-500">{{ $wa->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ===================== IP / GİRİŞ LOGLARI ===================== --}}
        <div x-show="tab === 'iplogin'" class="p-4">
            @if($loginLogs->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Henüz giriş logu yok. Giriş yapıldığında otomatik kaydedilecek.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Durum</th>
                            <th class="pb-2 pr-4">IP Adresi</th>
                            <th class="pb-2 pr-4">Ülke / Şehir</th>
                            <th class="pb-2 pr-4">Tarayıcı</th>
                            <th class="pb-2">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($loginLogs as $log)
                        <tr>
                            <td class="py-2 pr-4">
                                @if($log->status === 'success')
                                    <span class="status-success">✓ Başarılı</span>
                                @else
                                    <span class="status-danger">✗ Başarısız</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-cyan-400 font-mono font-semibold">{{ $log->ip_address ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400">{{ implode(' / ', array_filter([$log->country, $log->city])) ?: '—' }}</td>
                            <td class="py-2 pr-4 text-gray-500 max-w-[180px] truncate" title="{{ $log->user_agent ?? '' }}">{{ Str::limit($log->user_agent ?? '—', 40) }}</td>
                            <td class="py-2 text-gray-500">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ===================== KREDİ HAREKETLERİ ===================== --}}
        <div x-show="tab === 'creditlog'" class="p-4">
            @if($creditLogs->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Kredi hareketi yok.</p>
            @else
            {{-- Özet --}}
            @php
                $smsTotalAdded  = $creditLogs->where('type','sms')->where('action','add')->sum('amount');
                $smsTotalUsed   = $creditLogs->where('type','sms')->whereIn('action',['use','deduct'])->sum('amount');
                $waTotalAdded   = $creditLogs->where('type','whatsapp')->where('action','add')->sum('amount');
                $waTotalUsed    = $creditLogs->where('type','whatsapp')->whereIn('action',['use','deduct'])->sum('amount');
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">SMS Toplam Eklenen</p>
                    <p class="text-lg font-bold text-emerald-400">+{{ number_format($smsTotalAdded) }}</p>
                </div>
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">SMS Toplam Kullanılan</p>
                    <p class="text-lg font-bold text-red-400">{{ number_format($smsTotalUsed) }}</p>
                </div>
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">WA Toplam Eklenen</p>
                    <p class="text-lg font-bold text-green-400">+{{ number_format($waTotalAdded) }}</p>
                </div>
                <div class="rounded-xl p-3 text-center" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <p class="text-[10px] text-gray-500">WA Toplam Kullanılan</p>
                    <p class="text-lg font-bold text-orange-400">{{ number_format($waTotalUsed) }}</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Tür</th>
                            <th class="pb-2 pr-4">İşlem</th>
                            <th class="pb-2 pr-4">Miktar</th>
                            <th class="pb-2 pr-4">Sonraki Bakiye</th>
                            <th class="pb-2 pr-4">Açıklama</th>
                            <th class="pb-2">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($creditLogs as $cl)
                        <tr>
                            <td class="py-2 pr-4">
                                <span class="{{ $cl->type === 'sms' ? 'text-emerald-400' : 'text-green-400' }} font-semibold uppercase text-[11px]">{{ $cl->type }}</span>
                            </td>
                            <td class="py-2 pr-4">
                                @if($cl->action === 'add')
                                    <span class="status-success">+ Eklendi</span>
                                @elseif($cl->action === 'use')
                                    <span class="status-danger">− Kullanıldı</span>
                                @elseif($cl->action === 'refund')
                                    <span class="status-warning">↩ İade</span>
                                @else
                                    <span class="status-danger">↓ Düşüldü</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 font-bold font-mono {{ in_array($cl->action, ['add','refund']) ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ in_array($cl->action, ['add','refund']) ? '+' : '-' }}{{ number_format(abs($cl->amount)) }}
                            </td>
                            <td class="py-2 pr-4 text-gray-300 font-mono">{{ $cl->balance_after !== null ? number_format($cl->balance_after) : '—' }}</td>
                            <td class="py-2 pr-4 text-gray-500">{{ $cl->description ?? '—' }}</td>
                            <td class="py-2 text-gray-500">{{ $cl->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ===================== ÖDEME BİLDİRİMLERİ ===================== --}}
        <div x-show="tab === 'payments'" class="p-4">
            @if($paymentNotifications->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Ödeme bildirimi yok.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Gönderici</th>
                            <th class="pb-2 pr-4">Banka</th>
                            <th class="pb-2 pr-4">Tutar</th>
                            <th class="pb-2 pr-4">Durum</th>
                            <th class="pb-2 pr-4">Ödeme Tarihi</th>
                            <th class="pb-2">Bildirim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($paymentNotifications as $p)
                        <tr>
                            <td class="py-2 pr-4 text-gray-300">{{ $p->sender_name ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-400">{{ $p->bank ?? '—' }}</td>
                            <td class="py-2 pr-4 font-bold text-yellow-400 font-mono">{{ $p->amount ? number_format($p->amount, 2).' ₺' : '—' }}</td>
                            <td class="py-2 pr-4">
                                @if($p->status === 'approved')
                                    <span class="status-success">Onaylı</span>
                                @elseif($p->status === 'rejected')
                                    <span class="status-danger">Reddedildi</span>
                                @else
                                    <span class="status-warning">Bekliyor</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-gray-500">{{ $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('d.m.Y') : '—' }}</td>
                            <td class="py-2 text-gray-500">{{ $p->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ===================== GUARD LOGLARI ===================== --}}
        <div x-show="tab === 'guardlogs'" class="p-4">
            @if($guardLogs->isEmpty())
                <p class="text-center text-gray-500 py-8 text-sm">Guard logu yok.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="text-left text-gray-500 border-b" style="border-color:var(--admin-border)">
                            <th class="pb-2 pr-4">Aksiyon</th>
                            <th class="pb-2 pr-4">Neden</th>
                            <th class="pb-2 pr-4">Önem</th>
                            <th class="pb-2 pr-4">Detay</th>
                            <th class="pb-2">Tarih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--admin-border)">
                        @foreach($guardLogs as $log)
                        <tr>
                            <td class="py-2 pr-4">
                                <span class="{{ str_contains(strtolower($log->action ?? ''), 'fail') || str_contains(strtolower($log->action ?? ''), 'block') ? 'status-danger' : 'status-success' }}">
                                    {{ $log->action ?? '—' }}
                                </span>
                            </td>
                            <td class="py-2 pr-4 text-gray-400">{{ $log->reason ?? '—' }}</td>
                            <td class="py-2 pr-4">
                                @php $sev = $log->severity ?? 'low'; @endphp
                                <span class="{{ $sev === 'high' ? 'text-red-400' : ($sev === 'medium' ? 'text-amber-400' : 'text-gray-500') }} text-[11px] font-semibold uppercase">{{ $sev }}</span>
                            </td>
                            <td class="py-2 pr-4 text-gray-500 max-w-xs truncate">
                                {{ is_array($log->details) ? json_encode($log->details) : ($log->details ?? '—') }}
                            </td>
                            <td class="py-2 text-gray-500">{{ $log->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>{{-- /glass-card tablar --}}

    {{-- ===== VATANSMS HESAP KARTI ===== --}}
    <div class="glass-card p-6 mt-4" x-data="{ showKey: false, showSecret: false }">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[15px] font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                VatanSMS Hesabı
            </h3>
            @if($user->canSendSmsDirectly())
                <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/25">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Direkt Gönderim Aktif
                </span>
            @else
                <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/25">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    Admin Onay Modu
                    @if(!$user->hasApprovedDocuments()) · Evrak yok @endif
                    @if(!$user->hasVatanSmsAccount()) · API girilmemiş @endif
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sol: Form --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">API Key (api_id)</label>
                    <div class="relative">
                        <input wire:model="vatanApiKey" :type="showKey ? 'text' : 'password'"
                               placeholder="VatanSMS API ID" class="admin-input pr-10 font-mono text-[13px]">
                        <button type="button" @click="showKey = !showKey"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <svg x-show="!showKey" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showKey" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('vatanApiKey')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">API Şifre (api_key)</label>
                    <div class="relative">
                        <input wire:model="vatanApiSecret" :type="showSecret ? 'text' : 'password'"
                               placeholder="VatanSMS API Şifre" class="admin-input pr-10 font-mono text-[13px]">
                        <button type="button" @click="showSecret = !showSecret"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <svg x-show="!showSecret" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showSecret" style="display:none" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('vatanApiSecret')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Varsayılan Başlık</label>
                        <input wire:model="vatanSender" type="text" maxlength="50" placeholder="FIRMAADI"
                               class="admin-input font-mono" style="text-transform:uppercase">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Hesap ID</label>
                        <input wire:model="vatanAccountId" type="text" maxlength="100" placeholder="Referans ID"
                               class="admin-input font-mono text-[12px]">
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                    <input wire:model="documentApproved" type="checkbox" id="docApproved2_{{ $userId }}"
                           class="w-4 h-4 rounded accent-emerald-500">
                    <label for="docApproved2_{{ $userId }}" class="cursor-pointer select-none">
                        <span class="text-[13px] font-semibold text-white">Evrak Onaylı Hesap</span>
                        <span class="block text-[11px] text-gray-500">İşaretli + API giriliyse direkt gönderim aktif olur.</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button wire:click="saveVatanSms" wire:loading.attr="disabled" class="btn-primary flex-1 text-sm flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="saveVatanSms" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        <svg wire:loading.remove wire:target="saveVatanSms" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Kaydet
                    </button>
                    <button wire:click="testVatanSmsConnection" wire:loading.attr="disabled"
                            class="flex-1 py-2 px-4 text-sm font-semibold rounded-xl border transition-all text-indigo-400 hover:bg-indigo-500/10 flex items-center justify-center gap-2"
                            style="border-color:rgba(99,102,241,.3)">
                        <svg wire:loading wire:target="testVatanSmsConnection" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        <svg wire:loading.remove wire:target="testVatanSmsConnection" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        API Test
                    </button>
                </div>

                @if($vatanTestMsg)
                <div class="p-3 rounded-xl text-[12px] {{ str_contains($vatanTestMsg, 'bulundu') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                    {{ $vatanTestMsg }}
                </div>
                @endif
            </div>

            {{-- Sağ: Başlık Listesi --}}
            <div>
                <h4 class="text-[12px] font-semibold text-gray-400 uppercase tracking-wide flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    API'den Gönderici Başlıkları
                </h4>

                @if(!empty($vatanSenders))
                    <div class="space-y-1.5 max-h-60 overflow-y-auto pr-1">
                        @foreach($vatanSenders as $sender)
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg" style="background:var(--admin-bg);border:1px solid var(--admin-border)">
                            <span class="text-[13px] font-mono text-white">{{ is_array($sender) ? ($sender['name'] ?? $sender['sender'] ?? json_encode($sender)) : $sender }}</span>
                            <button type="button"
                                    wire:click="$set('vatanSender', '{{ is_array($sender) ? ($sender['name'] ?? $sender['sender'] ?? '') : $sender }}')"
                                    class="text-[11px] text-indigo-400 hover:text-indigo-300 transition-colors px-2 py-0.5 rounded hover:bg-indigo-500/10">
                                Seç
                            </button>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl" style="background:var(--admin-bg);border:1px dashed var(--admin-border)">
                        <svg class="w-7 h-7 text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-[12px] text-gray-600">API test et →<br>başlıklar burada listelenir</p>
                    </div>
                @endif

                <div class="mt-4 p-3 rounded-xl text-[11px] text-gray-500" style="background:var(--admin-bg);border:1px dashed var(--admin-border)">
                    <p class="font-semibold text-gray-400 mb-1">📋 Nasıl Çalışır?</p>
                    <ul class="space-y-0.5 list-disc list-inside">
                        <li>Evrak onayı <strong>+</strong> API giriliyse → <span class="text-emerald-400">Direkt Gönderim</span></li>
                        <li>Biri eksikse → <span class="text-amber-400">Admin Onay Modu</span></li>
                        <li>Evrakssız SMS'ler sistem numarsından çıkar</li>
                    </ul>
                </div>
            </div>

