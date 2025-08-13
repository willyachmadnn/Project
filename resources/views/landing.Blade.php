<x-layout>
    <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

<main class="container mx-auto px-4">

        {{-- gambar --}}
<section id="hero"
  class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] min-h-[calc(100vh-4rem)] overflow-hidden">
  <img
    src="https://ppid.mojokertokab.go.id/images/sliders/1686640788-5985.jpg"
    alt="Banner Mojokerto — Portal E-Agenda"
    class="absolute inset-0 h-full w-full object-cover object-center
           sm:object-[50%_40%] md:object-[50%_35%]"
    loading="eager" fetchpriority="high" decoding="async" />
  <div class="absolute inset-0 bg-black/30"></div>

  <div class="relative z-10 flex h-full items-center justify-center text-center text-white px-4 pt-50">
    <div>
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Tentang Portal E-Agenda</h2>
      <p class="text-base sm:text-lg md:text-xl max-w-5xl mx-auto">
        Portal E-Agenda ini merupakan platform digital resmi yang dikelola oleh Pemerintah Kabupaten Mojokerto.
        Sistem ini dirancang untuk menyajikan informasi jadwal kegiatan pimpinan dan pemerintah daerah secara
        terpusat, akurat, dan transparan kepada seluruh masyarakat dan pihak terkait.
      </p>
    </div>
  </div>
</section>
{{-- CARD KPI: template Tailwind + shadow, overlap ringan di desktop --}}
<section id="kpis" class="relative z-20 px-4 mt-6 md:-mt-10 lg:-mt-14">
  <div class="mx-auto max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
      <div class="bg-white rounded-2xl p-6 shadow-2xl shadow-black/10 ring-1 ring-black/5">
        <h3 class="text-sm font-medium text-slate-700">Agenda Menunggu</h3>
        <p class="text-3xl sm:text-4xl font-bold text-blue-600 mt-2">{{ $pendingAgendasCount ?? 0 }}</p>
      </div>

      <div class="bg-white rounded-2xl p-6 shadow-2xl shadow-black/10 ring-1 ring-black/5">
        <h3 class="text-sm font-medium text-slate-700">Agenda Berlangsung</h3>
        <p class="text-3xl sm:text-4xl font-bold text-green-600 mt-2">{{ $ongoingAgendasCount ?? 0 }}</p>
      </div>

      <div class="bg-white rounded-2xl p-6 shadow-2xl shadow-black/10 ring-1 ring-black/5">
        <h3 class="text-sm font-medium text-slate-700">Agenda Berakhir</h3>
        <p class="text-3xl sm:text-4xl font-bold text-red-600 mt-2">{{ $finishedAgendasCount ?? 0 }}</p>
      </div>
    </div>
  </div>
</section>


        {{-- Slider Filter Kartu Status --}}
        <section class="mb-8 p-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <label for="timeRange" class="block text-sm font-medium text-gray-700 mb-2">Filter Kartu Status</label>
            <input type="range" id="timeRange" min="1" max="5" value="{{ request('timeRange', '5') }}" class="w-full">
            <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                <span>Hari Ini & Mendatang</span>
                <span>7 Hari Terakhir & Mendatang</span>
                <span>30 Hari Terakhir & Mendatang</span>
                <span>1 Tahun Terakhir & Mendatang</span>
                <span>Semua Waktu</span>
            </div>
        </section>

        {{-- ==================== Form Filter + Tabel ==================== --}}
        <form action="{{ route('landing') }}" method="GET" id="filterForm" data-ajax="true">
            <input type="hidden" name="timeRange" value="{{ request('timeRange', 5) }}">
            <input type="hidden" name="perPage" value="{{ request('perPage', request('per_page', 10)) }}">
            <input type="hidden" id="qMirror" name="search" value="{{ request('search', request('q')) }}">
            <input type="hidden" id="pageHidden" name="page" value="{{ request('page', 1) }}">

            {{-- Controls --}}
