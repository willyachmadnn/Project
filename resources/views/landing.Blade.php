{{-- resources/views/landing.blade.php --}}
<x-layout>
  <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

  {{-- 
    The background and veil elements are moved here, outside of the #hero section. 
    This prevents the parent's 'transform' style from interfering with their 'position: fixed'.
    Elemen latar belakang dan selubung dipindahkan ke sini, di luar bagian #hero.
    Ini mencegah gaya 'transform' dari elemen induk mengganggu 'position: fixed' mereka.
  --}}
  <div class="hero-bg" aria-hidden="true"></div>
  <span class="hero-veil" aria-hidden="true"></span>

  <main class="container mx-auto px-4">
    {{-- HERO background + teks (tetap) --}}
    <section id="hero" class="relative isolate overflow-visible select-none">
      <style>
        #hero{
          /* The full-width transform hack is no longer needed on this container.
            Trik transform untuk lebar penuh tidak lagi diperlukan pada kontainer ini.
          */
          position:relative;
          min-height:100svh;
        }

        /* The .hero-bg rule is now independent and correctly uses position:fixed.
          Aturan .hero-bg sekarang independen dan menggunakan position:fixed dengan benar.
        */
        .hero-bg{
          position:fixed;
          inset:0; 
          z-index:-2; 
          pointer-events:none;
          background-image:url('https://ppid.mojokertokab.go.id/images/sliders/1686640788-5985.jpg');
          background-size:cover; 
          background-position:center; 
          width:100vw; 
          height:100svh;
        }

        /* The veil must also be fixed to stay with the background.
          Selubung juga harus diatur fixed agar tetap bersama latar belakang.
        */
        .hero-veil{ 
          position:fixed; 
          inset:0; 
          z-index:-1;
          background: radial-gradient(1200px 500px at 50% 0%, rgba(2,6,23,.22), transparent),
                      linear-gradient(to bottom, rgba(17,24,39,.55), rgba(17,24,39,.30) 30%, rgba(17,24,39,.10) 60%, rgba(17,24,39,0));
          backdrop-filter: blur(0.5px);
        }
        
        .hero-inner{
          display:flex; 
          align-items:flex-end; 
          justify-content:center;
          min-height: clamp(320px, 52vh, 560px);
          padding: clamp(16px, 4vw, 32px); 
          color:white; 
          text-shadow: 0 1px 2px rgba(0,0,0,.35);
          margin-top: 5%;
        }
      </style>

      {{-- The background elements are no longer here. --}}

      <div class="hero-inner">
        <div class="max-w-5xl">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-center">
            Tentang Portal E-Agenda
          </h1>
          <p class="text-base sm:text-lg md:text-xl leading-relaxed opacity-95 text-center">
            Portal E-Agenda ini merupakan platform digital resmi yang dikelola oleh Pemerintah Kabupaten Mojokerto.
            Sistem ini dirancang untuk menyajikan informasi jadwal kegiatan pimpinan dan pemerintah daerah secara
            terpusat, akurat, dan transparan kepada seluruh masyarakat dan pihak terkait.
          </p>
        </div>
      </div>
    </section>
  </main>

    {{-- sorotan kartu aktif diambil dari query ?status= --}}
    @php $kpiActive = request('status'); @endphp

    {{-- Kartu status (komponen) --}}
    <x-page
      :pending-agendas-count="$pendingAgendasCount ?? 0"
      :ongoing-agendas-count="$ongoingAgendasCount ?? 0"
      :finished-agendas-count="$finishedAgendasCount ?? 0"
      :kpi-active="$kpiActive" />
    {{-- Form + Kontrol + Tabel + Pagination (komponen) --}}
    <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6 mt-8">
      <x-table :agendas="$agendas" />
    </div>

  

  @push('scripts')
    @vite('resources/js/landing.js')
  @endpush
</x-layout>
