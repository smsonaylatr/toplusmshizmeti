<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Evrak Onayları</h1><p class="text-sm text-gray-500 mt-0.5">Kullanıcı evrak başvurularını yönetin</p></div>
    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['pending' => '⏳ Bekleyen', 'approved' => '✅ Onaylı', 'rejected' => '❌ Reddedilen', '' => 'Tümü'] as $val => $label)
        <button wire:click="$set('statusFilter', '{{ $val }}')" class="px-4 py-2 text-[12px] font-medium rounded-xl transition-all {{ $statusFilter === $val ? 'btn-primary' : 'glass-card text-gray-400 hover:text-white' }}" style="{{ $statusFilter !== $val ? 'padding: 8px 16px;' : '' }}">{{ $label }}</button>
        @endforeach
    </div>
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead><tr><th class="text-left">Kullanıcı</th><th class="text-left">Tür</th><th class="text-left hidden sm:table-cell">Dosya</th><th class="text-center">Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>@forelse($documents as $doc)
                    <tr><td class="text-gray-300 text-[13px]">{{ $doc->user?->name }}</td><td class="text-gray-400 text-[13px]">{{ $doc->type }}</td><td class="hidden sm:table-cell"><a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 text-[12px] transition-colors">{{ $doc->original_name }}</a></td><td class="text-center"><span class="{{ $doc->status === 'approved' ? 'status-success' : ($doc->status === 'pending' ? 'status-warning' : 'status-danger') }}">{{ $doc->status }}</span></td>
                    <td class="text-right">@if($doc->status === 'pending')<button wire:click="approve({{ $doc->id }})" class="btn-success mr-1">Onayla</button><button wire:click="startReject({{ $doc->id }})" class="btn-danger">Reddet</button>@endif</td></tr>
                @empty<tr><td colspan="5"><div class="text-center py-10"><p class="text-sm text-gray-600">Evrak bulunamadı</p></div></td></tr>@endforelse</tbody>
            </table>
        </div>
        <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $documents->links() }}</div>
    </div>
    @if($rejectingId)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="glass-card p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-white mb-3">Evrak Reddet</h3>
            <textarea wire:model="rejectionReason" rows="3" placeholder="Red sebebi..." class="admin-input mb-3"></textarea>
            <div class="flex gap-2"><button wire:click="confirmReject" class="btn-danger flex-1" style="padding:10px">Reddet</button><button wire:click="$set('rejectingId', null)" class="glass-card flex-1 text-center text-gray-400 hover:text-white cursor-pointer" style="padding:10px;font-size:13px;">İptal</button></div>
        </div>
    </div>
    @endif
</div>
