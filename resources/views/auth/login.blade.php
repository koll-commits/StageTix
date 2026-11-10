<x-guest-layout>
    <div class="w-full max-w-4xl overflow-hidden rounded-3xl shadow-2xl flex flex-col md:flex-row min-h-[520px] bg-white border border-gray-200/80">
        <!-- Kolom Kiri: Warna Biru Web (#2A3BF5) dan Hanya Berisi Logo StageTix -->
        <div class="w-full md:w-1/2 bg-[#2A3BF5] p-8 md:p-12 flex items-center justify-center relative overflow-hidden">
            <!-- Ambient Glow -->
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-white/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-[#D6FF3D]/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Hanya Logo StageTix -->
            <a href="{{ route('customer.home') }}" class="relative z-10 transition transform hover:scale-105" title="StageTix • Beranda">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="StageTix" 
                     class="w-56 sm:w-64 md:w-72 h-auto object-contain drop-shadow-md">
            </a>
        </div>

        <!-- Kolom Kanan: Form Masuk -->
        <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center border-t md:border-t-0 md:border-l border-gray-100">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Masuk ke Akun</h2>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Silakan masukkan kredensial akun Anda.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="my-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <!-- Surel / Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Surel / Email
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           placeholder="nama@domain.com"
                           class="w-full min-h-12 rounded-xl border border-gray-200 bg-gray-50/50 px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-[#2A3BF5] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2A3BF5]/20 transition" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Kata Sandi -->
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Kata sandi
                        </label>
                        <button type="button" 
                                @click="show = !show" 
                                class="text-xs text-gray-500 hover:text-gray-900 font-medium focus:outline-none"
                                x-text="show ? 'Sembunyikan' : 'Tampilkan'">
                            Tampilkan
                        </button>
                    </div>
                    <input id="password" 
                           :type="show ? 'text' : 'password'" 
                           name="password" 
                           required 
                           placeholder="••••••••"
                           class="w-full min-h-12 rounded-xl border border-gray-200 bg-gray-50/50 px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-[#2A3BF5] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2A3BF5]/20 transition" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Tombol Masuk (10% #D6FF3D) -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full min-h-12 inline-flex items-center justify-center gap-2 rounded-xl bg-[#D6FF3D] hover:bg-[#c7f428] px-4 text-sm font-black text-black transition shadow-md shadow-[#D6FF3D]/25 uppercase tracking-wider transform hover:scale-[1.01]">
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigasi Tambahan -->
                <div class="flex flex-col sm:flex-row items-center justify-between pt-3 gap-2 text-xs">
                    @if (Route::has('register'))
                        <span class="text-gray-500 font-medium">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-bold text-[#2A3BF5] hover:underline">
                                Daftar Sekarang
                            </a>
                        </span>
                    @endif

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-gray-500 hover:text-[#2A3BF5] hover:underline font-medium">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <div class="pt-2 border-t border-gray-100 text-center">
                    <a href="{{ route('customer.home') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-[#2A3BF5] font-semibold transition">
                        <span>&larr;</span>
                        <span>Kembali ke Beranda StageTix</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
