<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sonucu — TopluSMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f0f2f5] min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    @if($order && $order->status === 'paid')
    {{-- BAŞARI --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden text-center">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-8">
            <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">Ödeme Başarılı!</h1>
            <p class="text-green-100 text-sm">Krediniz hesabınıza yüklendi.</p>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <p class="text-xs text-green-600 font-medium mb-1">Yüklenen SMS Kredisi</p>
                <p class="text-4xl font-bold text-green-700">{{ $order->sms_amount_formatted }}</p>
                <p class="text-sm text-green-600 mt-0.5">SMS</p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-left">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-0.5">Paket</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $order->package_name }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-0.5">Ödenen Tutar</p>
                    <p class="text-sm font-semibold text-gray-800">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                    <p class="text-xs text-gray-500 mb-0.5">Sipariş No</p>
                    <p class="text-sm font-mono text-gray-800">{{ $order->merchant_oid }}</p>
                </div>
            </div>

            <div class="space-y-2 pt-2">
                <a href="{{ route('panel.dashboard') }}"
                   class="block w-full py-3 bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] text-white text-sm font-bold rounded-xl hover:from-[#1d4ed8] hover:to-[#1e40af] transition-all text-center">
                    Panele Git
                </a>
                <a href="{{ route('panel.payment.orders') }}"
                   class="block w-full py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors text-center">
                    Siparişlerim
                </a>
            </div>
        </div>
    </div>

    @elseif($order && $order->status === 'failed')
    {{-- HATA --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden text-center">
        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-8">
            <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">Ödeme Başarısız</h1>
            <p class="text-red-100 text-sm">İşleminiz tamamlanamadı.</p>
        </div>
        <div class="p-6 space-y-4">
            @if($order->failure_message)
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <p class="text-sm text-red-700">{{ $order->failure_message }}</p>
                </div>
            @endif

            <div class="space-y-2">
                <a href="{{ route('panel.pricing.index') }}"
                   class="block w-full py-3 bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] text-white text-sm font-bold rounded-xl hover:from-[#1d4ed8] hover:to-[#1e40af] transition-all text-center">
                    Tekrar Dene
                </a>
                <a href="{{ route('panel.dashboard') }}"
                   class="block w-full py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors text-center">
                    Panele Dön
                </a>
            </div>
        </div>
    </div>

    @else
    {{-- BEKLENİYOR / BİLİNMİYOR --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden text-center">
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-8">
            <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4 animate-spin" style="animation-duration:2s">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">İşlem Bekleniyor</h1>
            <p class="text-amber-100 text-sm">Ödemeniz işleniyor, lütfen bekleyin.</p>
        </div>
        <div class="p-6">
            <a href="{{ route('panel.dashboard') }}"
               class="block w-full py-3 bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] text-white text-sm font-bold rounded-xl text-center">
                Panele Dön
            </a>
        </div>
    </div>
    @endif
</div>

</body>
</html>
