<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Banka Hesapları</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-4">
        {{-- Sol: Banka Hesabı Kartları --}}
        <div class="w-full lg:w-1/2 space-y-3">
            @forelse($bankAccounts as $acc)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-gray-500 tracking-wider mb-1">BANKA: {{ strtoupper($acc['bank_name']) }}</p>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">{{ $acc['account_name'] }}</h3>
                            <p class="text-[13px] text-gray-400 font-mono">IBAN: {{ wordwrap($acc['iban'], 4, ' ', true) }}</p>
                            @if(!empty($acc['branch']))
                                <p class="text-[11px] text-gray-400 mt-0.5">Şube: {{ $acc['branch'] }}</p>
                            @endif
                        </div>
                        <div class="w-10 h-10 bg-[#2563eb]/10 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </div>
                    </div>
                    <button x-data @click="navigator.clipboard.writeText('{{ $acc['iban'] }}').then(() => { $el.textContent = 'KOPYALANDI ✓'; setTimeout(() => $el.textContent = 'IBAN KOPYALA', 2000); })"
                            class="mt-3 text-[12px] font-bold text-gray-500 hover:text-[#2563eb] tracking-wider transition-colors">
                        IBAN KOPYALA
                    </button>
                </div>
            @empty
                <div class="bg-gray-50 rounded-lg border border-dashed border-gray-300 p-8 text-center">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    <p class="text-sm text-gray-500">Henüz banka hesabı tanımlanmamış.</p>
                    <p class="text-xs text-gray-400 mt-1">Lütfen yöneticinizle iletişime geçin.</p>
                </div>
            @endforelse
        </div>

        {{-- Sağ: Ödeme Bildirimi Formu --}}
        <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-800">Ödeme Bildirimi Formu</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <input wire:model="senderName" type="text"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        @error('senderName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="relative">
                        <select wire:model="bank"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                            <option value="">Banka Seçiniz</option>
                            @foreach($bankAccounts as $acc)
                                <option value="{{ $acc['bank_name'] }}">{{ $acc['bank_name'] }} — {{ $acc['account_name'] }}</option>
                            @endforeach
                        </select>
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Banka Seçiniz</label>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    @error('bank') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                    <div>
                        <input wire:model="amount" type="number" placeholder="Yatırılan Tutar (₺)"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="submitNotification" wire:loading.attr="disabled"
                            class="w-full px-5 py-2.5 bg-[#2563eb] text-white rounded text-[12px] font-bold hover:bg-[#1d4ed8] transition-colors tracking-wide disabled:opacity-60">
                        <span wire:loading.remove wire:target="submitNotification">ÖDEME BİLDİRİMİ YAP ➜</span>
                        <span wire:loading wire:target="submitNotification">Gönderiliyor...</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
