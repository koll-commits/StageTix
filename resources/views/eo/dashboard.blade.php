<x-layouts.eo title="Dashboard">
    <x-slot:headerRight>
        <a href="{{ route('eo.events.create') }}"><x-button>+ Event baru</x-button></a>
    </x-slot:headerRight>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Event aktif" :value="$activeEvents" note="Dipublikasikan / berlangsung" />
        <x-stat-card label="Tiket terjual" :value="number_format($soldTotal, 0, ',', '.')" :note="'dari kuota '.number_format($quotaTotal, 0, ',', '.')" />
        <x-stat-card label="Pendapatan bersih" :value="'Rp '.number_format($netRevenue, 0, ',', '.')" note="Setelah komisi platform" />
        <x-stat-card label="Saldo tersedia" :value="'Rp '.number_format($balance?->available_amount ?? 0, 0, ',', '.')" :note="'Tertahan Rp '.number_format($balance?->held_amount ?? 0, 0, ',', '.')" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-5">
        <section class="lg:col-span-3">
            <h2 class="mb-3 text-base font-semibold">Event mendatang</h2>
            @forelse ($upcoming as $event)
                @php $pct = $event->quota_total ? round($event->sold_total / $event->quota_total * 100) : 0; @endphp
                <a href="{{ route('eo.events.show', $event->id) }}" class="mb-3 block rounded-xl border border-zinc-200 bg-white p-4 hover:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $event->title }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $event->genre }} · {{ $event->venue->name }}, {{ $event->venue->city }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400"><x-wib :at="$event->starts_at" /></p>
                        </div>
                        <x-status-badge :status="$event->status" />
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                        <div class="h-full bg-zinc-900 dark:bg-zinc-100" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ (int) $event->sold_total }} / {{ (int) $event->quota_total }} tiket ({{ $pct }}%)</p>
                </a>
            @empty
                <x-empty-state title="Belum ada event aktif" description="Buat event, tambahkan kategori tiket, lalu publikasikan.">
                    <x-slot:action><a href="{{ route('eo.events.create') }}"><x-button>Buat event</x-button></a></x-slot:action>
                </x-empty-state>
            @endforelse
        </section>

        <section class="lg:col-span-2">
            <h2 class="mb-3 text-base font-semibold">Tandai sold out cepat</h2>
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
                @forelse ($switches as $cat)
                    <form method="POST" action="{{ route('eo.categories.sold-out', $cat->id) }}" class="flex min-h-14 items-center justify-between gap-3 border-b border-zinc-100 px-4 py-2 last:border-0 dark:border-zinc-800">
                        @csrf @method('PATCH')
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $cat->name }}</p>
                            <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $cat->event->title }} · {{ $cat->sold_qty }}/{{ $cat->quota }}</p>
                        </div>
                        <x-button type="submit" variant="secondary">{{ $cat->is_sold_out ? 'Buka lagi' : 'Sold out' }}</x-button>
                    </form>
                @empty
                    <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400">Belum ada kategori tiket pada event yang dipublikasikan.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.eo>
