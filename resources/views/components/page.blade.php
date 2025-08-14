@props([
  'pendingAgendasCount' => 0,
  'ongoingAgendasCount' => 0,
  'finishedAgendasCount' => 0,
  'kpiActive' => null,
])

{{-- 
  Kode ini menggabungkan gaya kartu baru dengan fungsionalitas yang ada (garis warna, status aktif).
  Blok <style> telah dihapus dan digantikan sepenuhnya oleh kelas utilitas Tailwind.
--}}
<section class="relative z-20 mx-auto -mt-8 md:-mt-15 mb-10">
  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

    {{-- 1. Kartu Agenda Menunggu --}}
    <div @class([
        'relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]', // Gaya shadow dari contoh baru Anda
        'hover:shadow-xl hover:-translate-y-1', // Efek hover yang diminta
        'ring-0 ring-offset-0 ring-cyan-500' => in_array($kpiActive, ['menunggu', 'pending']), // Efek aktif
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-cyan-500"></div>
      
      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Menunggu</h3>
        <p class="mt-1 text-3xl font-bold text-cyan-600">{{ $pendingAgendasCount ?? 0 }}</p>
      </div>
    </div>

    {{-- 2. Kartu Agenda Berlangsung --}}
    <div @class([
        'relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]',
        'hover:shadow-xl hover:-translate-y-1',
        'ring-0 ring-offset-0 ring-green-600' => in_array($kpiActive, ['berlangsung', 'ongoing']),
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-green-600"></div>

      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Berlangsung</h3>
        <p class="mt-1 text-3xl font-bold text-green-700">{{ $ongoingAgendasCount ?? 0 }}</p>
      </div>
    </div>

    {{-- 3. Kartu Agenda Berakhir --}}
    <div @class([
        'relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200',
        '[box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)]',
        'hover:shadow-xl hover:-translate-y-1',
        'ring-0 ring-offset-0 ring-red-700' => in_array($kpiActive, ['selesai', 'berakhir', 'finished']),
    ])>
      {{-- Garis warna samping --}}
      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-red-700"></div>
      
      <div class="ml-3">
        <h3 class="text-slate-800 text-base font-semibold">Agenda Berakhir</h3>
        <p class="mt-1 text-3xl font-bold text-red-800">{{ $finishedAgendasCount ?? 0 }}</p>
      </div>
    </div>
    
  </div>
</section>