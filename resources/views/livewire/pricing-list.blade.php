<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Paket Fiyat Listesi</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">SMS Paket Fiyatları</h2></div>

    {{-- Başarı/Hata mesajları --}}
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->has('paytr'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4">
            {{ $errors->first('paytr') }}
        </div>
    @endif

    @php
        $packages = [
            ['index'=>0,'amount'=>'1.000',   'sms'=>1000,   'price'=>350,   'total'=>420,   'perSms'=>'0,350','color'=>'#3498db','popular'=>false],
            ['index'=>1,'amount'=>'2.500',   'sms'=>2500,   'price'=>750,   'total'=>900,   'perSms'=>'0,300','color'=>'#27ae60','popular'=>false],
            ['index'=>2,'amount'=>'5.000',   'sms'=>5000,   'price'=>1250,  'total'=>1500,  'perSms'=>'0,250','color'=>'#2563eb','popular'=>true],
            ['index'=>3,'amount'=>'10.000',  'sms'=>10000,  'price'=>2000,  'total'=>2400,  'perSms'=>'0,200','color'=>'#e74c3c','popular'=>false],
            ['index'=>4,'amount'=>'25.000',  'sms'=>25000,  'price'=>4375,  'total'=>5250,  'perSms'=>'0,175','color'=>'#9b59b6','popular'=>false],
            ['index'=>5,'amount'=>'50.000',  'sms'=>50000,  'price'=>7500,  'total'=>9000,  'perSms'=>'0,150','color'=>'#1abc9c','popular'=>false],
            ['index'=>6,'amount'=>'100.000', 'sms'=>100000, 'price'=>13000, 'total'=>15600, 'perSms'=>'0,130','color'=>'#34495e','popular'=>false],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($packages as $pkg)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden {{ $pkg['popular'] ? 'ring-2 ring-[#2563eb]' : '' }} relative">
                @if($pkg['popular'])
                    <div class="absolute top-0 right-0 bg-[#2563eb] text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">EN POPÜLER</div>
                @endif
                <div class="p-5 text-center" style="border-top: 4px solid {{ $pkg['color'] }}">
                    <p class="text-3xl font-bold text-gray-800">{{ $pkg['amount'] }}</p>
                    <p class="text-sm text-gray-400 mb-3">SMS Kredi</p>
                    <div class="bg-gray-50 rounded-lg p-3 mb-1">
                        <p class="text-2xl font-bold" style="color: {{ $pkg['color'] }}">{{ number_format($pkg['total'], 0, ',', '.') }} ₺</p>
                        <p class="text-[11px] text-gray-400">KDV Dahil</p>
                    </div>
                    <p class="text-[10px] text-gray-400 mb-1">KDV Hariç: {{ number_format($pkg['price'], 0, ',', '.') }} ₺</p>
                    <p class="text-xs text-gray-500 mb-4">Birim Fiyat: <strong>{{ $pkg['perSms'] }} ₺</strong></p>

                    {{-- Ödeme Formu --}}
                    <form action="{{ route('panel.payment.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_index" value="{{ $pkg['index'] }}">
                        <button type="submit"
                                class="w-full px-4 py-2.5 text-white text-sm font-bold rounded hover:opacity-90 transition-all flex items-center justify-center gap-2"
                                style="background-color: {{ $pkg['color'] }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            SATIN AL
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Güvenlik ve Bilgi --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-700">
            <strong>💳 Online Ödeme:</strong> PayTR güvenli ödeme altyapısı ile kredi/banka kartıyla anlık ödeme yapabilirsiniz. Ödeme onaylandıktan sonra krediniz otomatik yüklenir.
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
            <strong>🏦 Havale/EFT:</strong> <a href="{{ route('panel.payment.notification') }}" class="text-[#2563eb] hover:underline">Ödeme Bildirimi</a> sayfasından havale/EFT bilgisi gönderebilir, banka hesap numarası için <a href="{{ route('panel.bank.accounts') }}" class="text-[#2563eb] hover:underline">Banka Hesapları</a>'na bakabilirsiniz.
        </div>

    </div>
</div>
