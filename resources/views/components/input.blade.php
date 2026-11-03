@props(['name', 'label' => null, 'type' => 'text', 'bag' => 'default', 'hint' => null])
<div class="flex flex-col gap-1">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</label>
    @endif
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
        {{ $attributes->class('min-h-11 rounded-lg border border-zinc-300 bg-white px-3 text-sm text-zinc-900 placeholder-zinc-400 focus:border-zinc-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100') }} />
    @if ($hint)
        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $hint }}</p>
    @endif
    @if ($errors->{$bag}->has($name))
        <p class="text-xs text-red-600 dark:text-red-400">{{ $errors->{$bag}->first($name) }}</p>
    @endif
</div>
