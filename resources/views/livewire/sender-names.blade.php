<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Gönderici Adları</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Gönderici Adları Listesi</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">

        {{-- Başlık & Ekle Butonu --}}
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Kayıtlı Gönderici Adları</h3>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="px-4 py-2 bg-[#2563eb] text-white text-xs font-bold rounded hover:bg-[#1d4ed8] transition-colors">
                    + YENİ GÖNDERİCİ EKLE
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50">
                    <form wire:submit="addSender">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Gönderici Adı (Max 11 karakter)</label>
                        <input wire:model="newSenderName" type="text" maxlength="11" placeholder="Örn: FIRMAADI"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm mb-2 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        @error('newSenderName') <p class="text-xs text-red-500 mb-2">{{ $message }}</p> @enderror
                        <button type="submit"
                                class="w-full py-2.5 bg-[#2563eb] text-white text-xs font-bold rounded hover:bg-[#1d4ed8] transition-all tracking-wider">
                            TALEP GÖNDER
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tablo --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                        <th class="text-left px-4 py-3 w-10">#</th>
                        <th class="text-left px-4 py-3">Gönderici Adı</th>
                        <th class="text-left px-4 py-3">Durum</th>
                        <th class="text-right px-4 py-3">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($senderNames as $index => $sender)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Sıra --}}
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $index + 1 }}</td>

                            {{-- Ad + Varsayılan rozeti --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-800">{{ $sender->name }}</span>
                                    @if($sender->is_default)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            ★ Varsayılan
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Durum --}}
                            <td class="px-4 py-3">
                                @if($sender->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">✓ Onaylandı</span>
                                @elseif($sender->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">✗ Reddedildi</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">⏳ Beklemede</span>
                                @endif
                            </td>

                            {{-- İşlem --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @if($sender->status === 'approved' && !$sender->is_default)
                                        <button wire:click="setDefault({{ $sender->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 transition-colors">
                                            ★ Varsayılan Yap
                                        </button>
                                    @endif
                                    <button wire:click="deleteSender({{ $sender->id }})"
                                            wire:confirm="Bu gönderici adını silmek istediğinize emin misiniz?"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors">
                                        Sil
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400 text-sm">
                                Henüz gönderici adı eklenmemiş
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 text-xs text-gray-400">
            Toplam {{ $senderNames->count() }} gönderici adı
        </div>
    </div>
</div>
