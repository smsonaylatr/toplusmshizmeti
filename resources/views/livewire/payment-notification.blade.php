<div>
    {{-- Header --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ödeme Bildirimleri</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gönderdiğiniz havale/EFT bildirimlerinin durumları</p>
        </div>
        <a href="{{ route('panel.bank.accounts') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-[#1d4ed8] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Bildirim Gönder
        </a>
    </div>

    {{-- İstatistik Kartları --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        <button wire:click="$set('statusFilter', '')"
                class="rounded-xl p-4 text-left border transition-all {{ $statusFilter === '' ? 'border-[#2563eb] bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
            <p class="text-xs text-gray-500 mb-1">Tümü</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totals['all'] }}</p>
        </button>
        <button wire:click="$set('statusFilter', 'pending')"
                class="rounded-xl p-4 text-left border transition-all {{ $statusFilter === 'pending' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
            <p class="text-xs text-gray-500 mb-1">Bekliyor</p>
            <p class="text-2xl font-bold text-amber-500">{{ $totals['pending'] }}</p>
        </button>
        <button wire:click="$set('statusFilter', 'approved')"
                class="rounded-xl p-4 text-left border transition-all {{ $statusFilter === 'approved' ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
            <p class="text-xs text-gray-500 mb-1">Onaylandı</p>
            <p class="text-2xl font-bold text-green-600">{{ $totals['approved'] }}</p>
        </button>
        <button wire:click="$set('statusFilter', 'rejected')"
                class="rounded-xl p-4 text-left border transition-all {{ $statusFilter === 'rejected' ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
            <p class="text-xs text-gray-500 mb-1">Reddedildi</p>
            <p class="text-2xl font-bold text-red-500">{{ $totals['rejected'] }}</p>
        </button>
    </div>

    {{-- Tablo --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @if($notifications->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm text-gray-500">Kayıt bulunamadı.</p>
                <a href="{{ route('panel.bank.accounts') }}"
                   class="mt-3 inline-block text-xs text-[#2563eb] hover:underline font-medium">
                    İlk bildirimi gönder →
                </a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Gönderici / Banka</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tutar</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tarih</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Durum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($notifications as $n)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-gray-800 text-[13px]">{{ $n->sender_name }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $n->bank }} · {{ $n->phone }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="font-bold text-gray-800">{{ number_format($n->amount, 2, ',', '.') }} ₺</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[13px] text-gray-600">{{ $n->payment_date?->format('d.m.Y') ?? '—' }}</span>
                                <p class="text-[11px] text-gray-400 mt-0.5">Bildirim: {{ $n->created_at->format('d.m.Y H:i') }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($n->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Onaylandı
                                    </span>
                                @elseif($n->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-600">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        Reddedildi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-600">
                                        <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        İnceleniyor
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($notifications->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Bilgi --}}
    <p class="text-xs text-gray-400 mt-4 text-center">
        Ödeme onayları genellikle iş günü içinde işlenir. Sorun yaşarsanız destek ekibiyle iletişime geçin.
    </p>
</div>
