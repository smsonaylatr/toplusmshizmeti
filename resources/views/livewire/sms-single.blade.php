<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Tek Numaraya SMS</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Tek Numaraya SMS Gönder</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-700">{{ session('warning') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200"
         x-data="{ recentOpen: false, confirmOpen: false }">

        <div class="p-4 bg-[#2563eb] rounded-t-lg text-white text-[12px] leading-relaxed">
            <strong>HATIRLATMA!</strong><br>
            Ticari SMS içeriklerinde; firmalar için Mersis No, şahıs işletmelerinde ise; Ad Soyad ve TC.Kimlik Numarası, telefon veya email gibi iletişim bilgisinin RET Hizmeti ile birlikte bulunması önem teşkil etmektedir.
        </div>

        <div class="p-5">
            <form @submit.prevent="
                    async function() {
                        const ta = document.getElementById('smsSingleMsg');
                        if (ta) await $wire.setMessage(ta.value);
                        $wire.send();
                    }()">

                {{-- 2 KOLON GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- SOL KOLON --}}
                    <div class="space-y-3">

                        {{-- Telefon --}}
                        <div class="relative">
                            <input id="singlePhone" wire:model.blur="recipient" type="text"
                                   placeholder="0 (5XX) XXX XX XX" maxlength="15"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Alıcı Telefon Numarası</label>
                            @error('recipient') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Gönderici --}}
                        <div class="relative">
                            <select wire:model="senderName" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option value="">-- Gönderici Seçiniz --</option>
                                @forelse($senders as $sender)
                                    <option value="{{ $sender }}">{{ $sender }}</option>
                                @empty
                                    <option value="" disabled>Onaylı gönderici başlığınız yok</option>
                                @endforelse
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderici Adı</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            @error('senderName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tür --}}
                        <div class="relative">
                            <select wire:model="smsType" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option>Normal</option><option>İnteraktif</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Tür</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- Şablon — tamamen client-side, sunucu roundtrip YOK --}}
                        @php
                            $templateMap = $templates->pluck('content', 'id')->toJson();
                        @endphp
                        <div class="relative"
                             x-data="{ templates: {{ $templateMap }} }"
                             @template-select.window="
                                const content = templates[$event.detail];
                                if (!content) return;
                                const ta = document.getElementById('smsSingleMsg');
                                ta.value = content;
                                ta.dispatchEvent(new Event('input'));
                                @this.set('message', content, false);
                             ">
                            <select
                                class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white"
                                @change="
                                    const id = $event.target.value;
                                    const content = templates[id];
                                    if (!content) return;
                                    const ta = document.getElementById('smsSingleMsg');
                                    ta.value = content;
                                    ta.dispatchEvent(new Event('input'));
                                    @this.set('message', content, false);
                                ">
                                <option value="">Şablon Seçiniz</option>
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>


                        {{-- Gönderim Zamanı --}}
                        <div class="relative">
                            <select wire:model="sendTime" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option>Hemen Gönder</option><option>Zamanla</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderim Zamanı</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>

                        {{-- İleti Türü --}}
                        <div class="relative">
                            <select wire:model="messageType" required class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                <option value="Bilgi">Bilgi</option>
                                <option value="Ticari">Ticari</option>
                            </select>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* İleti Türü</label>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            @error('messageType') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Son 5 Mesaj Butonu --}}
                        <button type="button" @click="recentOpen = true"
                                class="px-7 py-1.5 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            SON 5 MESAJ
                        </button>

                    </div>{{-- /SOL KOLON --}}

                    {{-- SAĞ KOLON: Mesaj Textarea --}}
                    @php
                        $user             = auth()->user();
                        $shortCode        = $user->sms_short_code ?? '';
                        $cancelLink       = $user->sms_cancel_number ?? ''; // URL veya numara girilirse aktif
                        $kisaKodMetni     = $shortCode
                            ? "SMS iptali için {$shortCode} yazın 4609'a gönderin."
                            : '';
                        $iptalLinkiMetni  = $cancelLink
                            ? "SMS iptali için: {$cancelLink}"
                            : '';
                    @endphp
                    <div x-data="{
                        charCount: 0,
                        activeTab: 'iptal',
                        get smsCount() { return this.charCount > 0 ? Math.ceil(this.charCount / 160) : 1 },
                        insertText(text) {
                            const ta = document.getElementById('smsSingleMsg');
                            if (!ta || !text) return;
                            const start = ta.selectionStart;
                            const end   = ta.selectionEnd;
                            const before = ta.value.substring(0, start);
                            const after  = ta.value.substring(end);
                            ta.value = before + text + after;
                            ta.selectionStart = ta.selectionEnd = start + text.length;
                            ta.dispatchEvent(new Event('input'));
                            ta.focus();
                        }
                    }">

                        {{-- Tab butonları --}}
                        <div class="flex border-b border-gray-200 mb-3">
                            {{-- İPTAL LİNKİ: link girilmişse aktif, yoksa pasif --}}
                            <button type="button"
                                    @if($iptalLinkiMetni)
                                        @click="activeTab = 'iptal'; insertText({{ json_encode($iptalLinkiMetni) }})"
                                    @else
                                        disabled
                                    @endif
                                    :class="activeTab === 'iptal' && {{ $iptalLinkiMetni ? 'true' : 'false' }}
                                        ? 'text-[#2563eb] border-b-2 border-[#2563eb] -mb-px font-semibold'
                                        : '{{ $iptalLinkiMetni ? 'text-gray-400 hover:text-gray-600 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}'"
                                    class="px-4 py-2 text-xs transition-colors">
                                İPTAL LİNKİ
                            </button>
                            {{-- KISA KOD: kısa kod atanmışsa aktif, yoksa pasif --}}
                            <button type="button"
                                    @if($kisaKodMetni)
                                        @click="activeTab = 'kisak'; insertText({{ json_encode($kisaKodMetni) }})"
                                    @else
                                        disabled
                                    @endif
                                    :class="activeTab === 'kisak' && {{ $kisaKodMetni ? 'true' : 'false' }}
                                        ? 'text-[#2563eb] border-b-2 border-[#2563eb] -mb-px font-semibold'
                                        : '{{ $kisaKodMetni ? 'text-gray-400 hover:text-gray-600 cursor-pointer' : 'text-gray-300 cursor-not-allowed' }}'"
                                    class="px-4 py-2 text-xs transition-colors">
                                KISA KOD
                            </button>
                        </div>


                        <div class="relative">
                            <textarea id="smsSingleMsg"
                                      x-model="msgText"
                                      rows="16"
                                      placeholder="Mesajınızı bu alana giriniz"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"
                                      @input="charCount = $el.value.length"
                                      @blur="$wire.set('message', $el.value, false)"
                                      x-init="charCount = $el.value.length"></textarea>
                            <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Mesajınız</label>
                            @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <p class="mt-1 text-[11px] text-gray-400 text-right">
                            <span x-text="smsCount">1</span> SMS / <span x-text="charCount">0</span> Karakter
                        </p>
                    </div>{{-- /SAĞ KOLON --}}


                </div>{{-- /GRID --}}

                {{-- MESAJI GÖNDER BUTONU --}}
                <div class="mt-5">
                    <button type="button" @click="confirmOpen = true"
                            class="w-full py-3.5 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                        MESAJI GÖNDER
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </div>

                {{-- SON 5 MESAJ MODALI --}}
                <div x-show="recentOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[500] flex items-center justify-center p-4 bg-black/40"
                     @click.self="recentOpen = false"
                     x-cloak>
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                        <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-gray-800">Son 5 Mesaj</h3></div>
                        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            @forelse($recentMessages as $msg)
                                <div class="flex items-center justify-between px-6 py-4">
                                    <div class="flex-1 min-w-0 pr-4">
                                        <p class="text-[13px] text-gray-700 truncate">{{ $msg->message }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $msg->recipient }} &bull; {{ $msg->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <button type="button"
                                            @click="$wire.set('message', {{ json_encode($msg->message) }}); recentOpen = false"
                                            class="shrink-0 px-4 py-1.5 bg-green-500 hover:bg-green-600 text-white text-[12px] font-bold rounded transition-colors">SEÇ</button>
                                </div>
                            @empty
                                <div class="px-6 py-10 text-center"><p class="text-sm text-gray-400">Henüz gönderilen mesaj yok</p></div>
                            @endforelse
                        </div>
                        <div class="px-6 py-4 text-right border-t border-gray-100">
                            <button type="button" @click="recentOpen = false" class="text-sm font-bold text-red-500 hover:text-red-600">KAPAT</button>
                        </div>
                    </div>
                </div>

                {{-- GÖNDER ONAY MODALI --}}
                <div x-show="confirmOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[500] flex items-center justify-center p-4"
                     style="background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);"
                     @click.self="confirmOpen = false"
                     x-cloak>
                    <div class="w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden bg-white"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="scale-90 opacity-0"
                         x-transition:enter-end="scale-100 opacity-100">

                        {{-- Header --}}
                        <div class="px-6 py-5" style="background:linear-gradient(135deg,#1d4ed8,#2563eb);">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-[15px]">Gönderim Onayı</p>
                                    <p class="text-blue-200 text-[12px]">Lütfen bilgileri kontrol edin</p>
                                </div>
                            </div>
                        </div>

                        {{-- Özet --}}
                        <div class="px-6 py-4 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl p-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Telefon Sayısı</p>
                                    <p class="text-[14px] font-bold text-gray-800">1 Adet</p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Mesaj Türü</p>
                                    <p class="text-[14px] font-bold text-gray-800" x-text="$wire.smsType || 'Normal'"></p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">SMS Başlığı</p>
                                    <p class="text-[14px] font-bold text-gray-800 truncate" x-text="$wire.senderName || '-'"></p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">İleti Türü</p>
                                    <p class="text-[14px] font-bold text-gray-800" x-text="$wire.messageType || 'Bilgi'"></p>
                                </div>
                                <div class="rounded-xl p-3 col-span-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Gönderim Zamanı</p>
                                    <p class="text-[14px] font-bold text-gray-800">Hemen Gönderilecek</p>
                                </div>
                            </div>

                            <template x-if="$wire.messageType === 'Bilgi' || !$wire.messageType">
                                <div class="rounded-xl p-3.5 flex gap-2.5" style="background:#fff7ed;border:1px solid #fed7aa;">
                                    <span class="text-orange-500 font-black text-lg leading-none mt-0.5">!</span>
                                    <p class="text-[11px] text-orange-700 leading-relaxed">
                                        Bilgi mesajı seçildi. İYS izin durum sorgulaması yapılmayacaktır. Ticari faaliyetleriniz doğrultusunda mesaj göndermek istiyorsanız <strong>'Ticari'</strong> seçmelisiniz.
                                    </p>
                                </div>
                            </template>

                            <div class="rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
                                <div class="px-4 py-2" style="background:#f1f5f9;">
                                    <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Gönderim Önizleme</p>
                                </div>
                                <div class="grid grid-cols-4 px-4 py-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                    <p class="text-[10px] font-semibold text-gray-500">Cep Telefonu</p>
                                    <p class="text-[10px] font-semibold text-gray-500">Alıcı Adı</p>
                                    <p class="text-[10px] font-semibold text-gray-500">Mesaj</p>
                                    <p class="text-[10px] font-semibold text-gray-500">Karakter/Boy</p>
                                </div>
                                <div class="grid grid-cols-4 px-4 py-3">
                                    <p class="text-[12px] text-gray-700" x-text="$wire.recipient"></p>
                                    <p class="text-[12px] text-gray-400">—</p>
                                    <p class="text-[12px] text-gray-700 truncate" x-text="($wire.message || '').substring(0,25) + (($wire.message || '').length > 25 ? '...' : '')"></p>
                                    <p class="text-[12px] text-gray-600" x-text="'1 SMS / ' + ($wire.charCount || 0) + ' Karakter'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 flex gap-3 justify-end" style="border-top:1px solid #f1f5f9;">
                            <button type="button" @click="confirmOpen = false"
                                    class="px-6 py-2.5 rounded-xl font-bold text-[13px] flex items-center gap-2 transition-all"
                                    style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                İPTAL
                            </button>
                            <button type="submit" @click="confirmOpen = false" wire:loading.attr="disabled"
                                    class="px-6 py-2.5 rounded-xl font-bold text-[13px] flex items-center gap-2 transition-all disabled:opacity-50"
                                    style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;box-shadow:0 4px 14px rgba(22,163,74,.3);">
                                <span wire:loading.remove wire:target="send">GÖNDER</span>
                                <span wire:loading wire:target="send">Gönderiliyor...</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
(function () {
    function formatPhone(raw) {
        let digits = raw.replace(/\D/g, '');
        if (digits.length > 0 && digits[0] !== '0') digits = '0' + digits;
        digits = digits.substring(0, 11);
        if (digits.length <= 1) return digits;
        if (digits.length <= 4) return digits[0] + ' (' + digits.substring(1);
        if (digits.length <= 7) return digits[0] + ' (' + digits.substring(1, 4) + ') ' + digits.substring(4);
        if (digits.length <= 9) return digits[0] + ' (' + digits.substring(1, 4) + ') ' + digits.substring(4, 7) + ' ' + digits.substring(7);
        return digits[0] + ' (' + digits.substring(1, 4) + ') ' + digits.substring(4, 7) + ' ' + digits.substring(7, 9) + ' ' + digits.substring(9, 11);
    }

    function initPhone() {
        const input = document.getElementById('singlePhone');
        if (!input || input._phoneFormatted) return;
        input._phoneFormatted = true;
        input.addEventListener('input', function () {
            const formatted = formatPhone(this.value);
            this.value = formatted;
            const digits = formatted.replace(/\D/g, '');
            @this.set('recipient', digits);
        });
        if (input.value) input.value = formatPhone(input.value);
    }

    document.addEventListener('livewire:initialized', initPhone);
    document.addEventListener('livewire:navigated', initPhone);
    initPhone();
})();
</script>
