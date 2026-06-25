@props([
    'label' => '',
    'error' => null,
    'options' => [],
    'placeholder' => 'Seçiniz...',
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ $label }}</label>
    @endif
    <select
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 bg-slate-800/50 border rounded-lg text-sm text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 appearance-none ' .
            ($error ? 'border-rose-500/50 focus:ring-rose-500/50 focus:border-rose-500' : 'border-slate-700/50 focus:ring-indigo-500/50 focus:border-indigo-500 hover:border-slate-600')
        ]) }}
    >
        <option value="" class="bg-slate-800">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" class="bg-slate-800">{{ $text }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @if($error)
        <p class="mt-1.5 text-xs text-rose-400">{{ $error }}</p>
    @endif
</div>