<section class="mx-auto max-w-7xl px-2 sm:px-0">
  <div id="controls" class="flex flex-wrap items-center gap-2 md:gap-3">

    <!-- SHOW -->
    <div id="showWrap"
         class="inline-flex items-center gap-2 rounded-lg bg-white px-2 py-1
                ring-1 ring-inset ring-red-600/70 hover:ring-red-600/60
                focus-within:ring-1 focus-within:ring-red-600 shrink-0">
      <span class="text-sm font-medium text-slate-700">Show:</span>
      <div class="relative">
        <select id="perPageSelect" name="per_page"
                class="appearance-none h-7 w-16 rounded-md
                       border border-red-700/50 bg-transparent
                       pl-3 pr-7 text-sm font-medium leading-tight text-slate-700
                       focus:outline-none focus-visible:outline-none
                       focus:ring-1 focus:ring-red-600 focus:border-red-600 focus:shadow-none">
          @foreach ([10,25,50,100] as $n)
            <option value="{{ $n }}" @selected((int)request('per_page', request('perPage', 10)) === $n)>{{ $n }}</option>
          @endforeach
        </select>
        <svg class="pointer-events-none absolute right-1 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-600"
             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
        </svg>
      </div>
    </div>

    <!-- SEARCH (FLEXIBLE) -->
       <div class="relative flex-none ml-auto min-w-[18rem] md:min-w-[8rem]">
  <!-- PENTING: w-full, BUKAN w-[28rem] -->
  <input id="qInput" type="text" name="q"
         value="{{ request('q', request('search')) }}"
         placeholder="Cari Nama Agenda, Tempat"
       class="w-[14rem] sm:w-[18rem] md:w-[22rem] lg:w-[24rem] max-w-full
              rounded-md border border-red-700 pl-9 pr-3 py-2
              text-sm text-slate-800 placeholder-slate-400
              focus:outline-none focus-visible:outline-none
              focus:ring-1 focus:ring-red-700 focus:border-red-700 focus:shadow-none">
  <svg class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-red-700"
       fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 11.15 11.15z" />
  </svg>
</div>


    <!-- FILTER -->
    @php $currentStatus = strtolower((string)request('status')); @endphp
    <div class="relative shrink-0">
      <select id="statusSelect" name="status"
              class="appearance-none cursor-pointer rounded-md bg-red-700 pl-8 pr-8 py-2
                     text-sm font-medium text-white shadow
                     hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800">
        <option value="" @selected($currentStatus==='')>Semua</option>
        <option value="berlangsung" @selected($currentStatus==='berlangsung')>Berlangsung</option>
        <option value="selesai" @selected(in_array($currentStatus,['selesai','berakhir']))>Berakhir</option>
        <option value="menunggu" @selected(in_array($currentStatus,['menunggu','pending']))>Menunggu</option>
      </select>
      <svg class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-white"
           viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M3 5h18v2H3V5zm4 6h10v2H7v-2zm-2 6h14v2H5v-2z"/>
      </svg>
      <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-white">▾</span>
    </div>

  </div>
