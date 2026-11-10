<x-layouts.public title="Masuk">
    <div class="mx-auto mt-8 max-w-md rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <h1 class="text-xl font-semibold">Masuk ke StageTix</h1>
        <p class="mb-6 text-sm text-zinc-500 dark:text-zinc-400">Pembeli, Event Organizer, dan pengelola masuk dari sini.</p>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <x-input name="email" type="email" label="Email" required autofocus autocomplete="email" :value="old('email')" />
            <x-input name="password" type="password" label="Kata sandi" required autocomplete="current-password" />
            <label class="flex min-h-11 items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <x-button type="submit" class="w-full">Masuk</x-button>
        </form>
    </div>
</x-layouts.public>
