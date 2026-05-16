@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
])

@php
    $base = 'neon-btn inline-flex min-h-11 items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 touch-manipulation';
    $variants = [
        'primary' => 'bg-cyan-500 text-slate-900 hover:bg-cyan-400 focus:ring-cyan-400',
        'secondary' => 'border border-slate-600 bg-slate-800 text-slate-200 hover:border-cyan-500/50 hover:text-cyan-300 focus:ring-cyan-500/50',
        'danger' => 'border border-red-500/40 bg-red-500/10 text-red-300 hover:bg-red-500/20 focus:ring-red-500',
        'ghost' => 'text-slate-400 hover:text-cyan-300',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