</section>


            {{-- ============ TABLE + PAGINATION (dibungkus #tableWrap untuk AJAX) ============ --}}
<section id="tableWrap" class="mx-auto mt-4 max-w-7xl px-2 sm:px-1">
  <div class="rounded-lg bg-white shadow">
    <!-- izinkan scroll saat sempit -->
    <div class="overflow-x-auto">
      <table
        class="w-full table-fixed text-xs sm:text-sm
               /* paksa rata tengah (override CSS lain) */
               ![&_th]:text-center ![&_td]:text-center ![&_td]:align-middle
               /* kalau isi td dibungkus div, tetap center */
               [&_td>div]:flex [&_td>div]:items-center [&_td>div]:justify-center
               /* link/badge juga center */
               [&_td>a]:inline-flex [&_td>a]:items-center [&_td>a]:justify-center [&_td>a]:mx-auto
               [&_td>span]:block [&_td>span]:mx-auto">

        {{-- Lebar kolom --}}
        <colgroup>
          <col class="w-12 sm:w-14 md:w-16" />   {{-- NO --}}
          <col class="w-[24%]" />                 {{-- Nama Agenda --}}
          <col class="w-[16%]" />                 {{-- Tempat --}}
          <col class="w-[16%]" />                 {{-- Tanggal --}}
          <col class="w-[12%]" />                 {{-- Waktu --}}
          <col class="w-[14%]" />                 {{-- PIC --}}
          <col class="w-[14%]" />                 {{-- Dihadiri --}}
          <col class="w-[12%]" />                 {{-- Status --}}
        </colgroup>

        <thead>
          <tr>
            <th class="bg-red-800 px-2 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white rounded-tl-md">No</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">Nama Agenda</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">Tempat</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">Tanggal</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">Waktu</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">PIC</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white">Dihadiri</th>
            <th class="bg-red-800 px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-white rounded-tr-md">Status</th>
          </tr>
        </thead>
                    <tbody id="agendaBody">
                            @forelse($agendas as $agenda)
                                @php
                                    $rowIndex = ($agendas->firstItem() ?? 1) + $loop->index;

                                    $rawStatus = strtolower(trim((string)($agenda->status ?? '')));
                                    $labelStatus = [
                                        'berlangsung' => 'berlangsung',
                                        'selesai'     => 'berakhir',
                                        'finished'    => 'berakhir',
                                        'done'        => 'berakhir',
                                        'pending'     => 'menunggu',
                                    ][$rawStatus] ?? ($rawStatus ?: '-');

                                    $badgeClass = match ($labelStatus) {
                                        'berlangsung' => 'bg-green-100 text-green-800 ring-green-600/20',
                                        'berakhir'    => 'bg-red-100 text-red-700 ring-red-600/20',
                                        'menunggu'    => 'bg-blue-100 text-blue-700 ring-blue-600/20',
                                        default       => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                                    };

                                    $tgl = $agenda->tanggal ?? $agenda->date ?? null;
                                    $tglText = is_object($tgl) && method_exists($tgl,'translatedFormat') ? $tgl->translatedFormat('d F Y') : ($tgl ?: '-');
                                    $monthNum  = is_object($tgl) && method_exists($tgl,'format') ? $tgl->format('m') : '';
                                    $yearNum   = is_object($tgl) && method_exists($tgl,'format') ? $tgl->format('Y') : '';
                                    $monthName = is_object($tgl) && method_exists($tgl,'translatedFormat') ? $tgl->translatedFormat('F') : '';


                                    // --- WAKTU -> tampil HH:mm
                                    $fmt = function ($v) {
                                        if (blank($v)) return null;
                                        if ($v instanceof \DateTimeInterface) {
                                            return \Illuminate\Support\Carbon::parse($v)->format('H:i');
                                        }
                                        // Ambil HH:MM dari string (buang detik jika ada)
                                        if (preg_match('/\b(\d{1,2}):(\d{2})(?::\d{2})?\b/', (string)$v, $m)) {
                                            return sprintf('%02d:%02d', $m[1], $m[2]);
                                        }
                                        try { return \Illuminate\Support\Carbon::parse((string)$v)->format('H:i'); }
                                        catch (\Throwable $e) { return null; }
                                    };
                                    // ambil raw
                                    $wMulaiRaw   = $agenda->waktu_mulai ?? $agenda->jam_mulai ?? $agenda->pukul_mulai ?? $agenda->start_time ?? null;
                                    $wSelesaiRaw = $agenda->waktu_selesai ?? $agenda->jam_selesai ?? $agenda->pukul_selesai ?? $agenda->end_time ?? null;
                                    $wSatuRaw    = $agenda->waktu ?? $agenda->jam ?? $agenda->pukul ?? null;

                                    // format ke HH:mm
                                    $wMulai   = $fmt($wMulaiRaw);
                                    $wSelesai = $fmt($wSelesaiRaw);

                                    // jika $wSatu berisi rentang "xx:xx - yy:yy", format keduanya
                                    $wSatu = (filled($wSatuRaw) && preg_match('/[-–]/', (string)$wSatuRaw))
                                        ? collect(preg_split('/\s*[-–]\s*/', (string)$wSatuRaw))->map($fmt)->filter()->implode(' - ')
                                        : $fmt($wSatuRaw);

                                    // hasil akhir (pakai nama variabel yang sama)
                                    $waktuText = ($wMulai || $wSelesai)
                                        ? collect([$wMulai, $wSelesai])->filter()->implode(' - ')
                                        : ($wSatu ?? '-');

                                    $picText = $agenda->admin->opd_admin ?? $agenda->admin_id;
                                    $picCandidates = ['pic','PIC','pic_name','pic_text','nama_pic','penanggung_jawab','penanggungJawab','penanggung_jawab_nama','pj','pj_nama','petugas','petugas_nama','koordinator','koordinator_nama','bagian','bidang','unit','opd','skpd','dinas','instansi','organisasi','pic.nama','pic.name','user_pic.name','user.name','creator.name','created_by_name','dibuat_oleh'];
                                    foreach ($picCandidates as $key) {
                                        $val = data_get($agenda, $key);
                                        if (is_string($val) && trim($val) !== '') { $picText = trim($val); break; }
                                        if (is_object($val)) { $n = data_get($val,'name') ?? data_get($val,'nama') ?? data_get($val,'title'); if (is_string($n) && trim($n) !== '') { $picText = trim($n); break; } }
                                        if (is_array($val)) {
                                            $n = $val['name'] ?? $val['nama'] ?? $val['title'] ?? null;
                                            if (is_string($n) && trim($n) !== '') { $picText = trim($n); break; }
                                            $flat=[]; foreach ($val as $vv){
                                                if (is_string($vv) && trim($vv)!=='') $flat[]=trim($vv);
                                                elseif (is_array($vv)) { $nn=$vv['name']??$vv['nama']??$vv['title']??null; if (is_string($nn)&&trim($nn)!=='') $flat[]=trim($nn); }
                                                elseif (is_object($vv)) { $nn=data_get($vv,'name')??data_get($vv,'nama')??data_get($vv,'title'); if (is_string($nn)&&trim($nn)!=='') $flat[]=trim($nn); }
                                            }
                                            if (!empty($flat)) { $picText = implode(', ', array_slice($flat,0,2)); break; }
                                        }
                                    }
                                    if (is_numeric($picText)) { $picText = '-'; }

                                    $dihadiriText = data_get($agenda,'dihadiri') ?? data_get($agenda,'dihadiri_oleh') ?? data_get($agenda,'dihadiri_oleh_text') ?? '-';

                                    $indexParts = [
                                        $agenda->nama_agenda ?? $agenda->title ?? '',
                                        $agenda->tempat ?? $agenda->lokasi ?? '',
                                        $tglText, $monthName, $monthNum, $yearNum,
                                        $wMulai, $wSelesai, $wSatu,
                                        $picText, $dihadiriText,
                                        $labelStatus, $rawStatus,
                                    ];
                                    $searchIndex = mb_strtolower(implode(' ', array_filter($indexParts)));
                                @endphp

                                <tr class="odd:bg-white even:bg-rose-50" data-index="{{ $searchIndex }}" data-status="{{ $labelStatus }}">
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800 align-top">{{ $rowIndex }}.</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900">{{ $agenda->nama_agenda ?? $agenda->title ?? '-' }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800">{{ $agenda->tempat ?? $agenda->lokasi ?? '-' }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800">{{ $tglText }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800">{{ $waktuText }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800">{{ $picText }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-800">{{ $dihadiriText }}</td>
                                    <td class="border-y border-slate-200 px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeClass }}">{{ ucfirst($labelStatus) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-4 py-10 text-center text-sm text-slate-700">Data tidak ditemukan.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

{{-- Pagination --}}
{{-- Pagination --}}
@if (method_exists($agendas, 'links'))
  <div class="flex flex-col items-center gap-3 border-t border-slate-200 px-2 py-6">

    {{-- Info ringkas (sembunyikan di layar kecil) --}}
    <p class="text-sm text-slate-600 hidden sm:block">
      Menampilkan <span class="font-medium">{{ $agendas->firstItem() }}</span> –
      <span class="font-medium">{{ $agendas->lastItem() }}</span> dari
      <span class="font-medium">{{ $agendas->total() }}</span> Data
    </p>

    {{-- NAV pagination: tengah + aktif maroon --}}
    <div
      class="pagination-ui
        [&_nav]:flex [&_nav]:items-center [&_nav]:justify-center [&_nav]:gap-2

        [&_a.relative.inline-flex.items-center]:rounded-lg
        [&_a.relative.inline-flex.items-center]:border [&_a.relative.inline-flex.items-center]:border-slate-300
        [&_a.relative.inline-flex.items-center]:px-3 [&_a.relative.inline-flex.items-center]:py-2
        [&_a.relative.inline-flex.items-center]:text-slate-700
        [&_a.relative.inline-flex.items-center:hover]:bg-slate-100

        [&_[aria-current='page']>span]:!bg-[#800000]
        [&_[aria-current='page']>span]:!text-white
        [&_[aria-current='page']>span]:!border-transparent
        [&_[aria-current='page']>span]:rounded-lg

        [&_span[aria-disabled='true']>span]:rounded-lg
        [&_span[aria-disabled='true']>span]:border [&_span[aria-disabled='true']>span]:border-slate-300
        [&_span[aria-disabled='true']>span]:text-slate-400
      "
    >
      {{ $agendas->onEachSide(0)->withQueryString()->links('pagination::tailwind') }}
      {{-- Jika file view kamu ada di resources/views/pagination/tailwind.blade.php gunakan:
           {{ $agendas->onEachSide(0)->withQueryString()->links('pagination.tailwind') }} --}}
    </div>
  </div>
@endif



                </div>
            </section>
        </form>
    </main>

@push('scripts')
<script>
(function () {
  const FORM_SEL = '#filterForm';
  const WRAP_SEL = '#tableWrap';
  const BODY_SEL = '#agendaBody';
  let ctrl = null;        // AbortController
  let typingTimer = null; // debounce kalau nanti mau tambahkan server sync (sekarang dimatikan)

  const form  = () => document.querySelector(FORM_SEL);
  const wrap  = () => document.querySelector(WRAP_SEL);
  const tbody = () => document.querySelector(BODY_SEL);

  // ============== NEW: hapus ringkasan bawaan dari links() ==============
  // Default view Tailwind Laravel menaruh ringkasan "Menampilkan ... hasil"
  // di dalam container yang kamu bungkus dengan .shrink-0. Kita buang <p> itu.
  function removeLinksSummary(ctx){
    const root = ctx || document;
    // cukup aman karena <p> kecil bawaan pagination berada di dalam .shrink-0
    root.querySelectorAll('#tableWrap .shrink-0 p.text-sm').forEach(el => el.remove());
  }
  // ======================================================================

  // ================= helpers =================
  function setPage(n){
    const f = form(); if (!f) return;
    let p = f.querySelector('input[name="page"]');
    if (!p) { p = document.createElement('input'); p.type='hidden'; p.name='page'; f.appendChild(p); }
    p.value = String(n || 1);
  }

  function normalizeStatus(val){
    val = (val || '').toString().toLowerCase().trim();
    if (['selesai','berakhir','finished','done'].includes(val)) return 'berakhir';
    if (['berlangsung','ongoing'].includes(val))                 return 'berlangsung';
    if (['menunggu','pending'].includes(val))                    return 'menunggu';
    return '';
  }

  function buildServerParams(){
    const f = form(); const fd = new FormData(f);
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp);
    fd.delete('per_page');
    fd.delete('q'); fd.delete('search');
    if (!fd.get('status')) fd.delete('status');
    return fd;
  }

  function urlForServer(){
    const f = form(); const fd = buildServerParams();
    const qs = new URLSearchParams(fd).toString();
    return f.action + (qs ? '?' + qs : '');
  }

  function urlForState(){
    const f = form(); const fd = new FormData(f);
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp); fd.delete('per_page');
    const qVal = f.querySelector('#qInput')?.value || '';
    if (qVal.trim() !== '') { fd.set('q', qVal); fd.set('search', qVal); }
    else { fd.delete('q'); fd.delete('search'); }
    if (!fd.get('status')) fd.delete('status');
    const qs = new URLSearchParams(fd).toString();
    return f.action + (qs ? '?' + qs : '');
  }

  function updateUrlState(){
    history.replaceState({}, '', urlForState());
  }

  // ================= client-side live filter =================
  function liveFilter(term, statusVal){
    const body = tbody(); if (!body) return;

    const q = (term || '').toString().trim().toLowerCase();
    const parts = q ? q.split(/\s+/) : [];
    const sNorm = normalizeStatus(statusVal);

    [...body.querySelectorAll('tr')].forEach(tr => {
      const idx = (tr.dataset.index || '').toLowerCase();
      const rowStatus = normalizeStatus(tr.dataset.status || '');
      const matchSearch = parts.length === 0 ? true : parts.every(p => idx.includes(p));
      const matchStatus = !sNorm ? true : rowStatus === sNorm;
      tr.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
  }

  // ================= AJAX replace table+pagination =================
  function ajaxLoad(fetchUrl){
    if (!wrap()) return;
    if (ctrl) ctrl.abort();
    ctrl = new AbortController();

    wrap().classList.add('opacity-60');

    fetch(fetchUrl, {
      signal: ctrl.signal,
      credentials: 'same-origin',
      cache: 'no-store',
      headers: { 'Accept': 'text/html' }
    })
    .then(r => r.text())
    .then(html => {
      if (ctrl.signal.aborted) return;
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const nextWrap = doc.querySelector(WRAP_SEL);
      if (!nextWrap || !wrap()) return;

      wrap().replaceWith(nextWrap);

      // Hapus ringkasan bawaan pagination dari links()
      removeLinksSummary(document);

      // setelah ganti table, re-bind pagination dan apply filter lokal lagi
      bindInsideWrap();

      const qVal = form()?.querySelector('#qInput')?.value || '';
      const sVal = form()?.querySelector('#statusSelect')?.value || '';
      liveFilter(qVal, sVal);

      updateUrlState();
    })
    .catch(e => { if (e.name !== 'AbortError') console.error(e); })
    .finally(() => { document.querySelector(WRAP_SEL)?.classList.remove('opacity-60'); });
  }

  function bindInsideWrap(){
    const container = document.querySelector(WRAP_SEL);
    if (!container) return;

    // Pastikan ringkasan bawaan hilang juga bila ada
    removeLinksSummary(container);

    container.querySelectorAll('a[href*="page="]').forEach(a => {
      a.addEventListener('click', e => {
        e.preventDefault();
        const url = new URL(a.href, location.origin);
        setPage(url.searchParams.get('page') || 1);
        ajaxLoad(urlForServer());
      });
    });
  }

  // ================= bind controls =================
  function bind(){
    const f = form(); if (!f) return;

    // Hapus ringkasan bawaan saat initial load
    removeLinksSummary(document);

    // SHOW
    const perSel = f.querySelector('#perPageSelect');
    if (perSel){
      perSel.addEventListener('change', () => {
        const perMirror = f.querySelector('input[name="perPage"]');
        if (perMirror) perMirror.value = perSel.value;
        setPage(1);

        const q = f.querySelector('#qInput')?.value || '';
        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(q, s);

        ajaxLoad(urlForServer());
      });
    }

    // STATUS
    const statusSel = f.querySelector('#statusSelect');
    if (statusSel){
      statusSel.addEventListener('change', () => {
        setPage(1);
        const q = f.querySelector('#qInput')?.value || '';
        liveFilter(q, statusSel.value);
        ajaxLoad(urlForServer());
      });
    }

    // SEARCH — client-side only
    const qInput  = f.querySelector('#qInput');
    const qMirror = f.querySelector('#qMirror'); // hanya untuk keep state
    if (qInput){
      qInput.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

      qInput.addEventListener('input', () => {
        if (qMirror) qMirror.value = qInput.value;
        setPage(1);

        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(qInput.value, s);

        updateUrlState();
        // clearTimeout(typingTimer);
        // typingTimer = setTimeout(() => ajaxLoad(urlForServer()), 400);
      });
    }

    // Pagination awal
    bindInsideWrap();

    // Apply filter lokal sekali saat halaman diload (jika ada q/status di URL)
    const initQ = f.querySelector('#qInput')?.value || '';
    const initS = f.querySelector('#statusSelect')?.value || '';
    if (initQ || initS) liveFilter(initQ, initS);

    updateUrlState();
  }

  document.addEventListener('DOMContentLoaded', bind);
})();
</script>
@endpush


</x-layout>
