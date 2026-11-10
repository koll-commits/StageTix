@props(['variant' => 'primary', 'type' => 'button'])

@php
    $variants = [
        'primary' => 'bg-zinc-900 text-white hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-300',
        'secondary' => 'border border-zinc-300 bg-white text-zinc-900 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ];
@endphp

<button type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex min-h-11 items-center justify-center rounded-lg px-4 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-zinc-500 disabled:cursor-not-allowed disabled:opacity-50',
        $variants[$variant] ?? $variants['primary'],
    ]) }}>
    {{ $slot }}
</button>