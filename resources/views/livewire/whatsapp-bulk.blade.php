<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Gruplara WhatsApp Mesaj</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-green-600 border-b-2 border-green-600 inline-block pb-1">Gruplara Mesaj Gönder</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Banner --}}
        <div class="p-4 bg-gradient-to-r from-green-500 to-green-600 text-white text-[12px] leading-relaxed flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
            <div>
                <strong>WhatsApp Business API</strong><br>
                WhatsApp üzerinden toplu mesaj göndermek için rehberinizdeki grupları kullanabilirsiniz. Mesajlarınız WhatsApp Business API üzerinden iletilecektir.
            </div>
        </div>

        <div class="p-5">
            <form wire:submit="send">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Sol: Grup seçimi ve ayarlar --}}
                    <div class="space-y-4">
                        {{-- Grup --}}
                        <div class="relative">
                            <select wire:model="groupId" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none bg-gray-50/50 transition-all">
                                <option value="">Grup Seçiniz</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->contacts_count }} kişi)</option>
                                @endforeach
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1.5 text-[10px] text-gray-400 font-medium">* Hedef Grup</label>
                            <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        @error('groupId') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                        {{-- Gönderim Hızı --}}
                        <x-speed-selector wire-model="sendSpeed" />

                        {{-- Gönderen Numara --}}
                        <div class="relative">
                            <select wire:model="sessionId" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none bg-gray-50/50 transition-all">
                                <option value="">Numara Seçiniz</option>
                                @foreach($sessions as $s)
                                    <option value="{{ $s->id }}">{{ $s->phone_number }} — {{ $s->display_name }} {{ $s->is_default ? '(Varsayılan)' : '' }}</option>
                                @endforeach
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1.5 text-[10px] text-gray-400 font-medium">* Gönderen Numara</label>
                            <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        @if($sessions->isEmpty())
                        <p class="text-[11px] text-amber-600"><a href="{{ route('panel.whatsapp.setup') }}" class="underline font-medium">WhatsApp Kurulum</a> sayfasından numara bağlayın.</p>
                        @endif

                        {{-- Kalan Kredi --}}
                        <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-green-700 font-medium">Kalan WhatsApp Kredi</span>
                                <span class="text-sm font-bold text-green-800">{{ number_format(auth()->user()->whatsapp_credits ?? 0) }}</span>
                            </div>
                        </div>

                        {{-- Günlük Limit --}}
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-blue-700 font-medium">Günlük Gönderim</span>
                                <span class="text-[11px] text-blue-600 font-semibold">{{ $dailyStats['today'] }}/{{ $dailyStats['daily_limit'] }}</span>
                            </div>
                            <div class="w-full bg-blue-100 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{ $dailyStats['percentage'] }}%"></div>
                            </div>
                            <p class="text-[10px] text-blue-500 mt-1">Kalan: {{ $dailyStats['remaining'] }} mesaj</p>
                        </div>

                        {{-- Spam Uyarısı --}}
                        @if(!empty($spamWarnings))
                        <div class="p-3 bg-red-50 rounded-xl border border-red-200 text-[11px] text-red-700">
                            <div class="flex items-center gap-1.5 mb-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                <strong>Spam Uyarısı!</strong>
                            </div>
                            <p>Mesajınız spam olarak işaretlenebilir: <span class="font-semibold">{{ implode(', ', $spamWarnings) }}</span></p>
                        </div>
                        @endif

                        {{-- Değişken Bilgisi --}}
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-[11px] text-amber-700">
                            <strong>İpucu:</strong> Kişiselleştirme için <code class="bg-amber-100 px-1 rounded">[isim]</code> ve <code class="bg-amber-100 px-1 rounded">[soyisim]</code> değişkenlerini kullanabilirsiniz.
                        </div>
                    </div>

                    {{-- Sağ: Mesaj --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5 font-medium">Mesajınızı bu alana giriniz</label>
                            <textarea wire:model.live="message" rows="10" placeholder="WhatsApp mesajınızı yazın..." class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm resize-y bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all"></textarea>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-gray-400">
                            <span>{{ $charCount }} karakter</span>
                        </div>
                        @error('message') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Gönder butonu --}}
                <div class="mt-5">
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 flex items-center justify-center gap-2 tracking-wider shadow-lg shadow-green-500/25 hover:shadow-xl hover:shadow-green-500/35 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        MESAJI GÖNDER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
