<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Özel Excel ile SMS</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Özel Excel İle SMS Gönder</h2></div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 bg-[#2563eb] rounded-t-lg text-white text-[12px] leading-relaxed">
            <strong>HATIRLATMA!</strong><br>
            Ticari SMS içeriklerinde; firmalar için Mersis No, şahıs işletmelerinde ise; Ad Soyad ve TC.Kimlik Numarası, telefon veya email gibi iletişim bilgisinin RET Hizmeti ile birlikte bulunması önem teşkil etmektedir.
        </div>
        <div class="p-5">
            {{-- Steps --}}
            <div class="flex items-center justify-center gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full {{ $step === 1 ? 'bg-[#2563eb] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center text-sm font-bold">1</span>
                    <span class="text-sm {{ $step === 1 ? 'text-[#2563eb] font-semibold' : 'text-gray-400' }}">Excel Dosyası Seç</span>
                </div>
                <div class="w-12 h-px bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full {{ $step === 2 ? 'bg-[#2563eb] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center text-sm font-bold">2</span>
                    <span class="text-sm {{ $step === 2 ? 'text-[#2563eb] font-semibold' : 'text-gray-400' }}">Sütunları Eşleştir & Gönder</span>
                </div>
            </div>

            @if($step === 1)
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <div class="flex-1">
                        <input type="file" accept=".xlsx,.xls,.csv" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded p-3 text-[12px] text-amber-700">
                    <strong>DİKKAT:</strong> Excel dosyanızdaki ilk sütun telefon numarası olmalıdır. Diğer sütunlar (İsim, Borç vb.) mesaj içinde değişken olarak kullanılabilir.
                </div>

                <div class="flex justify-end gap-3">
                    <button class="px-5 py-2.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        ÖRNEK EXCEL
                    </button>
                    <button wire:click="$set('step', 2)" class="px-6 py-2.5 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-colors">DEVAM ET ▶</button>
                </div>
            </div>
            @else
            <div class="space-y-4">
                <p class="text-sm text-gray-500">Excel sütunlarını mesaj değişkenleriyle eşleştirin:</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                            <option>Sütun A — Telefon</option>
                        </select>
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Telefon Sütunu</label>
                    </div>
                    <div class="relative">
                        <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                            <option>08507063457</option>
                        </select>
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderici Adı</label>
                    </div>
                </div>
                <div>
                    <textarea rows="6" placeholder="Mesajınızı yazın... Dinamik alanlar için {SUTUN_ADI} kullanın." class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                </div>
                <div class="flex justify-between">
                    <button wire:click="$set('step', 1)" class="px-5 py-2.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">◀ GERİ</button>
                    <button type="submit" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                        MESAJI GÖNDER
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
