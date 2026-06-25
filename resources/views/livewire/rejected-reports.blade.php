<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Ret Raporları</h1>

    {{-- Section Title with orange underline --}}
    <div class="mb-4">
        <h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Ret Raporları</h2>
    </div>

    <div class="flex flex-col lg:flex-row gap-4">
        {{-- Left: Filters --}}
        <div class="w-full lg:w-72 shrink-0">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                {{-- Başlangıç Tarihi --}}
                <div class="mb-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <input wire:model.live="dateFrom" type="date"
                               class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Başlangıç Tarihi</label>
                    </div>
                </div>

                {{-- Bitiş Tarihi --}}
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <input wire:model.live="dateTo" type="date"
                               class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Bitiş Tarihi</label>
                    </div>
                </div>

                {{-- Checkbox --}}
                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input wire:model.live="futureOnly" type="checkbox" class="rounded border-gray-300 text-[#2563eb] focus:ring-[#2563eb]">
                        Sadece ileri tarihli gönderimler göster
                    </label>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button wire:click="clearFilters" class="px-4 py-2 border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-50 transition-colors font-medium">
                        SEÇİMİ TEMİZLE
                    </button>
                    <button class="px-4 py-2 bg-[#2563eb] text-white rounded text-xs font-bold hover:bg-[#1d4ed8] transition-colors">
                        RAPORLARI YÜKLE
                    </button>
                </div>
            </div>
        </div>

        {{-- Right: Table --}}
        <div class="flex-1 min-w-0">
            {{-- Warning Banner --}}
            <div class="bg-[#fff3cd] border border-[#ffc107] rounded-lg p-3 mb-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#1d4ed8] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2v-2zm0-6h2v4h-2v-4z"/></svg>
                <p class="text-sm text-[#856404]">Okunma durumu linkiniz aktif değil. Aktivasyon için müşteri temsilcinize ulaşın.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500 bg-gray-50">
                                <th class="text-left py-3 px-4 font-semibold text-xs">Tarih</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Gruplar</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Mesaj</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Durumu</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Gönderim Öncesi Krediniz</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Toplam Gönderilen</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Okundu</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">İşlem</th>
                                <th class="text-left py-3 px-4 font-semibold text-xs">Gönderen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($messages as $msg)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 text-xs text-gray-500 whitespace-nowrap">
                                        {{ $msg->created_at->format('d M Y') }}<br>
                                        <span class="text-gray-400">{{ $msg->created_at->translatedFormat('l') }}</span><br>
                                        <span class="text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">—</td>
                                    <td class="py-3 px-4 text-xs text-gray-700 max-w-[250px]">
                                        <p class="line-clamp-3">{{ $msg->message }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-[11px] font-medium bg-red-100 text-red-700">
                                            Ret
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">—</td>
                                    <td class="py-3 px-4 text-xs text-gray-500">—</td>
                                    <td class="py-3 px-4 text-xs text-gray-400">—</td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-1">
                                            <button class="px-3 py-1 border border-gray-300 rounded text-[11px] text-gray-600 hover:bg-gray-50 font-medium">DETAY</button>
                                            <button class="px-3 py-1 border border-green-400 rounded text-[11px] text-green-600 hover:bg-green-50 font-medium">EXCEL</button>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-xs">
                                        <span class="text-gray-700 font-medium">{{ auth()->user()->name ?? 'KULLANICI' }}</span><br>
                                        <span class="text-[10px] text-blue-600 bg-amber-50 px-1.5 py-0.5 rounded mt-0.5 inline-block">Ana Kullanıcı</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-10 text-center">
                                        <p class="text-gray-400 text-sm">Bu görünümde veri yok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($messages->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-center gap-1">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
