<x-layouts.public title="Daftar">
    <form method="POST" action="{{ route('register.store') }}" class="mx-auto flex max-w-sm flex-col gap-4">
        @csrf

        <h1 class="text-xl font-semibold">Buat akun StageTix</h1>

        <div>
            <x-input name="name" label="Nama" :value="old('name')" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input name="email" type="email" label="Surel" :value="old('email')"
                     required autocomplete="username" />
            @error('email')
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input name="password" type="password" label="Kata sandi"
                     required autocomplete="new-password" />
            @error('password')
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <x-input name="password_confirmation" type="password" label="Ulangi kata sandi"
                 required autocomplete="new-password" />

        <x-button type="submit">Daftar</x-button>

        <p class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium underline">Masuk</a>
        </p>
    </form>
</x-layouts.public>