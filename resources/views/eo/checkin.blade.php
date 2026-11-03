<x-layouts.eo title="Check-in Gate">
    <div class="mb-6 rounded-xl border border-dashed border-zinc-300 bg-white p-6 text-center dark:border-zinc-700 dark:bg-zinc-900">
        <p class="font-medium">Pemindai QR e-tiket</p>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Pemindaian dan validasi tiket (FR-EO-04) diaktifkan pada modul Check-in Gate. Halaman ini sudah menampilkan rekap kehadiran per event.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @forelse ($events as $event)
            @php
                $issued = (int) ($counts[$event->id]->issued ?? 0);
                $in = (int) ($counts[$event->id]->checked_in ?? 0);
                $pct = $issued ? round($in / $issued * 100) : 0;
            @endphp
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-medium">{{ $event->title }}</p>
                    <x-status-badge :status="$event->status" />
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400"><x-wib :at="$event->starts_at" /></p>
                <p class="mt-3 text-2xl font-semibold">{{ $in }} <span class="text-sm font-normal text-zinc-500">/ {{ $issued }} masuk</span></p>
                <div class="mt-2 h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800"><div class="h-full bg-zinc-900 dark:bg-zinc-100" style="width: {{ $pct }}%"></div></div>
            </div>
        @empty
            <div class="sm:col-span-2"><x-empty-state title="Belum ada event untuk di-check-in" description="Event yang dipublikasikan akan tampil di sini." /></div>
        @endforelse
    </div>

    <h2 class="mb-3 mt-6 text-base font-semibold">Check-in terakhir</h2>
    <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        <table class="w-full min-w-[32rem] text-left text-sm">
            <thead class="bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"><tr><th class="px-4 py-2">Pemegang tiket</th><th class="px-4 py-2">Event</th><th class="px-4 py-2">Waktu</th></tr></thead>
            <tbody>
                @forelse ($recent as $t)
                    <tr class="border-t border-zinc-200 dark:border-zinc-800"><td class="px-4 py-3">{{ $t->holder_name }}</td><td class="px-4 py-3">{{ $t->event->title }}</td><td class="px-4 py-3"><x-wib :at="$t->checked_in_at" /></td></tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-zinc-500">Belum ada tiket yang check-in.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.eo>
