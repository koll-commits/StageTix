@props(['label', 'value', 'note' => null])
<div {{ $attributes->class('rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900') }}>
    <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $label }}</p>
    <p class="mt-1 text-2xl font-semibold">{{ $value }}</p>
    @if ($note)
        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $note }}</p>
    @endif
</div>
