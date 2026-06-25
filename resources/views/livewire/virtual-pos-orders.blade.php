<div>
    <div class="flex items-center justify-between mb-1">
        <h1 class="text-2xl font-bold text-gray-800">Siparişlerim</h1>
        <a href="{{ route('panel.pricing.index') }}"
           class="px-4 py-2 bg-[#2563eb] text-white text-sm font-semibold rounded-lg hover:bg-[#1d4ed8] transition-colors">
            + Kredi Satın Al
        </a>
    </div>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Sanal POS Sipariş Geçmişi</h2></div>

    {{-- Filtreler --}}
    <div class="flex gap-2 mb-4">
        @foreach([''=>'Tümü','paid'=>'Ödendi','pending'=>'Bekliyor','failed'=>'Başarısız','cancelled'=>'İptal'] as $val => $label)
            <button wire:click="$set('statusFilter','{{ $val }}')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                           {{ $statusFilter === $val ? 'bg-[#2563eb] text-white border-[#2563eb]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2563eb]' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-gray-500 text-sm">Henüz sipariş bulunmuyor.</p>
            <a href="{{ route('panel.pricing.index') }}" class="mt-4 inline-block px-4 py-2 bg-[#2563eb] text-white text-sm font-semibold rounded-lg hover:bg-[#1d4ed8]">
                Kredi Satın Al
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600">Sipariş No</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600">Paket</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-600">Tutar</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-600">Durum</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-600">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $order->merchant_oid }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800">{{ $order->package_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->sms_amount_formatted }} SMS</div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                {{ number_format($order->total_amount, 2, ',', '.') }} ₺
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $colors = [
                                        'paid'      => 'bg-green-100 text-green-700',
                                        'pending'   => 'bg-amber-100 text-amber-700',
                                        'failed'    => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-100 text-gray-600',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-xs text-gray-500">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($orders->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
