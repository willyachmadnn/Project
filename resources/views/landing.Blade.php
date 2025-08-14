{{-- resources/views/landing.blade.php --}}
<x-layout>
  <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

  <main class="container mx-auto px-4">
    {{-- HERO background + teks (tetap) --}}
    <section id="hero" class="relative isolate overflow-visible select-none">
      <style>
        #hero{
          width:100vw; left:50%; transform:translateX(-50%); position:relative;
          min-height:100svh;
        }
        #hero .hero-bg{
          position:fixed; inset:0; z-index:-2; pointer-events:none;
          background-image:url('https://ppid.mojokertokab.go.id/images/sliders/1686640788-5985.jpg');
          background-size:cover; background-position:center; width:100vw; height:100svh;
        }
        #hero .hero-veil{ position:absolute; inset:0; z-index:-1;
          background: radial-gradient(1200px 500px at 50% 0%, rgba(2,6,23,.22), transparent),
                      linear-gradient(to bottom, rgba(17,24,39,.55), rgba(17,24,39,.30) 30%, rgba(17,24,39,.10) 60%, rgba(17,24,39,0));
          backdrop-filter: blur(0.5px);
        }
        .hero-inner{
          display:flex; align-items:flex-end; justify-content:center;
          min-height: clamp(320px, 52vh, 560px);
          padding: clamp(16px, 4vw, 32px); color:white; text-shadow: 0 1px 2px rgba(0,0,0,.35);
        }
      </style>

      <div class="hero-bg" aria-hidden="true"></div>
      <span class="hero-veil" aria-hidden="true"></span>

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

    {{-- sorotan kartu aktif diambil dari query ?status= --}}
    @php $kpiActive = request('status'); @endphp

    {{-- Kartu status (komponen) --}}
    <x-page
      :pending-agendas-count="$pendingAgendasCount ?? 0"
      :ongoing-agendas-count="$ongoingAgendasCount ?? 0"
      :finished-agendas-count="$finishedAgendasCount ?? 0"
      :kpi-active="$kpiActive" />
    {{-- Form + Kontrol + Tabel + Pagination (komponen) --}}
    <x-table :agendas="$agendas" />
  </main>

  @push('scripts')
    @vite('resources/js/landing.js')
  @endpush
</x-layout>
