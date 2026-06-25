<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kredi Satın Al — TopluSMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        #paytriframe { min-height: 500px; }
    </style>
</head>
<body class="bg-[#f0f2f5]">

<div class="min-h-screen flex flex-col">

    {{-- Basit Header --}}
    <header class="bg-[#2563eb] h-[46px] flex items-center px-4">
        <a href="{{ route('panel.dashboard') }}" class="flex items-center gap-2 text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-bold text-[15px]">TOPLUSMS</span>
        </a>
        <div class="flex-1"></div>
        <span class="text-white/80 text-xs">Güvenli Ödeme</span>
        <svg class="w-4 h-4 text-green-300 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </header>

    {{-- Ana içerik --}}
    <main class="flex-1 flex items-start justify-center p-4 pt-8">
        <div class="w-full max-w-2xl">

            {{-- Sipariş özeti kutusu --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4 overflow-hidden">
                <div class="bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] p-4">
                    <h1 class="text-white font-bold text-lg">Ödeme Sayfası</h1>
                    <p class="text-blue-200 text-sm mt-0.5">PayTR güvenli ödeme altyapısı</p>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Seçilen Paket</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $order->package_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Sipariş No: <span class="font-mono text-gray-700">{{ $order->merchant_oid }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 mb-0.5">Ödenecek Tutar</p>
                        <p class="font-bold text-2xl text-[#2563eb]">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</p>
                        <p class="text-[10px] text-gray-400">KDV Dahil</p>
                    </div>
                </div>
            </div>

            {{-- PayTR iFrame --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-3 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">256-bit SSL Şifreli Güvenli Ödeme</span>
                </div>

                <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                <iframe
                    src="https://www.paytr.com/odeme/guvenli/{{ $iframeToken }}"
                    id="paytriframe"
                    frameborder="0"
                    scrolling="no"
                    style="width: 100%; min-height: 500px;"
                ></iframe>
                <script>iFrameResize({}, '#paytriframe');</script>
            </div>

            {{-- Güvenlik notu --}}
            <p class="text-center text-xs text-gray-400 mt-4">
                Kart bilgileriniz PayTR altyapısında şifrelenerek işlenir. TopluSMS kart bilgilerinizi saklamaz.
            </p>

        </div>
    </main>

</div>

</body>
</html>
