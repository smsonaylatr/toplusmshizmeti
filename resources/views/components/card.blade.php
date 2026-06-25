@props([
    'title' => '',
    'description' => '',
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'glass-card rounded-xl animate-fade-in-up']) }}>
    @if($title || $description)
        <div class="px-5 py-4 border-b border-slate-700/30">
            @if($title)
                <h3 class="text-base font-semibold text-white">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'p-5' : '' }}">
        {{ $slot }}
    </div>
</div>
