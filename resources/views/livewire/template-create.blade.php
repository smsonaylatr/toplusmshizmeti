<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Şablon Oluştur</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 text-center">
                <h2 class="text-base font-semibold text-gray-800">Yeni Mesaj Şablonu Kaydet</h2>
            </div>
            <div class="p-6">
                {{-- Template Name --}}
                <div class="mb-4">
                    <div class="relative">
                        <input wire:model="name" type="text" placeholder="Şablon Adı"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    </div>
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Variable Buttons --}}
                <div class="flex items-center gap-2 mb-3">
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
                <div class="mb-4">
                    <div class="relative">
                        <textarea wire:model="content" rows="10"
                                  placeholder="Mesajınız"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Mesajınız</label>
                    </div>
                    @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Submit Button --}}
                <button wire:click="save" class="px-5 py-2 border border-gray-400 rounded text-[12px] text-gray-700 hover:bg-gray-50 transition-colors font-bold tracking-wide">
                    ŞABLONU OLUŞTUR
                </button>
            </div>
        </div>
    </div>
</div>
