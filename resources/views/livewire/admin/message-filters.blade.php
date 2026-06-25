<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Mesaj Filtreleri</h1><p class="text-sm text-gray-500 mt-0.5">BDK yasaklı kelime ve spam pattern yönetimi</p></div>
    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white mb-4">{{ $editingId ? '✏️ Filtre Düzenle' : '➕ Yeni Filtre' }}</h3>
            <form wire:submit="save" class="space-y-3">
                <div><label class="text-[12px] text-gray-500 mb-1 block">Pattern</label><input wire:model="pattern" type="text" class="admin-input" placeholder="yasaklı kelime veya /regex/">@error('pattern')<span class="text-red-400 text-[11px]">{{ $message }}</span>@enderror</div>
                <div><label class="text-[12px] text-gray-500 mb-1 block">Kategori</label><select wire:model="category" class="admin-select w-full"><option value="bdk">🏛️ BDK</option><option value="spam">📧 Spam</option><option value="fraud">🚨 Dolandırıcılık</option><option value="custom">⚙️ Özel</option></select></div>
                <div><label class="text-[12px] text-gray-500 mb-1 block">Ciddiyet</label><select wire:model="severity" class="admin-select w-full"><option value="low">🟢 Düşük</option><option value="medium">🟡 Orta</option><option value="high">🔴 Yüksek</option></select></div>
                <label class="flex items-center gap-2.5 text-[13px] text-gray-400 cursor-pointer p-2 rounded-xl" style="background: var(--admin-bg); border: 1px solid var(--admin-border);"><input wire:model="isRegex" type="checkbox" class="rounded border-gray-600 text-indigo-500 bg-transparent">Regex Pattern</label>
                <button type="submit" class="btn-primary w-full">{{ $editingId ? 'Güncelle' : 'Ekle' }}</button>
            </form>
        </div>
        <div class="lg:col-span-2 glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th class="text-left">Pattern</th><th class="text-center hidden sm:table-cell">Kategori</th><th class="text-center hidden sm:table-cell">Ciddiyet</th><th class="text-center">Durum</th><th class="text-right">İşlem</th></tr></thead>
                    <tbody>@forelse($filters as $f)
                        <tr><td><span class="text-white font-mono text-[12px]">{{ $f->pattern }}</span>@if($f->is_regex)<span class="status-purple ml-1">REGEX</span>@endif</td>
                        <td class="text-center hidden sm:table-cell"><span class="status-info">{{ $f->category }}</span></td>
                        <td class="text-center hidden sm:table-cell"><span class="{{ $f->severity === 'high' ? 'status-danger' : ($f->severity === 'medium' ? 'status-warning' : 'status-success') }}">{{ $f->severity }}</span></td>
                        <td class="text-center"><button wire:click="toggleActive({{ $f->id }})" class="cursor-pointer {{ $f->is_active ? 'status-success' : 'text-[11px] px-2.5 py-0.5 rounded-full bg-gray-800 text-gray-600' }}">{{ $f->is_active ? 'Aktif' : 'Pasif' }}</button></td>
                        <td class="text-right"><button wire:click="edit({{ $f->id }})" class="text-indigo-400 hover:text-indigo-300 text-[11px] mr-2 font-medium transition-colors">Düzenle</button><button wire:click="delete({{ $f->id }})" wire:confirm="Silmek istediğinize emin misiniz?" class="text-red-400 hover:text-red-300 text-[11px] font-medium transition-colors">Sil</button></td></tr>
                    @empty<tr><td colspan="5"><div class="text-center py-10"><p class="text-sm text-gray-600">Filtre bulunamadı</p></div></td></tr>@endforelse</tbody>
                </table>
            </div>
            <div class="p-4" style="border-top: 1px solid var(--admin-border);">{{ $filters->links() }}</div>
        </div>
    </div>
</div>
