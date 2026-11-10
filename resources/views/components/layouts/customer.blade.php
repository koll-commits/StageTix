@props(['title' => 'StageTix • Tiket Acara'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-[#F6F4EE] text-gray-900 flex flex-col font-sans antialiased selection:bg-[#D6FF3D] selection:text-black">

    <!-- TOP NAVBAR (BACKGROUND #2A3BF5, TEXT/PILL #F6F4EE, BUTTON #D6FF3D) -->
    <header class="bg-[#2A3BF5] text-[#F6F4EE] sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <a href="{{ route('customer.home') }}" class="flex items-center group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="StageTix" 
                         class="h-9 sm:h-10 w-auto object-contain transition transform group-hover:scale-105">
                </a>

                <!-- Pill-shaped Navigation Bar -->
                <nav class="hidden md:flex items-center bg-[#F6F4EE]/15 border border-[#F6F4EE]/25 rounded-full px-6 py-2.5 space-x-7 text-sm font-medium shadow-inner backdrop-blur-md">
                    <a href="{{ route('customer.home') }}" class="relative text-[#F6F4EE] font-bold flex flex-col items-center">
                        <span>Beranda</span>
                        <span class="w-7 h-1 bg-[#F6F4EE] rounded-full mt-0.5"></span>
                    </a>
                    <a href="#event" class="text-[#F6F4EE]/80 hover:text-[#F6F4EE] transition-colors duration-150">Event</a>
                    <a href="#tentang" class="text-[#F6F4EE]/80 hover:text-[#F6F4EE] transition-colors duration-150">Tentang</a>
                    <a href="#hubungi" class="text-[#F6F4EE]/80 hover:text-[#F6F4EE] transition-colors duration-150">Hubungi Kami</a>
                </nav>

                <!-- Action Items -->
                <div class="flex items-center gap-3">
                    <!-- Search Icon Button -->
                    <button type="button" 
                            id="search-btn"
                            class="w-10 h-10 rounded-full flex items-center justify-center text-[#F6F4EE] hover:bg-[#F6F4EE]/15 transition"
                            title="Cari Event">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>

                    <!-- User Account / Profile -->
                    @auth
                        <div class="relative" id="user-menu-container">
                            <button type="button" 
                                    id="user-menu-btn"
                                    class="w-10 h-10 rounded-full bg-[#F6F4EE] text-[#2A3BF5] font-black text-xs uppercase flex items-center justify-center shadow-md hover:bg-white transition">
                                <span>{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </button>
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-52 rounded-2xl bg-white text-gray-900 shadow-2xl border border-gray-100 py-2 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-700 font-medium">Profil Saya</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-50 text-[#2A3BF5] font-bold">Dashboard Admin</a>
                                @elseif(auth()->user()->isEoOperator())
                                    <a href="{{ route('eo.dashboard', ['eo' => 'demo']) }}" class="block px-4 py-2 hover:bg-gray-50 text-[#2A3BF5] font-bold">Portal Event Organizer</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 font-bold">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Button only (#D6FF3D) -->
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#D6FF3D] hover:bg-[#c7f428] text-black font-extrabold text-xs shadow-md shadow-[#D6FF3D]/20 transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span>Masuk</span>
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Menu Button -->
                    <button type="button" 
                            id="mobile-menu-btn"
                            class="md:hidden w-10 h-10 rounded-full flex items-center justify-center text-[#F6F4EE] hover:bg-[#F6F4EE]/15 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2 border-t border-[#F6F4EE]/15">
                <nav class="flex flex-col space-y-2 text-sm font-medium">
                    <a href="{{ route('customer.home') }}" class="bg-[#F6F4EE]/20 text-[#F6F4EE] px-3 py-2 rounded-lg font-bold">Beranda</a>
                    <a href="#event" class="text-[#F6F4EE]/80 hover:bg-[#F6F4EE]/15 px-3 py-2 rounded-lg transition">Event</a>
                    <a href="#tentang" class="text-[#F6F4EE]/80 hover:bg-[#F6F4EE]/15 px-3 py-2 rounded-lg transition">Tentang</a>
                    <a href="#hubungi" class="text-[#F6F4EE]/80 hover:bg-[#F6F4EE]/15 px-3 py-2 rounded-lg transition">Hubungi Kami</a>
                    @guest
                        <div class="pt-2 border-t border-[#F6F4EE]/15 flex gap-2">
                            <a href="{{ route('login') }}" class="flex-1 text-center py-2 bg-[#D6FF3D] rounded-xl text-black font-extrabold text-xs shadow-md">Masuk</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center py-2 border border-[#F6F4EE] rounded-xl text-[#F6F4EE] font-bold text-xs hover:bg-[#F6F4EE]/10">Daftar</a>
                        </div>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN BODY (BACKGROUND #F6F4EE) -->
    <main class="flex-1 w-full bg-[#F6F4EE]">
        {{ $slot }}
    </main>

    <!-- FOOTER (BACKGROUND #2A3BF5, TEXT #F6F4EE) -->
    <footer class="bg-[#2A3BF5] text-[#F6F4EE] py-12 px-6 border-t border-blue-800 mt-16 shadow-inner">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            <!-- Footer Brand -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <a href="{{ route('customer.home') }}" class="inline-block group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="StageTix" 
                         class="h-9 w-auto object-contain transition transform group-hover:scale-105">
                </a>
                <span class="text-[10px] tracking-widest text-[#F6F4EE]/70 font-semibold uppercase mt-2">TIKET KONSER TERPERCAYA</span>
            </div>

            <!-- Footer Links -->
            <nav class="flex flex-wrap justify-center gap-6 sm:gap-8 text-xs text-[#F6F4EE]/80 font-medium">
                <a href="#tentang" class="hover:text-[#F6F4EE] transition-colors">Tentang Kami</a>
                <a href="#syarat" class="hover:text-[#F6F4EE] transition-colors">Syarat & Ketentuan</a>
                <a href="#privasi" class="hover:text-[#F6F4EE] transition-colors">Kebijakan Privasi</a>
                <a href="#bantuan" class="hover:text-[#F6F4EE] transition-colors">Pusat Bantuan</a>
            </nav>

            <!-- Copyright -->
            <div class="text-xs text-[#F6F4EE]/60">
                &copy; {{ date('Y') }} StageTIX Inc. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- Minimal Script for Dropdowns / Mobile Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-dropdown');
            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
