<x-layouts.eo title="Dashboard">
    <div class="flex items-center gap-3">
        <h1 class="text-xl font-semibold">Dashboard Event Organizer</h1>
        <x-status-badge status="active">aktif</x-status-badge>
    </div>
    
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
        EO Identifier: <span class="font-mono bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded text-zinc-800 dark:text-zinc-200">{{ $eo }}</span>
    </p>

    <div class="mt-6">
        <x-empty-state 
            title="Belum Ada Aktivitas Acara"
            description="Manajemen acara, pemesanan tiket, dan sistem scan check-in Gate akan tampil di sini (Modul 7 & 12)." />
    </div>
</x-layouts.eo>

