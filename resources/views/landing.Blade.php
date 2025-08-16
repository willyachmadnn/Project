{{-- File: resources/views/landing.blade.php --}}

<x-layout>
    <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

    <div class="hero-bg" aria-hidden="true"></div>
    <span class="hero-veil" aria-hidden="true"></span>

    <main id="landing-page-wrapper">
        <section class="hero-spacer"></section>

        <div class="hero-text-content">
            <div class="slide-in-wrapper">
                <h1 id="hero-title"
                    class="animate-item text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-white text-shadow">
                    Tentang Portal E-Agenda
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
    @endpush
</x-layout>