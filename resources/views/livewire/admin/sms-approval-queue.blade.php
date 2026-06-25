<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">SMS Onay Kuyruğu</h1>
            <p class="text-sm text-gray-500 mt-0.5">Evrak onayı olmayan kullanıcıların SMS istekleri</p>
        </div>
        @if($pendingCount > 0)
            <span class="px-3 py-1.5 bg-amber-500/20 text-amber-400 text-sm font-bold rounded-full border border-amber-500/30">
                {{ $pendingCount }} Bekliyor
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="flash-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-danger mb-4">{{ session('error') }}</div>
    @endif

    {{-- Reddetme Modalı --}}
    @if($rejectingId)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-[#1a1d2e] border border-red-500/30 rounded-2xl p-6 w-96 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold">SMS Reddet</h3>
                    <p class="text-gray-500 text-sm">Reddetme sebebi girin (isteğe bağlı)</p>
                </div>
            </div>
            <textarea wire:model="rejectReason" rows="3"
                      placeholder="Reddetme sebebi..." class="admin-input resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button wire:click="cancelReject" class="flex-1 py-2 rounded-lg border border-gray-600 text-gray-300 text-sm hover:bg-white/5 transition-colors">İptal</button>
                <button wire:click="confirmReject" class="flex-1 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-bold transition-colors">Reddet</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Filtre --}}
    <div class="glass-card p-4 mb-4">
        <div class="flex gap-2">
            @foreach(['pending' => '⏳ Bekleyen', 'approved' => '✅ Onaylanan', 'rejected' => '❌ Reddedilen', '' => '🔄 Tümü'] as $val => $label)
            <button wire:click="$set('statusFilter', '{{ $val }}')"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === $val ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : 'text-gray-500 hover:text-gray-300 hover:bg-white/5' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Tablo --}}
    <div class="glass-card overflow-hidden">
        @if($items->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="w-12 h-12 text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500 font-medium">Bu filtrede kayıt yok.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="text-left text-gray-500 text-[11px] uppercase tracking-wide border-b" style="border-color:var(--admin-border)">
                        <th class="px-5 py-3">Kullanıcı</th>
                        <th class="px-5 py-3">Gönderici</th>
                        <th class="px-5 py-3">Mesaj</th>
                        <th class="px-5 py-3">Alıcı</th>
                        <th class="px-5 py-3">Durum</th>
                        <th class="px-5 py-3">Tarih</th>
                        <th class="px-5 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:var(--admin-border)">
                    @foreach($items as $item)
                    <tr class="hover:bg-white/2 transition-colors">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-white">{{ $item->user->name }}</div>
                            <div class="text-gray-500 text-[11px]">{{ $item->user->email }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-mono text-indigo-400 text-[12px]">{{ $item->sender_name }}</span>
                        </td>
                        <td class="px-5 py-4 max-w-xs">
                            <p class="text-gray-300 truncate">{{ $item->message }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-semibold text-white">{{ number_format($item->recipient_count) }}</span>
                            <span class="text-gray-500 text-[11px]"> kişi</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($item->status === 'pending')
                                <span class="status-warning">⏳ Bekliyor</span>
                            @elseif($item->status === 'approved')
                                <span class="status-success">✅ Onaylandı</span>
                            @else
                                <span class="status-danger">❌ Reddedildi</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-[12px]">
                            {{ $item->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            @if($item->status === 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="approve({{ $item->id }})" wire:loading.attr="disabled"
                                        class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 text-[12px] font-semibold rounded-lg transition-colors border border-emerald-500/20">
                                    Onayla & Gönder
                                </button>
                                <button wire:click="startReject({{ $item->id }})"
                                        class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 text-[12px] font-semibold rounded-lg transition-colors border border-red-500/20">
                                    Reddet
                                </button>
                            </div>
                            @elseif($item->status === 'rejected' && $item->reject_reason)
                                <span class="text-gray-600 text-[11px] italic">{{ Str::limit($item->reject_reason, 30) }}</span>
                            @else
                                <span class="text-gray-700">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t" style="border-color:var(--admin-border)">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
