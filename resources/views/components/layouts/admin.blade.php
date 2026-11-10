@props(['title' => 'Admin'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div class="flex min-h-screen flex-col">
        <header class="flex min-h-14 items-center justify-between border-b border-zinc-200 bg-white px-6 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 font-bold text-white text-xs dark:bg-white dark:text-zinc-900">ADM</span>
                <span class="text-base font-semibold">Pengelola Platform StageTix</span>
                <span class="ms-2 text-sm text-zinc-400">/ {{ $title }}</span>
            </div>

            <div class="flex items-center gap-4">
                @isset($headerRight)
                    <div>{{ $headerRight }}</div>
                @endisset
                @auth
                <div class="flex items-center gap-3 text-xs border-s border-zinc-200 ps-4 dark:border-zinc-800">
                    <span class="text-zinc-600 dark:text-zinc-300 font-medium">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Keluar</button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 p-6">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 p-4 text-center text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
            StageTix Platform Administration &bull; Modul 2 Modular Monolith
        </footer>
    </div>
</body>
</html>

