@props(['title' => null, 'description' => null])

<div {{ $attributes->class('flex flex-col items-center gap-2 rounded-lg border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-600') }}>
    @if ($title)
        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $title }}</p>
    @endif
    @if ($description)
        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
    {{ $slot }}
    @isset($action)
        <div class="mt-2">{{ $action }}</div>
    @endisset
</div>