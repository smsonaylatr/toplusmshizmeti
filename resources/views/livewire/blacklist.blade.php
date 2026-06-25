<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Karaliste</h1>

    <div class="flex flex-col lg:flex-row gap-4">
        {{-- Left: Number Input --}}
        <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-[#2563eb]">
                    <h3 class="text-sm font-semibold text-[#2563eb]">Numara Engelleme /Kaldırma</h3>
                </div>
                <div class="p-4">
                    <textarea wire:model="numbersInput"
                              rows="12"
                              placeholder="İşlem yapılacak numaralar"
                              class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>

                    <div class="flex items-center gap-3 mt-3">
                        <button wire:click="unblockNumbers" class="px-4 py-2 border border-gray-400 rounded text-[11px] text-gray-700 hover:bg-gray-50 transition-colors font-bold tracking-wide">
                            NUMARALARIN ENGELLERİNİ KALDIR
                        </button>
                        <button wire:click="blockNumbers" class="px-4 py-2 border border-gray-400 rounded text-[11px] text-gray-700 hover:bg-gray-50 transition-colors font-bold tracking-wide">
                            NUMARALARI ENGELLE
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Blocked Numbers List --}}
        <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-[#2563eb] flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Engelli Numaralar</h3>
                    <button class="text-[11px] font-bold text-gray-600 hover:text-[#2563eb] tracking-wide transition-colors">
                        EXCEL'E AKTAR
                    </button>
                </div>
                <div class="p-4">
                    <p class="text-[11px] text-[#2563eb] mb-3">*Numarayı Kopyalamak İçin Tıklayın</p>

                    <div class="max-h-[400px] overflow-y-auto divide-y divide-gray-100">
                        @forelse($blockedNumbers as $blocked)
                            <div class="flex items-center justify-between py-3 group hover:bg-gray-50 px-2 rounded cursor-pointer"
                                 x-data
                                 @click="navigator.clipboard.writeText('{{ $blocked->phone_number }}')">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <span class="text-sm text-gray-700 font-mono">{{ $blocked->phone_number }}</span>
                                </div>
                                <div class="text-right flex items-center gap-3">
                                    <div>
                                        <p class="text-[10px] text-gray-400">Eklenme Tarihi:</p>
                                        <p class="text-[11px] text-[#2563eb]">{{ $blocked->created_at->translatedFormat('d F Y, l H:i') }}</p>
                                    </div>
                                    <button wire:click.stop="removeNumber({{ $blocked->id }})"
                                            class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-600 transition-all p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 text-sm">
                                Engelli numara bulunmuyor.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
