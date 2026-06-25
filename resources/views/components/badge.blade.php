@props([
    'type' => 'pending',
])

@php
$styles = [
    'pending' => 'bg-blue-500/10 text-amber-400 ring-amber-500/20',
    'sent' => 'bg-blue-500/10 text-blue-400 ring-blue-500/20',
    'delivered' => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/20',
    'failed' => 'bg-rose-500/10 text-rose-400 ring-rose-500/20',
    'draft' => 'bg-slate-500/10 text-slate-400 ring-slate-500/20',
    'sending' => 'bg-blue-500/10 text-indigo-400 ring-indigo-500/20',
    'completed' => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/20',
    'info' => 'bg-sky-500/10 text-sky-400 ring-sky-500/20',
    'success' => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/20',
    'warning' => 'bg-blue-500/10 text-amber-400 ring-amber-500/20',
    'danger' => 'bg-rose-500/10 text-rose-400 ring-rose-500/20',
];
$labels = [
    'pending' => 'Bekliyor',
    'sent' => 'Gönderildi',
    'delivered' => 'İletildi',
    'failed' => 'Başarısız',
    'draft' => 'Taslak',
    'sending' => 'Gönderiliyor',
    'completed' => 'Tamamlandı',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full ring-1 ring-inset ' . ($styles[$type] ?? $styles['info'])]) }}>
    {{ $slot->isEmpty() ? ($labels[$type] ?? $type) : $slot }}
</span>
