<x-layouts.eo title="Event & Tiket">
    <x-slot:headerRight>
        <a href="{{ route('eo.events.create') }}"><x-button>+ Event baru</x-button></a>
    </x-slot:headerRight>

    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        @foreach (['' => 'Semua', 'draft' => 'Draft', 'published' => 'Dipublikasikan', 'ongoing' => 'Berlangsung', 'finished' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
            <a href="{{ route('eo.events.index', $key ? ['status' => $key] : []) }}"
                class="inline-flex min-h-11 items-center rounded-full border px-4 {{ ($status ?? '') === $key ? 'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' : 'border-zinc-300 hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800' }}">{{ $label }}</a>
        @endforeach
    </div>

    @forelse ($events as $event)
        @php $pct = $event->quota_total ? round($event->sold_total / $event->quota_total * 100) : 0; @endphp
        <a href="{{ route('eo.events.show', $event->id) }}" class="mb-3 flex gap-4 rounded-xl border border-zinc-200 bg-white p-4 hover:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="hidden h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-zinc-100 sm:block dark:bg-zinc-800">
                @if ($event->poster_path)
                    <img src="{{ asset('storage/'.$event->poster_path) }}" alt="Poster {{ $event->title }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <p class="truncate font-medium">{{ $event->title }}</p>
                    <x-status-badge :status="$event->status" />
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $event->genre }} · {{ $event->venue->name }}, {{ $event->venue->city }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400"><x-wib :at="$event->starts_at" /></p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">{{ (int) $event->sold_total }} / {{ (int) $event->quota_total }} tiket terjual ({{ $pct }}%)</p>
            </div>
        </a>
    @empty
        <x-empty-state title="Belum ada event" description="Event yang kamu buat akan muncul di sini.">
            <x-slot:action><a href="{{ route('eo.events.create') }}"><x-button>Buat event</x-button></a></x-slot:action>
        </x-empty-state>
    @endforelse

    <div class="mt-4">{{ $events->links() }}</div>
</x-layouts.eo>
