<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(37,99,235,.2), rgba(59,130,246,.1));">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">API Ayarları</h1>
                <p class="text-sm text-gray-500 mt-0.5">VatanSMS REST API bağlantı yapılandırması</p>
            </div>
        </div>
    </div>

    {{-- Flash --}}
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

            {{-- ═══ API Kimlik Bilgileri ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(37,99,235,.1);">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">API Kimlik Bilgileri</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">VatanSMS hesabınızdaki API ID ve Key bilgileri</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    {{-- API ID --}}
                    <div>
                        <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">API ID</label>
                        <input wire:model="apiId" type="text" class="admin-input" placeholder="VatanSMS API ID numaranız">
                        @error('apiId') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- API Key --}}
                    <div>
                        <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">API Key</label>
                        <div class="relative">
                            <input wire:model="apiKey"
                                   type="{{ $showApiKey ? 'text' : 'password' }}"
                                   class="admin-input pr-10"
                                   placeholder="VatanSMS gizli API anahtarınız">
                            <button type="button"
                                    wire:click="toggleShowApiKey"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                                @if($showApiKey)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>
                        @error('apiKey') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-gray-600 mt-1.5">
                            API bilgilerinize
                            <a href="https://vatansms.net" target="_blank" class="text-blue-400 hover:underline">vatansms.net</a>
                            → Hesabım → API Bilgilerimi Görüntüle bölümünden ulaşabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ═══ Gönderim Ayarları ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(16,185,129,.1);">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Gönderim Ayarları</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Varsayılan gönderici adı ve mesaj tipi</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Varsayılan Gönderici Adı</label>
                            <input wire:model="sender" type="text" maxlength="11" class="admin-input" placeholder="ör: TOPLUPLSMS">
                            @error('sender') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-gray-600 mt-1.5">Maksimum 11 karakter, VatanSMS'te onaylı gönderici adı olmalı</p>
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Varsayılan Mesaj Tipi</label>
                            <select wire:model="messageType" class="admin-input">
                                <option value="normal">Normal (ASCII)</option>
                                <option value="turkce">Türkçe (Türkçe karakter)</option>
                            </select>
                            @error('messageType') <p class="text-[11px] text-red-400 mt-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-gray-600 mt-1.5">Türkçe seçilince SMS limitiniz daha az kişiye gidebilir</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Bağlantı Testi ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(245,158,11,.1);">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Bağlantı Testi</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Kayıtlı API bilgileriyle VatanSMS bağlantısını test et</p>
                    </div>
                </div>
                <div class="p-5">
                    <button type="button"
                            wire:click="testConnection"
                            wire:loading.attr="disabled"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200"
                            style="background: rgba(245,158,11,.15); color: #f59e0b; border: 1px solid rgba(245,158,11,.25);">
                        <span wire:loading.remove wire:target="testConnection">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </span>
                        <span wire:loading wire:target="testConnection">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="testConnection">Bağlantıyı Test Et</span>
                        <span wire:loading wire:target="testConnection">Test ediliyor...</span>
                    </button>

                    {{-- Test Sonucu --}}
                    @if($testResult !== null)
                        <div class="mt-4 p-4 rounded-xl" style="background: var(--admin-inner-bg); border: 1px solid {{ ($testResult['success'] ?? false) ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)' }};">
                            @if($testResult['success'] ?? false)
                                <div class="flex items-center gap-2 text-emerald-400 font-semibold text-sm mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Bağlantı başarılı!
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @if(isset($testResult['name']))
                                        <div class="p-3 rounded-lg" style="background: var(--admin-bg);">
                                            <p class="text-[10px] text-gray-500 mb-0.5">Hesap Adı</p>
                                            <p class="text-[13px] text-white font-medium">{{ $testResult['name'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($testResult['balance']))
                                        <div class="p-3 rounded-lg" style="background: var(--admin-bg);">
                                            <p class="text-[10px] text-gray-500 mb-0.5">Bakiye (SMS)</p>
                                            <p class="text-[13px] text-emerald-400 font-medium">{{ number_format($testResult['balance'], 0, ',', '.') }}</p>
                                        </div>
                                    @endif
                                    @if(isset($testResult['email']))
                                        <div class="p-3 rounded-lg" style="background: var(--admin-bg);">
                                            <p class="text-[10px] text-gray-500 mb-0.5">E-posta</p>
                                            <p class="text-[13px] text-white font-medium">{{ $testResult['email'] }}</p>
                                        </div>
                                    @endif
                                </div>
                                {{-- Raw response for debug --}}
                                <details class="mt-3">
                                    <summary class="text-[11px] text-gray-600 cursor-pointer hover:text-gray-400 transition-colors">Ham Yanıt</summary>
                                    <pre class="mt-2 text-[10px] text-gray-400 overflow-x-auto">{{ json_encode($testResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </details>
                            @else
                                <div class="flex items-center gap-2 text-red-400 font-semibold text-sm mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Bağlantı başarısız
                                </div>
                                <p class="text-[12px] text-gray-400">{{ $testResult['message'] ?? 'API bağlantısı kurulamadı. API ID ve Key bilgilerinizi kontrol edin.' }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══ Aktif Ödeme Altyapısı ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(99,102,241,.1);">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold" style="color: var(--admin-text-primary);">Aktif Ödeme Altyapısı</h3>
                        <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">Kullanıcılara hangi ödeme sistemi sunulsun?</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- PayTR --}}
                        <label wire:click="$set('activeGateway', 'paytr')"
                               class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all"
                               style="{{ $activeGateway === 'paytr' ? 'background: rgba(99,102,241,.08); border: 1.5px solid rgba(99,102,241,.4);' : 'background: var(--admin-inner-bg); border: 1.5px solid var(--admin-border);' }}">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(99,102,241,.12);">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm" style="color: var(--admin-text-primary);">PayTR</p>
                                <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">iFrame API entegrasyonu</p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                 style="{{ $activeGateway === 'paytr' ? 'border-color: #6366f1; background: #6366f1;' : 'border-color: var(--admin-border);' }}">
                                @if($activeGateway === 'paytr')
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                @endif
                            </div>
                        </label>

                        {{-- iyzico --}}
                        <label wire:click="$set('activeGateway', 'iyzico')"
                               class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all"
                               style="{{ $activeGateway === 'iyzico' ? 'background: rgba(249,95,98,.08); border: 1.5px solid rgba(249,95,98,.4);' : 'background: var(--admin-inner-bg); border: 1.5px solid var(--admin-border);' }}">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(249,95,98,.12);">
                                <svg class="w-5 h-5" style="color: #f95f62;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm" style="color: var(--admin-text-primary);">iyzico</p>
                                <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">Checkout Form 3DS</p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                 style="{{ $activeGateway === 'iyzico' ? 'border-color: #f95f62; background: #f95f62;' : 'border-color: var(--admin-border);' }}">
                                @if($activeGateway === 'iyzico')
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                @endif
                            </div>
                        </label>
                    </div>
                    <p class="text-[10px] mt-3" style="color: var(--admin-text-muted);">
                        Değiştirip kaydettiğinizde tüm yeni ödemeler seçili altyapıyı kullanır.
                    </p>
                </div>
            </div>

            {{-- ═══ PayTR Sanal POS ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(99,102,241,.1);">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold" style="color: var(--admin-text-primary);">PayTR Sanal POS</h3>
                        <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">PayTR iFrame API entegrasyonu bilgileri</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Mağaza ID (Merchant ID)</label>
                            <input wire:model="paytrMerchantId" type="text" class="admin-input" placeholder="PayTR mağaza ID'niz">
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Merchant Key</label>
                            <input wire:model="paytrMerchantKey" type="password" class="admin-input" placeholder="PayTR Merchant Key">
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Merchant Salt</label>
                            <input wire:model="paytrMerchantSalt" type="password" class="admin-input" placeholder="PayTR Merchant Salt">
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Test Modu</label>
                            <select wire:model="paytrTestMode" class="admin-input">
                                <option value="1">Açık (Test - Gerçek para çekilmez)</option>
                                <option value="0">Kapalı (Canlı Mod)</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-600">
                        Bildirim URL: <code class="text-indigo-400 text-[9px]">{{ url('/panel/payment/callback') }}</code>
                    </p>
                </div>
            </div>

            {{-- ═══ iyzico Sanal POS ═══ --}}
            <div class="glass-card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom: 1px solid var(--admin-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(249,95,98,.1);">
                        <svg class="w-4 h-4" style="color: #f95f62;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold" style="color: var(--admin-text-primary);">iyzico Sanal POS</h3>
                        <p class="text-[11px] mt-0.5" style="color: var(--admin-text-secondary);">iyzico Checkout Form (3DS) bilgileri</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">API Key</label>
                            <input wire:model="iyzicoApiKey" type="text" class="admin-input" placeholder="iyzico API Key">
                        </div>
                        <div>
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Secret Key</label>
                            <input wire:model="iyzicoSecretKey" type="password" class="admin-input" placeholder="iyzico Secret Key">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[12px] font-medium text-gray-500 mb-1.5 block">Base URL</label>
                            <select wire:model="iyzicoBaseUrl" class="admin-input">
                                <option value="https://sandbox.iyzipay.com">Sandbox (Test)</option>
                                <option value="https://api.iyzipay.com">Canlı (Production)</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-600">
                        API bilgilerinize
                        <a href="https://merchant.iyzipay.com/settings" target="_blank" class="text-blue-400 hover:underline">iyzico Merchant Panel</a>
                        → Ayarlar → API ve Entegrasyon bölümünden ulaşabilirsiniz. &bull;
                        Callback URL: <code class="text-[#f95f62] text-[9px]">{{ url('/panel/payment/iyzico-callback') }}</code>
                    </p>
                </div>
            </div>


            <div class="flex items-center justify-between pt-2">
                <p class="text-[11px] text-gray-600">
                    <svg class="w-3.5 h-3.5 inline-block mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    API bilgileri şifreli olarak saklanır
                </p>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span wire:loading.remove wire:target="save">Ayarları Kaydet</span>
                    <span wire:loading wire:target="save">Kaydediliyor...</span>
                </button>
            </div>

        </form>
    </div>
</div>
