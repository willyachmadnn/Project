{{-- File: resources/views/landing.blade.php --}}

<x-layout>
    <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

    <div class="hero-bg" aria-hidden="true"></div>
    <span class="hero-veil" aria-hidden="true"></span>
    
    {{-- Alert untuk menyarankan penggunaan Google Chrome --}}
    <div id="browser-alert" class="fixed bottom-4 right-4 bg-white/90 backdrop-blur-sm text-gray-800 px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm transform transition-all duration-500 translate-y-full opacity-0 flex items-start">
        <div class="flex-shrink-0 mr-3">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-11h-2v-2h2v2zm0 8h-2v-6h2v6z"></path>
            </svg>
        </div>
        <div>
            <p class="font-medium">Pengalaman Terbaik</p>
            <p class="text-sm mt-1">Untuk pengalaman terbaik, kami menyarankan Anda menggunakan browser Google Chrome.</p>
            <button id="close-alert" class="mt-2 text-xs text-blue-600 hover:text-blue-800 font-medium">Tutup</button>
        </div>
    </div>

    <main id="landing-page-wrapper">
        <section class="hero-spacer"></section>

        <div class="hero-text-content">
            <div class="slide-in-wrapper">
                <h1 id="hero-title"
                                                                                                                                                        class="animate-item text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight mb-3 sm:mb-4 text-white text-shadow leading-tight">
                    Sistem Informasi E-Agenda Pemerintah Kabupaten Mojokerto 
                </h1>
            </div>
            
            <div id="hero-description"
                 class="text-base sm:text-lg md:text-xl leading-relaxed opacity-95 text-white text-shadow space-y-3">
                <p class="animate-item">
                    Portal E-Agenda ini merupakan platform digital resmi yang dikelola oleh Pemerintah Kabupaten Mojokerto. Sistem ini dirancang untuk menyajikan informasi jadwal kegiatan pimpinan dan pemerintah daerah secara terpusat, akurat, dan transparan kepada seluruh masyarakat dan pihak terkait.
                </p>
            </div>
        </div>

        <section class="content-body">
            {{-- PERUBAHAN: Menambahkan div pembungkus dengan ID untuk target AJAX --}}
            <div id="ajax-content-wrapper" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                @php $kpiActive = request('status'); @endphp
                
                <div id="kpi-cards" class="mb-8"> 
                    <x-page
                        :pending-agendas-count="$pendingAgendasCount ?? 0"
                        :ongoing-agendas-count="$ongoingAgendasCount ?? 0"
                        :finished-agendas-count="$finishedAgendasCount ?? 0"
                        :kpi-active="$kpiActive" />
                </div>

                @auth('admin')
                    {{-- Tampilan untuk pengguna yang sudah login --}}
                    <x-dashboard :agendas="$agendas" />
                @else
                    {{-- Tampilan untuk pengguna yang belum login (tamu) --}}
                    <x-table :agendas="$agendas"/>
                @endauth

            </div> {{-- Akhir dari #ajax-content-wrapper --}}
        </section>
    </main>
    
    @push('styles')
        @vite('resources/css/landing.css')
    @endpush

    @push('scripts')
        @vite('resources/js/landing.js')
        <script>
            // Script untuk menampilkan dan menyembunyikan alert browser
            document.addEventListener('DOMContentLoaded', function() {
                const browserAlert = document.getElementById('browser-alert');
                const closeAlert = document.getElementById('close-alert');
                const isChrome = /Chrome/.test(navigator.userAgent) && !/Chromium|Edge|Edg/.test(navigator.userAgent);
                
                // Jika bukan Chrome, tampilkan alert setelah 1.5 detik
                if (!isChrome) {
                    setTimeout(function() {
                        browserAlert.classList.remove('translate-y-full', 'opacity-0');
                        browserAlert.classList.add('translate-y-0', 'opacity-100');
                    }, 1500);
                }
                
                // Tutup alert saat tombol ditutup
                closeAlert.addEventListener('click', function() {
                    browserAlert.classList.remove('translate-y-0', 'opacity-100');
                    browserAlert.classList.add('translate-y-full', 'opacity-0');
                    
                    // Simpan di localStorage bahwa alert sudah ditutup
                    localStorage.setItem('browser-alert-closed', 'true');
                });
                
                // Cek apakah alert sudah pernah ditutup sebelumnya
                if (localStorage.getItem('browser-alert-closed') === 'true') {
                    browserAlert.classList.add('hidden');
                }
            });
        </script>
    @endpush
</x-layout>