@props([
    'label' => '',
    'type' => 'text',
    'error' => null,
    'hint' => null,
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 bg-slate-800/50 border rounded-lg text-sm text-white placeholder-slate-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 ' .
            ($error ? 'border-rose-500/50 focus:ring-rose-500/50 focus:border-rose-500' : 'border-slate-700/50 focus:ring-indigo-500/50 focus:border-indigo-500 hover:border-slate-600')
        ]) }}
    />
    @if($error)
        <p class="mt-1.5 text-xs text-rose-400">{{ $error }}</p>
    @elseif($hint)
        <p class="mt-1.5 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>
