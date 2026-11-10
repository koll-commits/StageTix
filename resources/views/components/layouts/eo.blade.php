@props(['title' => 'Event Organizer'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div x-data="{ open: false }" class="flex min-h-screen">
        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="open" 
             x-cloak
             @click="open = false" 
             class="fixed inset-0 z-20 bg-black/50 lg:hidden"
             aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 transform border-r border-zinc-200 bg-white p-4 transition-transform duration-200 lg:static lg:translate-x-0 dark:border-zinc-800 dark:bg-zinc-900 flex flex-col justify-between">
            <div>
                <div class="mb-6 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 font-bold text-white text-sm">EO</span>
                    <span class="text-lg font-semibold tracking-tight">StageTix EO</span>
                </div>
                <nav class="flex flex-col gap-1 text-sm">
                    <span class="flex items-center gap-2 rounded-lg bg-red-50 text-red-700 px-3 py-2 font-medium dark:bg-red-950/40 dark:text-red-300">
                        Dashboard
                    </span>
                    <span class="px-3 py-2 text-zinc-400 dark:text-zinc-500">Katalog Acara (Modul 7)</span>
                    <span class="px-3 py-2 text-zinc-400 dark:text-zinc-500">Pemesanan & Tiket (Modul 8–9)</span>
                    <span class="px-3 py-2 text-zinc-400 dark:text-zinc-500">Gate Check-in (Modul 12)</span>
                    <span class="px-3 py-2 text-zinc-400 dark:text-zinc-500">Laporan Penjualan (Modul 13)</span>
                </nav>
            </div>

            <!-- Footer User Profile -->
            @auth
            <div class="border-t border-zinc-200 pt-3 dark:border-zinc-800 flex items-center justify-between text-xs">
                <div class="truncate">
                    <p class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Keluar</button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex min-h-14 items-center justify-between border-b border-zinc-200 bg-white px-4 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center gap-3">
                    <button type="button" @click="open = !open" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg border border-zinc-200 lg:hidden dark:border-zinc-700" aria-label="Toggle sidebar">
                        ☰
                    </button>
                    <span class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $title }}</span>
                </div>
                @isset($headerRight)
                    <div>{{ $headerRight }}</div>
                @endisset
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

