<x-layouts.eo title="Saldo & Penarikan">
    <div class="grid gap-4 sm:grid-cols-2">
        <x-stat-card label="Saldo tersedia" :value="'Rp '.number_format($balance?->available_amount ?? 0, 0, ',', '.')" />
        <x-stat-card label="Saldo tertahan" :value="'Rp '.number_format($balance?->held_amount ?? 0, 0, ',', '.')" note="Dikunci sampai penarikan disetujui platform" />
    </div>

    <h2 class="mb-3 mt-6 text-base font-semibold">Rekening terdaftar</h2>
    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        @forelse ($accounts as $acc)
            <div class="flex items-center justify-between gap-3 border-b border-zinc-100 px-4 py-3 last:border-0 dark:border-zinc-800">
                <div>
                    <p class="text-sm font-medium">{{ strtoupper($acc->bank_code) }} •••• {{ $acc->account_last4 }} @if ($acc->is_primary)<span class="text-xs text-zinc-500">(utama)</span>@endif</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">a.n. {{ $acc->account_holder }}</p>
                </div>
                <x-status-badge :status="$acc->status" />
            </div>
        @empty
            <p class="p-4 text-sm text-zinc-500 dark:text-zinc-400">Belum ada rekening terdaftar.</p>
        @endforelse
    </div>

    <h2 class="mb-3 mt-6 text-base font-semibold">Riwayat penarikan</h2>
    <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        <table class="w-full min-w-[28rem] text-left text-sm">
            <thead class="bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"><tr><th class="px-4 py-2">Diajukan</th><th class="px-4 py-2">Jumlah</th><th class="px-4 py-2">Status</th></tr></thead>
            <tbody>
                @forelse ($withdrawals as $w)
                    <tr class="border-t border-zinc-200 dark:border-zinc-800"><td class="px-4 py-3"><x-wib :at="$w->created_at" /></td><td class="px-4 py-3"><x-rupiah :amount="$w->amount" /></td><td class="px-4 py-3"><x-status-badge :status="$w->status" /></td></tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-zinc-500">Belum ada pengajuan penarikan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">Formulir pengajuan penarikan (FR-EO-07) ditambahkan bersama logika saldo dan persetujuan pengelola.</p>
</x-layouts.eo>
