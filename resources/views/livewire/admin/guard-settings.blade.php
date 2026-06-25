<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(139,92,246,.2), rgba(99,102,241,.1));">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Guard Ayarları</h1>
                <p class="text-sm text-gray-500 mt-0.5">AI güvenlik sistemi eşik değerleri ve yapılandırma</p>
            </div>
        </div>
    </div>

    {{-- Success Flash --}}
    @if(session('success'))
        <div class="flash-success mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="max-w-3xl">
        <form wire:submit="save" class="space-y-4">

            {{-- ═══ Mesaj Limitleri ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(59,130,246,.1);">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Mesaj Limitleri</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kullanıcı başına mesaj gönderim sınırları</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Günlük Limit</label>
                            <input wire:model="dailyMessageLimit" type="number" class="admin-input" placeholder="ör: 10000">
                            <p class="text-[10px] text-gray-600 mt-1.5">Bir kullanıcının günde gönderebileceği maksimum mesaj</p>
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Saatlik Limit</label>
                            <input wire:model="hourlyMessageLimit" type="number" class="admin-input" placeholder="ör: 1000">
                            <p class="text-[10px] text-gray-600 mt-1.5">Bir kullanıcının saatte gönderebileceği maksimum mesaj</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Risk Eşikleri ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(245,158,11,.1);">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5C2.5 17.333 3.462 19 5.002 19z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Risk Eşikleri</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">AI risk skoru değerlendirme sınırları</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Askıya Alma Eşiği</label>
                            <div class="relative">
                                <input wire:model="suspendThreshold" type="number" min="0" max="100" class="admin-input" placeholder="ör: 80">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-gray-600 font-medium pointer-events-none">/100</span>
                            </div>
                            <p class="text-[10px] text-gray-600 mt-1.5">Risk skoru bu değeri aşarsa kullanıcı otomatik askıya alınır</p>
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Uyarı Eşiği</label>
                            <div class="relative">
                                <input wire:model="warnThreshold" type="number" min="0" max="100" class="admin-input" placeholder="ör: 50">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-gray-600 font-medium pointer-events-none">/100</span>
                            </div>
                            <p class="text-[10px] text-gray-600 mt-1.5">Risk skoru bu değeri aşarsa admin uyarısı tetiklenir</p>
                        </div>
                    </div>

                    {{-- Visual threshold indicator --}}
                    <div class="mt-5 p-3.5 rounded-xl" style="background: var(--admin-inner-bg); border: 1px solid var(--admin-inner-border);">
                        <div class="flex items-center gap-2 mb-2.5">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[11px] font-medium text-gray-500">Eşik Skalası</span>
                        </div>
                        <div class="h-2.5 rounded-full overflow-hidden flex gap-0.5" style="background: var(--admin-bg);">
                            <div class="h-full rounded-l-full" style="width: 50%; background: linear-gradient(90deg, #059669, #10b981);"></div>
                            <div class="h-full" style="width: 30%; background: linear-gradient(90deg, #d97706, #f59e0b);"></div>
                            <div class="h-full rounded-r-full" style="width: 20%; background: linear-gradient(90deg, #dc2626, #ef4444);"></div>
                        </div>
                        <div class="flex justify-between mt-1.5">
                            <span class="text-[10px] text-emerald-400 font-medium">0 — Güvenli</span>
                            <span class="text-[10px] text-amber-400 font-medium">Uyarı Bölgesi</span>
                            <span class="text-[10px] text-red-400 font-medium">Askıya Al — 100</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Özellikler ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(16,185,129,.1);">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Güvenlik Özellikleri</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Otomatik koruma ve filtreleme modülleri</p>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    {{-- Auto Suspend --}}
                    <label class="guard-toggle-card flex items-center gap-4 p-4 rounded-xl cursor-pointer" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <input wire:model="autoSuspendEnabled" type="checkbox" class="hidden">
                        <div class="flex-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(239,68,68,.1);">
                                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[13px] text-white font-medium">Otomatik Askıya Alma</p>
                                    <p class="text-[11px] text-gray-600 mt-0.5">Risk skoru eşik değerini aştığında kullanıcıyı otomatik olarak askıya alır</p>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <div class="w-9 h-5 rounded-full relative" style="background: rgba(148,163,184,.25);">
                                <div class="w-3.5 h-3.5 rounded-full bg-white absolute top-[3px] left-[3px] shadow-sm transition-transform duration-200"></div>
                            </div>
                        </div>
                    </label>

                    {{-- BDK Filter --}}
                    <label class="guard-toggle-card flex items-center gap-4 p-4 rounded-xl cursor-pointer" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <input wire:model="bdkFilterEnabled" type="checkbox" class="hidden">
                        <div class="flex-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(139,92,246,.1);">
                                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[13px] text-white font-medium">BDK Filtresi</p>
                                    <p class="text-[11px] text-gray-600 mt-0.5">BDK yasaklı kelime ve ifade kontrolü</p>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <div class="w-9 h-5 rounded-full relative" style="background: rgba(148,163,184,.25);">
                                <div class="w-3.5 h-3.5 rounded-full bg-white absolute top-[3px] left-[3px] shadow-sm transition-transform duration-200"></div>
                            </div>
                        </div>
                    </label>

                    {{-- Spam Filter --}}
                    <label class="guard-toggle-card flex items-center gap-4 p-4 rounded-xl cursor-pointer" style="background: var(--admin-bg); border: 1px solid var(--admin-border);">
                        <input wire:model="spamFilterEnabled" type="checkbox" class="hidden">
                        <div class="flex-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(245,158,11,.1);">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[13px] text-white font-medium">Spam Filtresi</p>
                                    <p class="text-[11px] text-gray-600 mt-0.5">Gönderilen mesajlarda spam pattern taraması ve otomatik engelleme</p>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <div class="w-9 h-5 rounded-full relative" style="background: rgba(148,163,184,.25);">
                                <div class="w-3.5 h-3.5 rounded-full bg-white absolute top-[3px] left-[3px] shadow-sm transition-transform duration-200"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex items-center justify-between pt-2">
                <p class="text-[11px] text-gray-600">
                    <svg class="w-3.5 h-3.5 inline-block mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Değişiklikler anında uygulanır
                </p>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>

    <style>
        .guard-toggle-card { transition: border-color .2s ease, box-shadow .2s ease; }
        .guard-toggle-card:hover { border-color: rgba(37,99,235,.25) !important; box-shadow: 0 0 15px rgba(37,99,235,.04); }
        .guard-toggle-card input:checked ~ .flex-1 .text-white { color: var(--admin-text-primary) !important; }
        .guard-toggle-card input:checked ~ .shrink-0 > div {
            background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
        }
        .guard-toggle-card input:checked ~ .shrink-0 > div > div {
            transform: translateX(16px);
        }
    </style>
</div>
