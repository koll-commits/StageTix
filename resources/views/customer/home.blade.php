<x-layouts.customer title="StageTix • Platform Tiket Konser & Acara Terpercaya">
    <div class="space-y-12 pb-8">
        
        <!-- HERO BANNER SECTION (MAIN HEADING DENGAN GAMBAR ASLI FIGMA) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="relative overflow-hidden rounded-3xl shadow-xl border border-gray-200/80 bg-[#0B1E63]">
                <img src="{{ asset('images/hero-banner.jpg') }}" 
                     alt="Welcome to StageTix - Temukan Event Favoritmu di Platform ini!" 
                     class="w-full h-auto object-cover rounded-3xl block shadow-sm">
            </div>
        </section>

        <!-- EVENT CARDS GRID SECTION -->
        <section id="event" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-950 tracking-tight">Katalog Konser & Event</h2>
                    <p class="text-sm text-gray-600 mt-1 font-medium">Pilih konser idola Anda dan dapatkan tiket resmi sebelum kehabisan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-4 py-2 rounded-full bg-[#2A3BF5] text-white shadow-sm">
                        Semua Kategori (6)
                    </span>
                </div>
            </div>

            <!-- 6 Event Cards -->
            @php
                $mockEvents = [
                    [
                        'id' => 1,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                    [
                        'id' => 2,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                    [
                        'id' => 3,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                    [
                        'id' => 4,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                    [
                        'id' => 5,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                    [
                        'id' => 6,
                        'artist' => 'FEAST',
                        'title' => 'Konser Feast',
                        'tour' => 'Membangun & Menghancurkan Tour',
                        'date' => '14 November 2026',
                        'price' => 'Rp120.000',
                        'location' => 'JAKARTA • SENAYAN',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($mockEvents as $event)
                    <!-- Event Card Container -->
                    <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-200 group flex flex-col justify-between cursor-pointer">
                        
                        <!-- Event Poster Graphic (Dark red geometric FEAST banner matching Figma) -->
                        <div class="relative aspect-[16/10] w-full rounded-2xl overflow-hidden bg-gradient-to-br from-[#120505] via-[#240808] to-[#4A0A0A] p-5 flex flex-col justify-between text-white border border-red-950/40 shadow-inner">
                            <!-- Geometric abstract accents in top-right -->
                            <div class="absolute -right-6 -top-6 w-28 h-28 bg-red-600/30 rounded-full blur-xl pointer-events-none"></div>
                            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-red-900/40 via-red-950/20 to-transparent pointer-events-none"></div>

                            <!-- Poster Subhead -->
                            <div class="relative z-10 flex items-center justify-between">
                                <span class="text-[9px] font-bold tracking-widest uppercase text-red-300">OFFICIAL STAGETIX CONCERT</span>
                                <span class="w-2 h-2 rounded-full bg-[#D6FF3D]"></span>
                            </div>

                            <!-- Poster Big Typography -->
                            <div class="relative z-10 my-auto">
                                <h3 class="text-3xl font-black tracking-widest text-white group-hover:scale-105 transition-transform duration-200">
                                    {{ $event['artist'] }}
                                </h3>
                                <p class="text-[11px] text-gray-300 font-medium tracking-wide mt-1">
                                    {{ $event['tour'] }}
                                </p>
                            </div>

                            <!-- Poster Meta Foot -->
                            <div class="relative z-10 pt-2 border-t border-white/10 flex items-center justify-between text-[9px] text-gray-400 font-mono">
                                <span>19:00 WIB</span>
                                <span>{{ $event['location'] }}</span>
                            </div>
                        </div>

                        <!-- Event Info -->
                        <div class="pt-5 px-1 pb-1">
                            <h4 class="font-black text-gray-900 text-xl group-hover:text-[#2A3BF5] transition-colors">
                                {{ $event['title'] }}
                            </h4>
                            <p class="text-xs sm:text-sm text-gray-500 font-semibold mt-1">
                                {{ $event['date'] }}
                            </p>
                            
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block">Mulai dari</span>
                                    <span class="text-lg sm:text-xl font-black text-gray-950">{{ $event['price'] }}</span>
                                </div>

                                <!-- Button Only (10% #D6FF3D) -->
                                <button type="button" 
                                        class="px-5 py-2.5 bg-[#D6FF3D] hover:bg-[#c7f428] text-black rounded-xl text-xs font-black uppercase tracking-wider shadow-md shadow-[#D6FF3D]/30 transition transform hover:scale-105">
                                    Beli Tiket
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="tentang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-3xl p-8 sm:p-12 border border-gray-200/90 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8 text-gray-900">
                <div class="max-w-xl space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-[#2A3BF5] bg-[#2A3BF5]/10 px-3 py-1 rounded-full">
                        Tentang StageTix
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black text-gray-950">
                        Partner Resmi Tiket Konser & Festival Terpercaya
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">
                        StageTix menghubungkan penikmat musik dengan panggung spektakuler musisi impian. Sistem transaksi terenkripsi dengan validasi QR-gate berkecepatan tinggi menjamin pengalaman menonton konser tanpa hambatan.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <!-- Button Only (10% #D6FF3D) -->
                    <a href="#hubungi" class="text-center px-8 py-3.5 rounded-full bg-[#D6FF3D] hover:bg-[#c7f428] text-black text-sm font-black uppercase tracking-wider shadow-md shadow-[#D6FF3D]/20 transition transform hover:-translate-y-0.5">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="hubungi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-gray-200/90 shadow-sm text-center max-w-2xl mx-auto space-y-4 text-gray-900">
                <h4 class="text-2xl font-black text-gray-950">Punya Pertanyaan Seputar Tiket?</h4>
                <p class="text-xs sm:text-sm text-gray-600 font-medium">
                    Tim dukungan pelanggan kami siap membantu Anda 24/7 untuk pemesanan tiket, pengembalian, dan verifikasi gerbang venue.
                </p>
                <div class="pt-2 flex justify-center">
                    <!-- Button Only (10% #D6FF3D) -->
                    <a href="mailto:support@stagetix.id" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-black bg-[#D6FF3D] hover:bg-[#c7f428] px-6 py-3 rounded-xl shadow-md transition transform hover:scale-105">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <span>support@stagetix.id</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.customer>
