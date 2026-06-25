{{-- Custom Speed Selector with SVG Icons --}}
@props(['wireModel' => 'sendSpeed'])

<div x-data="{ open: false, selected: @entangle($wireModel) }" class="relative">
    <button @click="open = !open" @click.outside="open = false" type="button"
        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 bg-gray-50/50 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
        <span class="flex items-center gap-2.5">
            {{-- Hızlı --}}
            <template x-if="selected === 'hizli'">
                <span class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Hızlı — ~30 sn aralık</span>
                </span>
            </template>
            {{-- Orta --}}
            <template x-if="selected === 'orta'">
                <span class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Orta — 1-2 dk aralık</span>
                </span>
            </template>
            {{-- Yavaş --}}
            <template x-if="selected === 'yavas'">
                <span class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Yavaş — 3-5 dk aralık</span>
                </span>
            </template>
        </span>
        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <label class="absolute -top-2 left-3 bg-white px-1.5 text-[10px] text-gray-400 font-medium z-10">* Gönderim Hızı</label>

    {{-- Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">

        {{-- Hızlı --}}
        <button type="button" @click="selected = 'hizli'; open = false"
            :class="selected === 'hizli' ? 'bg-amber-50 text-amber-700' : 'text-gray-600 hover:bg-gray-50'"
            class="w-full flex items-center gap-2.5 px-3.5 py-2.5 text-sm transition-colors">
            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <div class="text-left">
                <span class="font-medium">Hızlı</span>
                <span class="text-[11px] text-gray-400 ml-1">~30 sn aralık</span>
            </div>
            <template x-if="selected === 'hizli'">
                <svg class="w-4 h-4 text-amber-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
        </button>

        {{-- Orta --}}
        <button type="button" @click="selected = 'orta'; open = false"
            :class="selected === 'orta' ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50'"
            class="w-full flex items-center gap-2.5 px-3.5 py-2.5 text-sm transition-colors border-t border-gray-100">
            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-left">
                <span class="font-medium">Orta</span>
                <span class="text-[11px] text-gray-400 ml-1">1-2 dk aralık</span>
            </div>
            <template x-if="selected === 'orta'">
                <svg class="w-4 h-4 text-blue-500 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
        </button>

        {{-- Yavaş --}}
        <button type="button" @click="selected = 'yavas'; open = false"
            :class="selected === 'yavas' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50'"
            class="w-full flex items-center gap-2.5 px-3.5 py-2.5 text-sm transition-colors border-t border-gray-100">
            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <div class="text-left">
                <span class="font-medium">Yavaş</span>
                <span class="text-[11px] text-gray-400 ml-1">3-5 dk aralık</span>
            </div>
            <template x-if="selected === 'yavas'">
                <svg class="w-4 h-4 text-green-600 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
        </button>
    </div>
</div>
