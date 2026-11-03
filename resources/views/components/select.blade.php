@props(['name', 'label' => null, 'bag' => 'default'])
<div class="flex flex-col gap-1">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->class('min-h-11 rounded-lg border border-zinc-300 bg-white px-3 text-sm text-zinc-900 focus:border-zinc-500 focus:outline-none disabled:opacity-60 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100') }}>
        {{ $slot }}
    </select>
    @if ($errors->{$bag}->has($name))
        <p class="text-xs text-red-600 dark:text-red-400">{{ $errors->{$bag}->first($name) }}</p>
    @endif
</div>
