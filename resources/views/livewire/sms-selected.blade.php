<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Seçili Kayıtlara SMS</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Seçili Kayıtlara SMS Gönder</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        @if($step === 1)
        {{-- ADIM 1: Kişi Seçimi --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Sol: Gruplar --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-gray-700">Gruplar</h3>
                </div>
                <div class="p-3">
                    <input wire:model.live="searchGroup" type="text" placeholder="Grup ara..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm mb-3 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    <div class="space-y-1 max-h-[300px] overflow-y-auto">
                        @forelse($groups as $group)
                            <button wire:click="$set('selectedGroup', '{{ $group->id }}')"
                                    class="w-full text-left px-3 py-2 rounded text-sm transition-colors {{ $selectedGroup == $group->id ? 'bg-[#2563eb] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="flex items-center justify-between">
                                    {{ $group->name }}
                                    <span class="text-[10px] {{ $selectedGroup == $group->id ? 'text-white/70' : 'text-gray-400' }}">{{ $group->contacts_count }}</span>
                                </span>
                            </button>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">Grup bulunamadı</p>
                        @endforelse
                    </div>
                    @if($selectedGroup)
                        <button wire:click="selectAllFromGroup" class="w-full mt-2 px-3 py-2 bg-blue-50 text-blue-600 text-xs font-medium rounded hover:bg-blue-100 transition-colors">TÜMÜNÜ SEÇ</button>
                    @endif
                </div>
            </div>

            {{-- Orta: Kayıtlar --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-gray-700">Kayıtlar</h3>
                </div>
                <div class="p-3">
                    <input wire:model.live="searchContact" type="text" placeholder="Kayıt ara..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm mb-3 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    <div class="space-y-1 max-h-[300px] overflow-y-auto">
                        @if($selectedGroup)
                            @forelse($contacts as $contact)
                                <button wire:click="selectContact({{ $contact->id }})"
                                        class="w-full text-left px-3 py-2 rounded text-sm transition-colors {{ in_array($contact->id, $selectedContacts) ? 'bg-green-50 text-green-700 border border-green-200' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span class="flex items-center justify-between">
                                        <span>{{ $contact->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $contact->phone }}</span>
                                    </span>
                                </button>
                            @empty
                                <p class="text-sm text-gray-400 text-center py-4">Bu grupta kayıt yok</p>
                            @endforelse
                        @else
                            <p class="text-sm text-gray-400 text-center py-4">Bir grup seçin</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sağ: Seçilenler --}}
            <div class="border border-gray-200 rounded-lg">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-lg flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Seçilenler</h3>
                    <span class="text-xs bg-[#2563eb] text-white px-2 py-0.5 rounded-full font-bold">{{ count($selectedContacts) }}</span>
                </div>
                <div class="p-3">
                    <div class="space-y-1 max-h-[300px] overflow-y-auto">
                        @forelse($selectedContactsData as $contact)
                            <div class="flex items-center justify-between px-3 py-2 rounded text-sm bg-green-50 border border-green-100">
                                <span class="text-gray-700">{{ $contact->name }} <span class="text-[10px] text-gray-400">{{ $contact->phone }}</span></span>
                                <button wire:click="removeContact({{ $contact->id }})" class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">Henüz kayıt seçilmedi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @error('selectedContacts') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror

        <div class="mt-4">
            <button wire:click="goToCompose" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                MESAJI OLUŞTUR
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>

        @else
        {{-- ADIM 2: Mesaj Yazma --}}
        <div class="max-w-2xl mx-auto space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs bg-[#2563eb] text-white px-2 py-0.5 rounded-full font-bold">{{ count($selectedContacts) }}</span>
                <span class="text-sm text-gray-600">kişi seçildi</span>
            </div>

            {{-- HATIRLATMA kutusu --}}
            <div class="bg-[#2563eb] text-white rounded-lg px-4 py-3 flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <span class="font-bold text-sm">HATIRLATMA!</span>
                    <p class="text-[11px] mt-1 text-white/90">Reklam amaçlı SMS gönderimleri yasaklanmıştır. Alıcının onayı olmadan SMS gönderilmemelidir.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
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
                    @error('senderName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="relative">
                    <select wire:model="smsType" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                        <option>Normal</option><option>Flash</option><option>Türkçe</option>
                    </select>
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* SMS Tipi</label>
                </div>
            </div>

            <div class="relative">
                <textarea wire:model="message" rows="6" placeholder="Mesajınızı yazın..." class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm resize-y focus:outline-none focus:ring-1 focus:ring-[#2563eb]"></textarea>
                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Mesaj İçeriği</label>
                @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button wire:click="goBack" class="px-6 py-3 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 font-bold tracking-wider">◀ GERİ</button>
                <button wire:click="send" class="flex-1 py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                    MESAJI GÖNDER
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
