<x-layouts.public title="Masuk">
    <form method="POST" action="{{ route('login.store') }}" class="mx-auto flex max-w-sm flex-col gap-4">
        @csrf

        <h1 class="text-xl font-semibold">Masuk ke akun StageTix</h1>

        <div>
            <x-input name="email" type="email" label="Surel" :value="old('email')"
                     required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input name="password" type="password" label="Kata sandi"
                     required autocomplete="current-password" />
            @error('password')
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex min-h-11 items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="size-4"> Ingat saya
        </label>

        <x-button type="submit">Masuk</x-button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-zinc-600 dark:text-zinc-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium underline">Daftar sebagai pembeli</a>
            </p>
        @endif
    </form>
</x-layouts.public>