@props(['type' => 'button', 'variant' => 'primary'])

@php
$variants = [
    'primary' => 'bg-red-600 text-white hover:bg-red-700 dark:bg-red-600 dark:text-white dark:hover:bg-red-500',
    'secondary' => 'border border-zinc-300 text-zinc-800 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-100 dark:hover:bg-zinc-800',
];
@endphp

<button type="{{ $type }}"
    {{ $attributes->class(['inline-flex min-h-11 items-center justify-center gap-2 rounded-lg px-4 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 disabled:opacity-50', $variants[$variant] ?? $variants['primary']]) }}>
    {{ $slot }}
</button>

