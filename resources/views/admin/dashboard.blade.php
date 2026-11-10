<x-layouts.admin title="Dashboard">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold">Dashboard Pengelola Platform</h1>
            <p class="text-sm text-zinc-500">Kelola Event Organizer, komisi transaksi, dan verifikasi rekening.</p>
        </div>
        <x-status-badge status="active">Sistem Online</x-status-badge>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="bg-zinc-50 text-zinc-600 dark:bg-zinc-800/60 dark:text-zinc-300 border-b border-zinc-200 dark:border-zinc-800">
                <tr>
                    <th class="px-4 py-3 font-medium">Event Organizer</th>
                    <th class="px-4 py-3 font-medium">Penanggung Jawab</th>
                    <th class="px-4 py-3 font-medium">Status Operasional</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t border-zinc-200 dark:border-zinc-800">
                    <td class="px-4 py-6" colspan="3">
                        <x-empty-state 
                            title="Belum ada Event Organizer terdaftar" 
                            description="Administrasi EO, role delegasi, dan skema bagi hasil diisi pada Modul 5." />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-layouts.admin>

