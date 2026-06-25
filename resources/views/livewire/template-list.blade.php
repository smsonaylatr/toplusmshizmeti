<div>
    {{-- Page Title --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Şablonlar</h1>

    {{-- Section Title with orange underline --}}
    <div class="mb-4">
        <h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Şablon İşlemleri</h2>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-100">
            <p class="text-xs text-gray-500 mb-3">
                *Sayfa içinde Şablon güncelleme işlemi için <span class="text-[#2563eb]">Şablon Adı</span> veya <span class="text-[#2563eb]">Şablon Mesaj</span> Sütunlarına tıklayabilirsiniz.
            </p>
            <a href="{{ route('panel.templates.create') }}" wire:navigate
               class="inline-block px-5 py-2 border border-gray-400 rounded text-[12px] text-gray-700 hover:bg-gray-50 transition-colors font-bold tracking-wide">
                YENİ ŞABLON OLUŞTUR
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-500 bg-gray-50/50">
                        <th class="text-left py-3 px-4 font-semibold text-xs w-40">Şablon Adı</th>
                        <th class="text-left py-3 px-4 font-semibold text-xs">Mesaj</th>
                        <th class="text-right py-3 px-4 font-semibold text-xs w-24">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-700 font-medium">{{ $template->name }}</td>
                            <td class="py-3 px-4 text-xs text-gray-600">
                                <p class="line-clamp-3">{{ $template->content }}</p>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-gray-400 hover:text-[#2563eb] transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="deleteTemplate({{ $template->id }})"
                                            wire:confirm="Bu şablonu silmek istediğinize emin misiniz?"
                                            class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center text-gray-400 text-sm">
                                Henüz şablon oluşturulmamış.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-end gap-4 text-xs text-gray-500">
            <div class="flex items-center gap-2">
                <span>Sayfa başına satır:</span>
                <select class="border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
            <span>{{ $templates->count() > 0 ? '1 - ' . $templates->count() . ' arası, Toplam: ' . $templates->count() . ' kayıt' : '0 kayıt' }}</span>
            <div class="flex items-center gap-1">
                <button class="p-1 text-gray-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button class="p-1 text-gray-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
        </div>
    </div>
</div>
