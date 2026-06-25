<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">WhatsApp Paket Fiyatları</h1>
    <div class="mb-5"><h2 class="text-base font-semibold text-green-600 border-b-2 border-green-600 inline-block pb-1">Kredi Paketleri</h2></div>

    {{-- Kalan Kredi --}}
    <div class="mb-5 p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl text-white flex items-center justify-between shadow-lg shadow-green-500/20">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
            <span class="text-sm font-medium">Mevcut WhatsApp Krediniz</span>
        </div>
        <span class="text-2xl font-bold">{{ number_format(auth()->user()->whatsapp_credits ?? 0) }}</span>
    </div>

    {{-- Paketler --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        @foreach($packages as $pkg)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative {{ $pkg['popular'] ? 'ring-2 ring-blue-500' : '' }}">
            @if($pkg['popular'])
            <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl">POPÜLER</div>
            @endif
            <div class="p-5 text-center">
                <h3 class="text-sm font-bold text-gray-800 mb-1">{{ $pkg['name'] }}</h3>
                <div class="text-3xl font-extrabold text-gray-900 my-3">{{ number_format($pkg['credits']) }}</div>
                <p class="text-xs text-gray-400 mb-4">WhatsApp Kredi</p>

                <div class="space-y-2 mb-4 text-[12px] text-gray-500">
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span>Mesaj Başı</span>
                        <span class="font-semibold text-gray-700">{{ $pkg['perMsg'] }} ₺</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span>Toplam</span>
                        <span class="font-semibold text-gray-700">{{ number_format($pkg['price']) }} ₺</span>
                    </div>
                </div>

                <button class="w-full py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-md shadow-green-500/20">
                    SATIN AL
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Bilgi --}}
    <div class="mt-5 p-4 bg-amber-50 rounded-2xl border border-amber-100 text-[12px] text-amber-700">
        <strong>Not:</strong> Tüm fiyatlar KDV hariçtir. Paketler arasında geçiş yapmadan önce mevcut kredinizi tüketmeniz gerekmez — yeni kredi eklenir. Özel paket ihtiyaçlarınız için <strong>destek@toplusms.com</strong> ile iletişime geçin.
    </div>
</div>
