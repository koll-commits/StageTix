@props(['title' => 'Portal EO'])
@php
$nav = [
    ['Dashboard', 'eo.dashboard', 'eo.dashboard', true],
    ['Event & Tiket', 'eo.events.index', 'eo.events.*', true],
    ['Check-in Gate', 'eo.checkin', 'eo.checkin', false],
    ['Laporan Penjualan', 'eo.reports', 'eo.reports', true],
    ['Saldo & Penarikan', 'eo.balance', 'eo.balance', true],
];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div x-data="{ open: false }" class="flex min-h-screen">
        <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-20 flex w-64 transform flex-col border-r border-zinc-200 bg-white p-4 transition-transform duration-200 lg:static lg:translate-x-0 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-6">
                <div class="text-lg font-semibold">StageTix</div>
                <div class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $tenant->display_name }}</div>
            </div>
            <nav class="flex flex-col gap-1 text-sm">
                @foreach ($nav as [$label, $route, $pattern, $managerOnly])
                    @continue($managerOnly && $eoRole === 'gate_staff')
                    <a href="{{ route($route) }}"
                        class="flex min-h-11 items-center rounded-lg px-3 {{ request()->routeIs($pattern) ? 'bg-zinc-100 font-medium dark:bg-zinc-800' : 'text-zinc-600 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-zinc-800/60' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="mt-auto border-t border-zinc-200 pt-4 text-sm dark:border-zinc-800">
                <div class="truncate font-medium">{{ auth()->user()->name }}</div>
                <div class="mb-2 text-xs text-zinc-500 dark:text-zinc-400">{{ ['owner' => 'Pemilik EO', 'operator' => 'Operator', 'gate_staff' => 'Petugas gate'][$eoRole] ?? $eoRole }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button type="submit" variant="secondary" class="w-full">Keluar</x-button>
                </form>
            </div>
        </aside>
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex min-h-14 items-center gap-3 border-b border-zinc-200 bg-white px-4 dark:border-zinc-800 dark:bg-zinc-900">
                <button type="button" @click="open = !open" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg lg:hidden" aria-label="Buka menu">☰</button>
                <span class="font-semibold">{{ $title }}</span>
                @isset($headerRight)<div class="ms-auto">{{ $headerRight }}</div>@endisset
            </header>
            <main class="mx-auto w-full max-w-6xl flex-1 p-4 sm:p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-300">{{ session('error') }}</div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
    @fluxScripts
</body>
</html>
