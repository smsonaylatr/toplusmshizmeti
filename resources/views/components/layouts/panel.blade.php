<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel' }} — TopluSMS</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /*
         * Light mode kontrast düzeltmesi
         * text-gray-400 (#9ca3af) ve text-gray-500 (#6b7280) light modda
         * beyaz/açık arka plan üzerinde okunaksız. Aşağıdaki kurallarla
         * bu sınıflar WCAG AA uyumlu değerlere taşınıyor.
         */
        .text-gray-400,
        .text-gray-400\/80,
        [class*="text-gray-4"] {
            color: #6b7280 !important; /* gray-500 */
        }
        .text-gray-500,
        [class*="text-gray-5"] {
            color: #4b5563 !important; /* gray-600 */
        }
        .text-gray-300,
        [class*="text-gray-3"] {
            color: #6b7280 !important; /* gray-500 */
        }

        /* Placeholder yoğunluğu da artır */
        ::placeholder { color: #9ca3af !important; opacity: 1; }

        /* Sidebar içinde (koyu arka plan) override'ları geri al */
        .sidebar-inner .text-gray-400,
        .sidebar-inner .text-gray-500,
        .sidebar-inner .text-gray-300 { color: inherit !important; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-gray-800 antialiased" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">

    <div x-data="{ sidebarOpen: false, referralOpen: false }" class="min-h-screen flex flex-col">

        {{-- Top Orange Navbar (full-width, VATANSMS style) --}}
        <header class="sticky top-0 z-50 bg-[#2563eb]">
            <div class="flex items-center h-[46px]">
                {{-- Left: Hamburger + Logo --}}
                <div class="flex items-center gap-3 w-[240px] shrink-0 px-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-white/80">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="font-bold text-[17px] text-white tracking-tight">TOPLUSMSHİZMETİ</span>
                </div>

                {{-- Search --}}
                <div class="hidden sm:block relative w-48 lg:w-56 pl-3">
                    <input type="text" placeholder="Arama Yap" class="w-full pl-3 pr-8 py-[5px] bg-white border border-gray-300 rounded text-[13px] text-gray-600 placeholder-gray-400 focus:outline-none">
                    <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                {{-- Spacer --}}
                <div class="flex-1"></div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-2">
                    {{-- Bizi Tavsiye Edin --}}
                    <button @click="referralOpen = true" class="hidden md:flex items-center gap-1.5 px-3 py-1.5 border border-white text-white text-[12px] font-semibold rounded hover:bg-white/10 transition-colors">
                        <span>Bizi tavsiye edin</span>
                        <span class="text-[10px] font-normal opacity-90">Hediye SMS kazanın</span>
                    </button>

                    {{-- SMS Kredi Göstergesi --}}
                    @php
                        $smsCredits    = auth()->user()->sms_credits ?? 0;
                        $creditUrl     = route('panel.payment.notification');
                        $creditClass   = $smsCredits <= 50
                            ? 'bg-red-500/25 text-white border border-red-400/50'
                            : 'bg-white/15 text-white border border-white/30';
                    @endphp
                    <a href="{{ $creditUrl }}"
                       class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded text-[12px] font-semibold transition-colors {{ $creditClass }} hover:bg-white/25">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>{{ number_format($smsCredits) }} SMS</span>
                        @if($smsCredits <= 50)
                            <span class="text-[9px] font-normal opacity-90">Kredi Yükle</span>
                        @endif
                    </a>

                    {{-- Divider --}}
                    <div class="w-px h-6 bg-white/30 mx-1 hidden md:block"></div>

                    {{-- Notification Bell --}}
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative text-white hover:text-white/80 p-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-red-500 rounded-full border border-[#2563eb] text-[9px] text-white font-bold flex items-center justify-center min-w-[18px] h-[18px]">{{ $unreadCount }}</span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="notifOpen" @click.away="notifOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-[100]" x-cloak>
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-gray-800">Bildirimler</h3>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('panel.notifications.readAll') }}">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-[#2563eb] hover:underline">Tümünü Okundu İşaretle</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                @php $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get(); @endphp
                                @forelse($notifications as $notif)
                                    <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ !$notif->is_read ? 'bg-blue-50/50' : '' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="shrink-0 mt-0.5">
                                                @if($notif->type === 'success')
                                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                @elseif($notif->type === 'warning')
                                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                                @elseif($notif->type === 'error')
                                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-800">{{ $notif->title }}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notif->is_read)
                                                <span class="w-2 h-2 bg-[#2563eb] rounded-full shrink-0 mt-1.5"></span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center">
                                        <p class="text-sm text-gray-400">Bildiriminiz bulunmamakta.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Grid/Apps Icon --}}
                    <button class="text-white hover:text-white/80 p-1.5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/></svg>
                    </button>

                    {{-- User Avatar --}}
                    <div class="w-8 h-8 rounded-full bg-[#1d4ed8] border-2 border-white/60 flex items-center justify-center text-white text-sm font-bold cursor-pointer">
                        {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
            {{-- Bottom border line --}}
            <div class="h-[1px] bg-[#1d4ed8]"></div>
        </header>

        {{-- Body: Sidebar + Content --}}
        <div class="flex flex-1 min-h-0">

            {{-- Mobile Overlay --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/50 z-40 lg:hidden"
                x-cloak
            ></div>

            {{-- Sidebar --}}
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-[47px] left-0 z-50 w-[240px] transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-[47px] lg:inset-auto lg:z-auto lg:h-[calc(100vh-47px)] lg:shrink-0"
            >
                <div class="flex flex-col h-full bg-[#1e293b] overflow-hidden">

                {{-- Navigation --}}
                @php
                    $openMenu = match(true) {
                        request()->routeIs('panel.sms.*') => 'sms',
                        request()->routeIs('panel.whatsapp.*') => 'whatsapp',
                        request()->routeIs('panel.lcv.*') => 'lcv',
                        request()->routeIs('panel.contacts.*') => 'rehber',
                        request()->routeIs('panel.reports.*') => 'rapor',
                        request()->routeIs('panel.api.*') => 'api',
                        request()->routeIs('panel.subusers.*') => 'altkullanici',
                        request()->routeIs('panel.blacklist.*') => 'karaliste',
                        request()->routeIs('panel.templates.*') => 'sablon',
                        request()->routeIs('panel.pricing.*'),
                        request()->routeIs('panel.payment.*'),
                        request()->routeIs('panel.bank.*') => 'odeme',
                        request()->routeIs('panel.settings.*') => 'hesap',
                        request()->routeIs('panel.documents.*') => 'evrak',
                        request()->routeIs('panel.sendernames.*') => 'gonderici',
                        default => '',
                    };
                @endphp
                <nav class="flex-1 py-2 overflow-y-auto custom-scrollbar" x-data="{ openMenu: '{{ $openMenu }}' }">

                    {{-- Anasayfa --}}
                    <a href="{{ route('panel.dashboard') }}" wire:navigate
                       class="flex items-center gap-3 px-4 py-2.5 text-[13px] transition-colors
                              {{ request()->routeIs('panel.dashboard') ? 'bg-white/10 text-white border-l-3 border-[#2563eb]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Anasayfa
                    </a>

                    {{-- SMS İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'sms' ? '' : 'sms'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                SMS İşlemleri
                            </span>
                            <svg :class="openMenu === 'sms' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'sms'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.sms.create') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.create') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Gruplara SMS
                            </a>
                            <a href="{{ route('panel.sms.excel') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.excel') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Excel ile SMS
                            </a>
                            <a href="{{ route('panel.sms.customExcel') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.customExcel') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Özel Excel ile SMS
                            </a>
                            <a href="{{ route('panel.sms.bulk') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.bulk') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Toplu Numaralara SMS
                            </a>
                            <a href="{{ route('panel.sms.selected') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.selected') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Seçili Kayıtlara SMS
                            </a>
                            <a href="{{ route('panel.sms.single') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.sms.single') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Tek Numaraya SMS
                            </a>
                        </div>
                    </div>

                    {{-- Whatsapp İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'whatsapp' ? '' : 'whatsapp'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Whatsapp İşlemleri
                            </span>
                            <svg :class="openMenu === 'whatsapp' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'whatsapp'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.whatsapp.setup') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.setup') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Whatsapp Kurulum
                            </a>
                            <a href="{{ route('panel.whatsapp.groups') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.groups') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                Gruplara Mesaj
                            </a>
                            <a href="{{ route('panel.whatsapp.excel') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.excel') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Excel ile Mesaj
                            </a>
                            <a href="{{ route('panel.whatsapp.bulk') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.bulk') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Toplu Numaralara Mesaj
                            </a>
                            <a href="{{ route('panel.whatsapp.single') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.single') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Tek Numaraya Mesaj
                            </a>
                            <a href="{{ route('panel.whatsapp.reports') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.reports') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Raporlar
                            </a>
                            <a href="{{ route('panel.whatsapp.pricing') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.whatsapp.pricing') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Paket Fiyat Listesi
                            </a>
                        </div>
                    </div>

                    {{-- LCV İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'lcv' ? '' : 'lcv'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                LCV İşlemleri
                            </span>
                            <svg :class="openMenu === 'lcv' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'lcv'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.lcv.create') }}" wire:navigate class="flex items-center gap-2 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.lcv.*') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                LCV Oluştur
                            </a>
                        </div>
                    </div>

                    {{-- Rehber İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'rehber' ? '' : 'rehber'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('panel.contacts.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Rehber İşlemleri
                            </span>
                            <svg :class="openMenu === 'rehber' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'rehber'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.contacts.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Numara Ekle</a>
                            <a href="{{ route('panel.contacts.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.contacts.index') ? 'text-white bg-white/5' : '' }}">Tüm Rehberi Listele</a>
                            <a href="{{ route('panel.contacts.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Grupları Listele</a>
                        </div>
                    </div>

                    {{-- WhatsApp Rehber --}}
                    <div>
                        <button @click="openMenu = openMenu === 'warehber' ? '' : 'warehber'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                WhatsApp Rehber
                            </span>
                            <svg :class="openMenu === 'warehber' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'warehber'" x-transition class="bg-black/10">
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Numara Ekle</a>
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Tüm Rehberi Listele</a>
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Grupları Listele</a>
                        </div>
                    </div>

                    {{-- Rapor İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'rapor' ? '' : 'rapor'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('panel.reports.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Rapor İşlemleri
                            </span>
                            <svg :class="openMenu === 'rapor' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'rapor'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.reports.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.reports.index') ? 'text-white bg-white/5' : '' }}">Tüm Raporlar</a>
                            <a href="{{ route('panel.reports.rejected') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.reports.rejected') ? 'text-white bg-white/5' : '' }}">Ret Raporları</a>
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Raporlarda Arama</a>
                        </div>
                    </div>

                    {{-- API & Entegrasyon --}}
                    <div>
                        <button @click="openMenu = openMenu === 'api' ? '' : 'api'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                API & Entegrasyon
                            </span>
                            <svg :class="openMenu === 'api' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'api'" x-transition class="bg-black/10">
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">API Dokümantasyonu</a>
                            <a href="#" class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5">Paket Fiyat Listesi</a>
                        </div>
                    </div>

                    {{-- Alt Kullanıcı İşlemleri --}}
                    <a href="{{ route('panel.subusers.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-[13px] transition-colors {{ request()->routeIs('panel.subusers.*') ? 'bg-white/10 text-white border-l-3 border-[#2563eb]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Alt Kullanıcı İşlemleri
                    </a>

                    {{-- Kara Liste --}}
                    <a href="{{ route('panel.blacklist.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-[13px] transition-colors {{ request()->routeIs('panel.blacklist.*') ? 'bg-white/10 text-white border-l-3 border-[#2563eb]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Kara Liste
                    </a>

                    {{-- Şablon İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'sablon' ? '' : 'sablon'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                Şablon İşlemleri
                            </span>
                            <svg :class="openMenu === 'sablon' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'sablon'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.templates.create') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.templates.create') ? 'text-white bg-white/5' : '' }}">Şablon Oluştur</a>
                            <a href="{{ route('panel.templates.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.templates.index') ? 'text-white bg-white/5' : '' }}">Tüm Şablonlar</a>
                            <a href="{{ route('panel.templates.birthday') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.templates.birthday') ? 'text-white bg-white/5' : '' }}">Doğum Günü</a>
                        </div>
                    </div>

                    {{-- Ödeme İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'odeme' ? '' : 'odeme'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Kredi İşlemleri
                            </span>
                            <svg :class="openMenu === 'odeme' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'odeme'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.pricing.index') }}" wire:navigate class="flex items-center gap-2.5 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.pricing.*') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Paket Fiyat Listesi
                            </a>
                            <a href="{{ route('panel.payment.orders') }}" wire:navigate class="flex items-center gap-2.5 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.payment.orders') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Siparişlerim
                            </a>
                            <a href="{{ route('panel.payment.notification') }}" wire:navigate class="flex items-center gap-2.5 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.payment.notification') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                Ödeme Bildirimi
                            </a>
                            <a href="{{ route('panel.bank.accounts') }}" wire:navigate class="flex items-center gap-2.5 px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.bank.accounts') ? 'text-white bg-white/5' : '' }}">
                                <svg class="w-3.5 h-3.5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                Banka Hesapları
                            </a>
                        </div>

                    </div>

                    {{-- Hesap İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'hesap' ? '' : 'hesap'" class="w-full flex items-center justify-between px-4 py-2.5 text-[13px] text-gray-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('panel.settings.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Hesap İşlemleri
                            </span>
                            <svg :class="openMenu === 'hesap' ? 'rotate-90' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'hesap'" x-transition class="bg-black/10">
                            <a href="{{ route('panel.settings.index') }}" wire:navigate class="block px-4 pl-11 py-2 text-[12px] text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('panel.settings.index') ? 'text-white bg-white/5' : '' }}">Profil Ayarları</a>
                        </div>
                    </div>

                    {{-- Evrak İşlemleri --}}
                    <a href="{{ route('panel.documents.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-[13px] transition-colors {{ request()->routeIs('panel.documents.*') ? 'bg-white/10 text-white border-l-3 border-[#2563eb]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Evrak İşlemleri
                    </a>

                    {{-- Gönderici Adları --}}
                    <a href="{{ route('panel.sendernames.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-[13px] transition-colors {{ request()->routeIs('panel.sendernames.*') ? 'bg-white/10 text-white border-l-3 border-[#2563eb]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Gönderici Adları
                    </a>


                    {{-- Admin Panel Linki (sadece admin hesaplar için) --}}
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-semibold text-amber-300 hover:bg-amber-500/10 hover:text-amber-200 transition-colors" style="border-top: 1px solid rgba(255,255,255,.08); margin-top: 4px; padding-top: 10px;">
                        <span class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(245,158,11,.2);">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        Admin Panel
                        <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded" style="background: rgba(245,158,11,.25); color: #fbbf24;">ADMİN</span>
                    </a>
                    @endif

                    {{-- Çıkış --}}
                    <div class="mt-2 border-t border-white/10 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-gray-400 hover:bg-white/5 hover:text-red-400 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
            <div class="flex-1 flex flex-col min-w-0 lg:h-[calc(100vh-47px)] lg:overflow-y-auto">
                {{-- Page Content --}}
                <main class="flex-1 p-4 lg:p-5">
                    {{ $slot }}
                </main>
            </div>
        </div>{{-- /flex body --}}

        {{-- Referans OL - KAZAN Modal --}}
        <div x-show="referralOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="referralOpen = false"></div>

            {{-- Modal Content --}}
            <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-lg z-10 overflow-hidden rounded-2xl shadow-2xl">
                {{-- Close Button --}}
                <button @click="referralOpen = false" class="absolute top-4 right-4 z-20 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/40 text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Gradient Header --}}
                <div class="bg-gradient-to-br from-[#2563eb] via-[#1d4ed8] to-[#1e40af] px-6 pt-8 pb-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-white/15 backdrop-blur rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Referans OL - KAZAN</h2>
                    <p class="text-blue-100 text-sm leading-relaxed max-w-sm mx-auto">
                        Tanıdıklarınızı davet edin, hem siz hem de yeni üyemiz <span class="text-white font-semibold">hediye SMS</span> kazansın!
                    </p>
                </div>

                {{-- Form Body --}}
                <div class="bg-white px-6 py-6">
                    <form class="space-y-5">
                        {{-- Bilgileriniz --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">Bilgileriniz</span>
                            </div>
                            <div class="space-y-3">
                                <div class="relative">
                                    <input type="text" placeholder="Adınız Soyadınız" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-[#2563eb]/30 focus:border-[#2563eb] transition-all">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="relative">
                                    <input type="text" placeholder="5xxxxxxxxx" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-[#2563eb]/30 focus:border-[#2563eb] transition-all">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-gray-200"></div>
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        {{-- Referans Kişi --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">Referans Olacağınız Kişi</span>
                            </div>
                            <div class="space-y-3">
                                <div class="relative">
                                    <input type="text" placeholder="Ad Soyad" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="relative">
                                    <input type="text" placeholder="5xxxxxxxxx" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition-all">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="button" class="w-full py-3 bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] text-white text-sm font-bold rounded-xl hover:from-[#1d4ed8] hover:to-[#1e40af] transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            FORMU GÖNDER
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
