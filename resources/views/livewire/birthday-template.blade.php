<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Doğumgünü şablonu</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Yeni Doğum günü Şablonu Kaydet</h2>
                <button wire:click="removeTemplate"
                        class="text-[12px] font-bold text-gray-600 hover:text-red-500 tracking-wide transition-colors">
                    ŞABLONU KALDIR
                </button>
            </div>
            <div class="p-6 space-y-4">
                {{-- Gönderim Platformu --}}
                <div class="relative">
                    <select wire:model="platform"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                        <option value="SMS">SMS</option>
                        <option value="WhatsApp">WhatsApp</option>
                    </select>
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderim Platformu</label>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>

                {{-- Dil --}}
                <div class="relative">
                    <select wire:model="language"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                        <option value="turkce">turkce</option>
                        <option value="english">english</option>
                        <option value="arabic">arabic</option>
                    </select>
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Dil</label>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>

                {{-- Gönderici Adı --}}
                <div class="relative">
                    <select wire:model="senderName"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                        <option value="">Gönderici Adı</option>
                    </select>
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Gönderici Adı</label>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>

                {{-- Variable Buttons --}}
                <div class="flex items-center gap-2">
                    <button wire:click="insertVariable('isim')" type="button"
                            class="px-4 py-1.5 border-2 border-[#2563eb] text-[#2563eb] rounded text-sm font-semibold hover:bg-[#2563eb] hover:text-white transition-colors">
                        İSİM
                    </button>
                    <button wire:click="insertVariable('soyisim')" type="button"
                            class="px-4 py-1.5 border-2 border-[#2563eb] text-[#2563eb] rounded text-sm font-semibold hover:bg-[#2563eb] hover:text-white transition-colors">
                        SOYİSİM
                    </button>
                </div>

                {{-- Message Content --}}
                <div class="relative">
                    <textarea wire:model="content" rows="8"
                              placeholder="Mesajınız"
                              class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Mesajınız</label>
                </div>

                {{-- Submit --}}
                <button wire:click="save" class="px-5 py-2 border border-gray-400 rounded text-[12px] text-gray-700 hover:bg-gray-50 transition-colors font-bold tracking-wide">
                    ŞABLONU KAYDET
                </button>
            </div>
        </div>
    </div>
</div>
