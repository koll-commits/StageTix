@props(['title' => 'StageTix'])
@php
$user = auth()->user();
$portal = $user ? \App\Support\HomeRedirect::url($user) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <header class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
        <div class="mx-auto flex min-h-14 max-w-6xl items-center justify-between gap-3 px-4">
            <a href="{{ route('catalog.home') }}" class="text-lg font-semibold">StageTix</a>
            <nav class="flex items-center gap-2 text-sm">
                @auth
                    @if ($portal !== route('catalog.home'))
                        <a href="{{ $portal }}"><x-button variant="secondary">Dashboard</x-button></a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button type="submit" variant="secondary">Keluar</x-button>
                    </form>
                @else
                    <a href="{{ route('login') }}"><x-button>Masuk</x-button></a>
                @endauth
            </nav>
        </div>
    </header>
    <main class="mx-auto w-full max-w-6xl p-4 sm:p-6">
        {{ $slot }}
    </main>
</body>
</html>
