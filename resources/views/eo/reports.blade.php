<x-layouts.eo title="Laporan Penjualan">
    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Pendapatan kotor" :value="'Rp '.number_format($gross, 0, ',', '.')" note="Penjualan tiket (pesanan dibayar)" />
        <x-stat-card label="Komisi platform" :value="'Rp '.number_format($commission, 0, ',', '.')" />
        <x-stat-card label="Pendapatan bersih" :value="'Rp '.number_format($net, 0, ',', '.')" />
    </div>

    <h2 class="mb-3 mt-6 text-base font-semibold">Penjualan per event</h2>
    <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        <table class="w-full min-w-[32rem] text-left text-sm">
            <thead class="bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"><tr><th class="px-4 py-2">Event</th><th class="px-4 py-2">Tiket</th><th class="px-4 py-2">Omzet</th></tr></thead>
            <tbody>
                @forelse ($perEvent as $row)
                    <tr class="border-t border-zinc-200 dark:border-zinc-800"><td class="px-4 py-3">{{ $row->title }}</td><td class="px-4 py-3">{{ number_format((int) $row->tickets, 0, ',', '.') }}</td><td class="px-4 py-3"><x-rupiah :amount="$row->gross" /></td></tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-zinc-500">Belum ada penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">Ekspor Excel/PDF dan okupansi venue (FR-EO-05) ditambahkan pada modul pelaporan.</p>
</x-layouts.eo>
