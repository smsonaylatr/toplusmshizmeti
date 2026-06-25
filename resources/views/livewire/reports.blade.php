<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Raporlar</h1>

    {{-- Section Title with orange underline --}}
    <div class="mb-4">
        <h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Raporlar</h2>
    </div>

    {{-- Warning Banner --}}
    <div class="bg-[#fff3cd] border border-[#ffc107] rounded-lg p-3 mb-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#1d4ed8] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2v-2zm0-6h2v4h-2v-4z"/></svg>
        <p class="text-sm text-[#856404]">Okunma durumu linkiniz aktif değil. Aktivasyon için müşteri temsilcinize ulaşın.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- Date Filters --}}
        <div class="p-4 border-b border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                <div>
                    <div class="relative">
                        <input wire:model.live="dateFrom" type="date" placeholder="Başlangıç Tarihi"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Başlangıç Tarihi</label>
                    </div>
                </div>
                <div>
                    <div class="relative">
                        <input wire:model.live="dateTo" type="date" placeholder="Bitiş Tarihi"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Bitiş Tarihi</label>
                    </div>
                </div>
            </div>

            {{-- Checkbox --}}
            <div class="mt-3">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" class="rounded border-gray-300 text-[#2563eb] focus:ring-[#2563eb]">
                    Sadece ileri tarihli gönderimler göster
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 mt-4">
                <button wire:click="clearFilters" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 transition-colors font-medium">
                    SEÇİMİ TEMİZLE
                </button>
                <button class="px-5 py-2 bg-[#2563eb] text-white rounded text-sm font-bold hover:bg-[#1d4ed8] transition-colors">
                    RAPORLARI YÜKLE
                </button>
            </div>
        </div>

        {{-- Report Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-500 bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-xs">Tarih</th>
                        <th class="text-left py-3 px-4 font-semibold text-xs">Gruplar</th>
                        <th class="text-left py-3 px-4 font-semibold text-xs">Mesaj</th>
                        <th class="text-left py-3 px-4 font-semibold text-xs">Durumu</th>
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
                            <td class="py-3 px-4 text-xs text-gray-700 max-w-[300px]">
                                <p class="line-clamp-3">{{ $msg->message }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-[11px] font-medium
                                    {{ in_array($msg->status, ['sent','delivered']) ? 'bg-green-100 text-green-700' : ($msg->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ in_array($msg->status, ['sent','delivered']) ? 'Gönderildi' : ($msg->status === 'failed' ? 'Başarısız' : 'Bekliyor') }}
                                </span>
                            </td>
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
                            <td colspan="7" class="py-10 text-center">
                                <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <p class="text-gray-400 text-sm">Rapor verisi bulunamadı</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
