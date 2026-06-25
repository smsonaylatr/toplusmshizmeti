<div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
        {{-- Groups Sidebar --}}
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Gruplar</h3>
                </div>
                <div class="p-3">
                    <div class="space-y-1 mb-3">
                        <button wire:click="$set('filterGroup', null)"
                                class="w-full flex items-center justify-between px-3 py-2 rounded text-xs transition-colors
                                       {{ !$filterGroup ? 'bg-[#2563eb]/10 text-[#2563eb] font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                            <span>Tümü</span>
                        </button>
                        @foreach($groups as $group)
                            <div class="flex items-center gap-1">
                                <button wire:click="$set('filterGroup', {{ $group->id }})"
                                        class="flex-1 flex items-center justify-between px-3 py-2 rounded text-xs transition-colors
                                               {{ $filterGroup == $group->id ? 'bg-[#2563eb]/10 text-[#2563eb] font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" style="background: {{ $group->color }}"></span>
                                        {{ $group->name }}
                                    </span>
                                    <span class="text-[10px] opacity-60">{{ $group->contacts_count }}</span>
                                </button>
                                <button wire:click="deleteGroup({{ $group->id }})" wire:confirm="Bu grubu silmek istediğinize emin misiniz?"
                                        class="text-gray-300 hover:text-red-400 transition-colors p-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if($showGroupForm)
                        <div class="space-y-2 pt-3 border-t border-gray-100">
                            <input wire:model="groupName" placeholder="Grup adı" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('groupName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            <div class="flex items-center gap-2">
                                <input type="color" wire:model="groupColor" class="w-7 h-7 rounded cursor-pointer">
                                <span class="text-[10px] text-gray-400">Renk</span>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="saveGroup" class="px-3 py-1.5 bg-[#2563eb] text-white text-xs rounded hover:bg-[#1d4ed8]">Kaydet</button>
                                <button wire:click="$set('showGroupForm', false)" class="px-3 py-1.5 text-gray-400 text-xs hover:text-gray-600">İptal</button>
                            </div>
                        </div>
                    @else
                        <button wire:click="$set('showGroupForm', true)"
                                class="w-full flex items-center justify-center gap-1 px-3 py-2 rounded text-xs text-gray-400 hover:text-[#2563eb] hover:bg-gray-50 transition-colors border border-dashed border-gray-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Yeni Grup
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contacts List --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                {{-- Toolbar --}}
                <div class="px-4 py-3 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="relative flex-1 max-w-sm">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ara..."
                               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    </div>
                    <button wire:click="openForm" class="px-4 py-2 bg-[#2563eb] text-white text-xs font-medium rounded hover:bg-[#1d4ed8] flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Kişi Ekle
                    </button>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 bg-gray-50/50">
                                <th class="text-left py-2.5 px-4 font-medium text-xs">Ad Soyad</th>
                                <th class="text-left py-2.5 px-4 font-medium text-xs">Telefon</th>
                                <th class="text-left py-2.5 px-4 font-medium text-xs hidden md:table-cell">E-posta</th>
                                <th class="text-left py-2.5 px-4 font-medium text-xs hidden sm:table-cell">Grup</th>
                                <th class="text-right py-2.5 px-4 font-medium text-xs">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($contacts as $contact)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-2.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-[#3498db] flex items-center justify-center text-[10px] font-semibold text-white">
                                                {{ mb_substr($contact->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">{{ $contact->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-4 text-gray-500 font-mono text-xs">{{ $contact->phone }}</td>
                                    <td class="py-2.5 px-4 text-gray-400 text-xs hidden md:table-cell">{{ $contact->email ?? '—' }}</td>
                                    <td class="py-2.5 px-4 hidden sm:table-cell">
                                        @if($contact->group)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] bg-gray-100 text-gray-600">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $contact->group->color }}"></span>
                                                {{ $contact->group->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="openForm({{ $contact->id }})" class="p-1.5 text-gray-300 hover:text-[#2563eb] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click="delete({{ $contact->id }})" wire:confirm="Bu kişiyi silmek istediğinize emin misiniz?" class="p-1.5 text-gray-300 hover:text-red-400 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center">
                                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <p class="text-gray-400 text-sm">Kişi bulunamadı</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($contacts->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $contacts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Contact Form Slide Over --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex justify-end">
            <div class="absolute inset-0 bg-black/40" wire:click="$set('showForm', false)"></div>
            <div class="relative w-full max-w-md bg-white h-full overflow-y-auto shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-bold text-gray-800">{{ $editingId ? 'Kişi Düzenle' : 'Yeni Kişi' }}</h3>
                        <button wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Ad Soyad *</label>
                            <input wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]" required>
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Telefon *</label>
                            <input wire:model="phone" placeholder="5xxxxxxxxx" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]" required>
                            @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">E-posta</label>
                            <input wire:model="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Grup</label>
                            <select wire:model="groupId" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                                <option value="">Grup seçin...</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Notlar</label>
                            <textarea wire:model="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm resize-none focus:outline-none focus:ring-1 focus:ring-[#2563eb]"></textarea>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-[#2563eb] text-white text-sm font-medium rounded hover:bg-[#1d4ed8]">{{ $editingId ? 'Güncelle' : 'Ekle' }}</button>
                            <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded hover:bg-gray-200">İptal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
