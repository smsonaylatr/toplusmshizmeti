@props([
    'label' => '',
    'value' => '0',
    'change' => null,
    'changeUp' => true,
    'icon' => 'chart',
    'color' => 'indigo',
])

@php
$colors = [
    'indigo' => ['bg' => 'bg-blue-500/10', 'text' => 'text-indigo-400', 'ring' => 'ring-indigo-500/20'],
    'emerald' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'ring' => 'ring-emerald-500/20'],
    'amber' => ['bg' => 'bg-blue-500/10', 'text' => 'text-amber-400', 'ring' => 'ring-amber-500/20'],
    'rose' => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-400', 'ring' => 'ring-rose-500/20'],
    'violet' => ['bg' => 'bg-violet-500/10', 'text' => 'text-violet-400', 'ring' => 'ring-violet-500/20'],
];
$c = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="glass-card rounded-xl p-5 animate-fade-in-up">
    <div class="flex items-start justify-between">
        <div class="space-y-2">
            <p class="text-sm font-medium text-slate-400">{{ $label }}</p>
            <p class="text-2xl font-bold text-white tracking-tight">{{ $value }}</p>
            @if($change !== null)
                <p class="flex items-center gap-1 text-xs font-medium {{ $changeUp ? 'text-emerald-400' : 'text-rose-400' }}">
                    @if($changeUp)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    @endif
                    {{ $change }}
                </p>
            @endif
        </div>
        <div class="{{ $c['bg'] }} {{ $c['text'] }} p-3 rounded-xl ring-1 {{ $c['ring'] }}">
            @if($icon === 'sms')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            @elseif($icon === 'users')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            @elseif($icon === 'check')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($icon === 'alert')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            @endif
        </div>
    </div>
</div>
