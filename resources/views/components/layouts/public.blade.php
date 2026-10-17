@props(['title' => 'Katalog Event'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-black-50 text-zinc-900 dark:bg-sky-950 dark:text-black-100">
      <div class="mx-auto flex min-h-screen w-full max-w-md flex-col md:max-w-3xl">
        <header class="sticky top-0 z-10 flex min-h-14 items-center gap-2 border-b border-zinc-200 bg-black-500 px-4 backdrop-blur dark:border-blue-800 dark:bg-blue-700">
            <a href="{{ route('public.home') }}" class="text-base font-semibold">{{ config('app.name') }}</a>
            <span class="text-sm text-zinc-500 dark:text-white-400">/ {{ $title }}</span>

            <nav class="ms-auto flex items-center gap-1 text-sm">
                @auth
                    <a href="{{ route('buyer.orders.index') }}" class="inline-flex min-h-11 items-center rounded-lg px-3 hover:bg-zinc-100 dark1:hover:bg-zinc-800">Pesanan</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-lg px-3 hover:bg-zinc-100 dark:hover:bg-zinc-800">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex min-h-11 items-center rounded-lg bg-zinc-900 px-3 text-white hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Daftar</a>
                    @endif
                @endauth
            </nav>
        </header>

        <main class="flex-1 p-4">{{ $slot }}</main>

        <footer class="border-t border-zinc-200 p-4 text-center text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
            {{ config('app.name') }}
        </footer>
    </div>
    @livewireScripts
</body>
</html>