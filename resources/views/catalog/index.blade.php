<x-layouts.public title="Katalog Konser">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Konser & event musik</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Temukan konser dari berbagai Event Organizer dalam satu tempat.</p>
    </div>

    <form method="GET" action="{{ route('catalog.home') }}" class="mb-6 grid gap-3 sm:grid-cols-4">
        <div class="sm:col-span-2"><x-input name="q" placeholder="Cari nama konser" :value="$q" /></div>
        <x-select name="genre">
            <option value="">Semua genre</option>
            @foreach ($genres as $g)<option value="{{ $g }}" @selected($genre === $g)>{{ $g }}</option>@endforeach
        </x-select>
        <x-button type="submit">Cari</x-button>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($events as $event)
            @php
                $open = $event->ticketCategories->filter(fn ($c) => ! $c->is_sold_out && $c->sold_qty < $c->quota);
                $from = $open->min('price_amount');
            @endphp
            <a href="{{ route('catalog.show', $event->slug) }}" class="flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white hover:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="aspect-[16/9] bg-gradient-to-br from-zinc-200 to-zinc-400 dark:from-zinc-700 dark:to-zinc-900">
                    @if ($event->poster_path)
                        <img src="{{ asset('storage/'.$event->poster_path) }}" alt="Poster {{ $event->title }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="flex flex-1 flex-col gap-1 p-4">
                    <span class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ $event->genre }}</span>
                    <p class="font-semibold">{{ $event->title }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400"><x-wib :at="$event->starts_at" /></p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $event->venue->name }}, {{ $event->venue->city }}</p>
                    <p class="mt-auto pt-2 text-sm font-medium">
                        @if ($event->ticketCategories->isEmpty())
                            Tiket segera hadir
                        @elseif ($open->isEmpty())
                            <x-status-badge status="soldout" />
                        @else
                            Mulai <x-rupiah :amount="$from" />
                        @endif
                    </p>
                </div>
            </a>
        @empty
            <div class="sm:col-span-2 lg:col-span-3">
                <x-empty-state title="Belum ada konser" description="Coba ubah kata kunci atau genre." />
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $events->links() }}</div>
</x-layouts.public>
