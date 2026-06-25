<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Toplu Numaralara SMS</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Numaralara SMS Gönder</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- HATIRLATMA Banner --}}
        <div class="p-4 bg-[#2563eb] rounded-t-lg text-white text-[12px] leading-relaxed flex items-start gap-3">
            <span class="text-xl font-bold mt-0.5">❕</span>
            <div>
                <strong>HATIRLATMA!</strong><br>
                Ticari SMS içeriklerinde; firmalar için Mersis No, şahıs işletmelerinde ise; Ad Soyad ve TC.Kimlik Numarası, telefon veya email gibi iletişim bilgisinin RET Hizmeti ile birlikte bulunması önem teşkil etmektedir.
            </div>
        </div>

        <div class="p-5">
        <form @submit.prevent="
                async function() {
                    const nums = document.getElementById('smsSendNums');
                    const msg  = document.getElementById('smsSendMsg');
                    if (nums) await $wire.setCustomNumbers(nums.value);
                    if (msg)  await $wire.setMessage(msg.value);
                    $wire.send();
                }()">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- SOL KOLON: Tabs + Numaralar Textarea --}}
                    <div class="space-y-3">
                        {{-- İPTAL LİNKİ / KISA KOD tabs --}}
                        <div class="flex border-b border-gray-200 mb-2">
                            <button type="button" class="px-4 py-2 text-xs font-medium text-gray-400 border-b-2 border-transparent hover:text-gray-600">İPTAL LİNKİ</button>
                            <button type="button" class="px-4 py-2 text-xs font-medium text-gray-400 border-b-2 border-transparent hover:text-gray-600">KISA KOD</button>
                        </div>

                        {{-- Numaralar Textarea --}}
                        <div class="relative">
                            <textarea id="smsSendNums" rows="18" placeholder="Her satıra bir numara girin..." x-data x-init="$el.value = $wire.customNumbers" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Numaralar</label>
                            @error('customNumbers') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- SAĞ KOLON: Ayarlar + Mesaj --}}
                    <div class="space-y-3">
                        {{-- Gönderici Adları --}}
                        <div class="relative">
                            <select wire:model="senderName" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option value="">-- Gönderici Seçiniz --</option>
                                @forelse($senders ?? [] as $id => $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @empty
                                    <option value="" disabled>Onaylı gönderici başlığınız yok</option>
                                @endforelse
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderici Adları</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            @error('senderName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Şablon - client-side — anlık --}}
                        @php $smsSendTemplateMap = ($templates ?? collect())->pluck('content','id')->toJson(); @endphp
                        <div class="relative" x-data="{ tpls: {{ $smsSendTemplateMap }} }">
                            <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white"
                                    @change="const c=tpls[$event.target.value];if(c){document.getElementById('smsSendMsg').value=c;document.getElementById('smsSendMsg').dispatchEvent(new Event('input'));$wire.set('message',c,false);}">
                                <option value="">Şablon Seciniz</option>
                                @foreach($templates ?? [] as $tpl)
                                    <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- Tür --}}
                        <div class="relative">
                            <select wire:model="smsType" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option>Normal</option>
                                <option>Flash</option>
                                <option>Türkçe</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Tür</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- Gönderim Zamanı --}}
                        <div class="relative">
                            <select wire:model="sendTime" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option>Hemen Gönder</option>
                                <option>Zamanla</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderim Zamanı</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- İleti Türü --}}
                        <div class="relative">
                            <select wire:model="messageType" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option>Bilgi</option>
                                <option>Ticari</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">İleti Türü</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- Karakter Sayacı - Alpine ile --}}
                        <div x-data="{cc:0,get sc(){return this.cc>0?Math.ceil(this.cc/160):1}}">
                            <p class="text-xs text-gray-500 mb-1" x-text="sc+' SMS / '+cc+' Karakter'">1 SMS / 0 Karakter</p>
                            <div class="relative">
                                <textarea id="smsSendMsg"
                                          x-ref="ta"
                                          @input="cc=$el.value.length"
                                          @blur="$wire.set('message',$el.value,false)"
                                          rows="6" placeholder="Mesajınızı yazın..."
                                          class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Mesajınız</label>
                                @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alt: SON 5 MESAJ + MESAJI GÖNDER --}}
                <div class="mt-5 space-y-3">
                    <button type="button" class="px-8 py-2.5 bg-[#2563eb] text-white text-xs font-bold rounded hover:bg-[#1d4ed8] transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        SON 5 MESAJ
                    </button>

                    <button type="submit" wire:loading.attr="disabled" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg disabled:opacity-50">
                        MESAJI GÖNDER
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
