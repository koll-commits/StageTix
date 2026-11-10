<x-layouts.public :title="$event->title">
    <a href="{{ route('catalog.home') }}" class="text-sm text-zinc-500 hover:underline">← Semua konser</a>

    <div class="mt-3 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        <div class="aspect-[21/9] bg-gradient-to-br from-zinc-200 to-zinc-400 dark:from-zinc-700 dark:to-zinc-900">
            @if ($event->poster_path)
                <img src="{{ asset('storage/'.$event->poster_path) }}" alt="Poster {{ $event->title }}" class="h-full w-full object-cover">
            @endif
        </div>
        <div class="p-4 sm:p-6">
            <span class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ $event->genre }}</span>
            <h1 class="text-2xl font-semibold">{{ $event->title }}</h1>
            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                <div><dt class="text-zinc-500">Waktu</dt><dd><x-wib :at="$event->starts_at" /></dd></div>
                <div><dt class="text-zinc-500">Lokasi</dt><dd>{{ $event->venue->name }}, {{ $event->venue->city }}</dd></div>
                <div><dt class="text-zinc-500">Maks. tiket per akun</dt><dd>{{ $event->max_tickets_per_account }}</dd></div>
            </dl>
            @if ($event->description)
                <p class="mt-4 whitespace-pre-line text-sm text-zinc-600 dark:text-zinc-300">{{ $event->description }}</p>
            @endif
        </div>
    </div>

    <h2 class="mb-3 mt-6 text-base font-semibold">Kategori tiket</h2>
    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        @forelse ($categories as $cat)
            @php $soldOut = $cat->is_sold_out || $cat->sold_qty >= $cat->quota; @endphp
            <div class="flex min-h-14 flex-wrap items-center justify-between gap-3 border-b border-zinc-100 px-4 py-3 last:border-0 dark:border-zinc-800">
                <div>
                    <p class="font-medium">{{ $cat->name }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $cat->area->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium"><x-rupiah :amount="$cat->price_amount" /></span>
                    @if ($soldOut)<x-status-badge status="soldout" />@else<x-status-badge status="active">Tersedia</x-status-badge>@endif
                </div>
            </div>
        @empty
            <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400">Tiket belum dirilis.</p>
        @endforelse
    </div>
    <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">Pembelian tiket (keranjang, checkout, dan pembayaran QRIS) dibuka pada modul pemesanan.</p>
</x-layouts.public>
