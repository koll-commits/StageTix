@props(['name', 'label' => null, 'bag' => 'default'])
<div class="flex flex-col gap-1">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</label>
    @endif
    <textarea id="{{ $name }}" name="{{ $name }}" rows="4"
        {{ $attributes->class('rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-zinc-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100') }}>{{ $slot }}</textarea>
    @if ($errors->{$bag}->has($name))
        <p class="text-xs text-red-600 dark:text-red-400">{{ $errors->{$bag}->first($name) }}</p>
    @endif
</div>
