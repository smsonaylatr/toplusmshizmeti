<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">WhatsApp Raporları</h1>
    <div class="mb-5"><h2 class="text-base font-semibold text-green-600 border-b-2 border-green-600 inline-block pb-1">Mesaj Raporları</h2></div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total']) }}</p>
            <p class="text-[11px] text-gray-400 font-medium mt-1">TOPLAM</p>
        </div>
        <div class="bg-white rounded-2xl border border-green-100 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['delivered']) }}</p>
            <p class="text-[11px] text-green-500 font-medium mt-1">İLETİLDİ</p>
        </div>
        <div class="bg-white rounded-2xl border border-amber-100 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['pending']) }}</p>
            <p class="text-[11px] text-amber-500 font-medium mt-1">BEKLEMEDE</p>
        </div>
        <div class="bg-white rounded-2xl border border-red-100 p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
            <p class="text-[11px] text-red-500 font-medium mt-1">BAŞARISIZ</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
        <div class="p-4 flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Numara veya mesaj ara..." class="w-full px-3.5 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
            </div>
            <select wire:model.live="statusFilter" class="px-3.5 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 appearance-none transition-all">
                <option value="">Tüm Durumlar</option>
                <option value="pending">Beklemede</option>
                <option value="sent">Gönderildi</option>
                <option value="delivered">İletildi</option>
                <option value="failed">Başarısız</option>
            </select>
            <input wire:model.live="dateFrom" type="date" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
            <input wire:model.live="dateTo" type="date" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
            <button wire:click="clearFilters" class="px-4 py-2 text-xs font-medium text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Temizle</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase">Alıcı</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase">Mesaj</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase">Durum</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-500 uppercase">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($messages as $msg)
                    <tr class="hover:bg-green-50/30 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-700 font-medium">{{ $msg->recipient }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600 max-w-xs truncate">{{ $msg->message }}</td>
                        <td class="px-5 py-3">
                            @switch($msg->status)
                                @case('delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">İletildi</span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Beklemede</span>
                                    @break
                                @case('sent')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">Gönderildi</span>
                                    @break
                                @case('failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Başarısız</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-400">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-green-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                            </div>
                            <p class="text-sm text-gray-400">Henüz WhatsApp mesajı bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
