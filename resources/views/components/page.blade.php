@props([
  'pendingAgendasCount' => 0,
  'ongoingAgendasCount' => 0,
  'finishedAgendasCount' => 0,
  'kpiActive' => null,
])

<style>
  .modern-landing-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .modern-landing-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s ease;
    z-index: 1;
  }

  .modern-landing-card:hover::before {
    left: 100%;
  }

  .modern-landing-card:active {
    transform: translateY(0) scale(0.98) !important;
  }

  .modern-landing-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
  }
</style>

<section class="relative z-20 mx-auto -mt-8 md:-mt-15 mb-10">
  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

    {{-- 1. Kartu Agenda Menunggu --}}
    <div @class([
        'modern-landing-card relative bg-white p-6 rounded-lg',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]',
        'hover:shadow-2xl',
        'ring-0 ring-offset-0 ring-cyan-500' => in_array($kpiActive, ['menunggu', 'pending']),
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-cyan-500"></div>
      
      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Menunggu</h3>
        <p class="mt-1 text-3xl font-bold text-cyan-600">{{ $pendingAgendasCount ?? 0 }}</p>
        @auth('admin')
        <p class="mt-2 text-sm text-gray-700 font-medium">Menunggu jadwal pelaksanaan</p>
        @endauth
      </div>
    </div>

    {{-- 2. Kartu Agenda Berlangsung --}}
    <div @class([
        'modern-landing-card relative bg-white p-6 rounded-lg',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]',
        'hover:shadow-2xl',
        'ring-0 ring-offset-0 ring-green-600' => in_array($kpiActive, ['berlangsung', 'ongoing']),
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-green-600"></div>

      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Berlangsung</h3>
        <p class="mt-1 text-3xl font-bold text-green-700">{{ $ongoingAgendasCount ?? 0 }}</p>
        @auth('admin')
        <p class="mt-2 text-sm text-gray-700 font-medium">Agenda aktif saat ini</p>
        @endauth
      </div>
    </div>

    {{-- 3. Kartu Agenda Berakhir --}}
    <div @class([
        'modern-landing-card relative bg-white p-6 rounded-lg',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]',
        'hover:shadow-2xl',
        'ring-0 ring-offset-0 ring-red-700' => in_array($kpiActive, ['selesai', 'berakhir', 'finished']),
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-red-700"></div>
      
      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Berakhir</h3>
        <p class="mt-1 text-3xl font-bold text-red-800">{{ $finishedAgendasCount ?? 0 }}</p>
        @auth('admin')
        <p class="mt-2 text-sm text-gray-700 font-medium">Riwayat agenda terlaksana</p>
        @endauth
      </div>
    </div>
    
  </div>
</section>