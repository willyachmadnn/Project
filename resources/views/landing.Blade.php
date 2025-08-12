<x-layout>
    <x-slot:title>Agenda Pemerintah Kabupaten Mojokerto</x-slot:title>

    <main class="container mx-auto mt-4 px-4">
        {{-- Hero --}}
        <section
  class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] overflow-hidden mb-12 py-40"
>
  {{-- gambar full-bleed --}}
  <img
    src="https://ppid.mojokertokab.go.id/images/sliders/1686640788-5985.jpg"
    alt="Banner Mojokerto"
    class="absolute inset-0 h-full w-full object-cover"
    loading="lazy"
    decoding="async"
  />

  {{-- overlay gelap + blur seperti sebelumnya --}}
  <div class="absolute inset-0 backdrop-blur-sm bg-black/30"></div>

  {{-- konten --}}
  <div class="relative z-10 text-white text-center px-4">
    <h2 class="text-4xl font-bold mb-4">Tentang Portal E-Agenda</h2>
    <p class="text-lg max-w-2xl mx-auto">
      Portal E-Agenda ini merupakan platform digital resmi yang dikelola oleh Pemerintah Kabupaten Mojokerto.
      Sistem ini dirancang untuk menyajikan informasi jadwal kegiatan pimpinan dan pemerintah daerah secara
      terpusat, akurat, dan transparan kepada seluruh masyarakat dan pihak terkait
    </p>
  </div>
</section>


        {{-- Kartu Status --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500">
                <h3 class="text-lg font-medium text-gray-700">Agenda Menunggu</h3>
                <p id="pendingCount" class="text-4xl font-bold text-blue-600 mt-2">{{ $pendingAgendasCount }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
                <h3 class="text-lg font-medium text-gray-700">Agenda Berlangsung</h3>
                <p id="ongoingCount" class="text-4xl font-bold text-green-600 mt-2">{{ $ongoingAgendasCount }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500">
                <h3 class="text-lg font-medium text-gray-700">Agenda Berakhir</h3>
                <p id="finishedCount" class="text-4xl font-bold text-red-600 mt-2">{{ $finishedAgendasCount }}</p>
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
            <section class="mx-auto max-w-7xl px-2 sm:px-4">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    {{-- SHOW (container ala wireframe) --}}
                    <div id="showWrap" class="relative inline-flex">
                    <div class="inline-flex items-center gap-2 h-10 rounded-md border border-red-700 bg-white px-4 shadow-sm">
                        <span class="text-sm font-medium text-slate-800">Show:</span>

                        <select id="perPageSelect" name="per_page"
                                class="appearance-none h-8 bg-transparent border-0 pr-10 text-sm focus:outline-none focus:ring-0">
                        @foreach([10,25,50,100] as $n)
                            <option value="{{ $n }}" @selected((int)request('per_page', request('perPage', 10)) === $n)>{{ $n }}</option>
                        @endforeach
                        </select>
                    </div>

  <!-- caret kustom -->
  <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-red-700"
       viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
    <path fill-rule="evenodd"
          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
          clip-rule="evenodd" />
  </svg>
</div>


                    <div class="flex items-center gap-3">
                        {{-- Search --}}
                        <div class="relative">
                            <input id="qInput" type="text" name="q"
                                   value="{{ request('q', request('search')) }}"
                                   placeholder="Cari Nama, Bulan, Tahun"
                                   class="w-[28rem] rounded-md border border-red-700 pl-9 pr-3 py-2 text-sm placeholder-slate-400 focus:border-red-800 focus:ring-red-800" />
                            <svg class="pointer-events-none absolute left-2.5 top-2.5 h-4 w-4 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 11.15 11.15z" />
                            </svg>
                        </div>

                        {{-- Status (Add Filter) --}}
                        @php $currentStatus = strtolower((string)request('status')); @endphp
                        <div class="relative">
                            <select id="statusSelect" name="status"
                                    class="appearance-none cursor-pointer rounded-md bg-red-700 pl-8 pr-8 py-2 text-sm font-medium text-white shadow hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800">
                                <option value="" @selected($currentStatus==='')>Semua</option>
                                <option value="berlangsung" @selected($currentStatus==='berlangsung')>Berlangsung</option>
                                <option value="selesai" @selected(in_array($currentStatus,['selesai','berakhir']))>Berakhir</option>
                                <option value="menunggu" @selected(in_array($currentStatus,['menunggu','pending']))>Menunggu</option>
                            </select>
                            <svg class="pointer-events-none absolute left-2.5 top-2.5 h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M3 5h18v2H3V5zm4 6h10v2H7v-2zm-2 6h14v2H5v-2z"/>
                            </svg>
                            <span class="pointer-events-none absolute right-2 top-1.5 text-white">▾</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============ TABLE + PAGINATION (dibungkus #tableWrap untuk AJAX) ============ --}}
            <section id="tableWrap" class="mx-auto mt-4 max-w-7xl px-2 sm:px-4">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1040px] border-separate border-spacing-0">
                            <thead>
                            <tr>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white rounded-tl-md">No</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">Nama Agenda</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">Tempat</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">Tanggal</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">Waktu</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">PIC</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white">Dihadiri</th>
                                <th class="bg-red-800 px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-white rounded-tr-md">Status</th>
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

                                    $wMulai   = $agenda->waktu_mulai ?? $agenda->jam_mulai ?? $agenda->pukul_mulai ?? $agenda->start_time ?? null;
                                    $wSelesai = $agenda->waktu_selesai ?? $agenda->jam_selesai ?? $agenda->pukul_selesai ?? $agenda->end_time ?? null;
                                    $wSatu    = $agenda->waktu ?? $agenda->jam ?? $agenda->pukul ?? null;
                                    $waktuText = ($wMulai || $wSelesai) ? trim(($wMulai ?? '').(($wMulai && $wSelesai)?' - ':'').($wSelesai ?? '')) : ($wSatu ?? '-');

                                    $picText = '-';
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
                                    <td class="border-y border-slate-200 px-4 py-3 text-sm text-slate-700 align-top">{{ $rowIndex }}.</td>
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
                                <tr><td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">Data tidak ditemukan.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($agendas, 'links'))
                        <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-4 py-3">
                            <p class="text-sm text-slate-600">
                                Menampilkan <span class="font-medium">{{ $agendas->firstItem() }}</span> –
                                <span class="font-medium">{{ $agendas->lastItem() }}</span> dari
                                <span class="font-medium">{{ $agendas->total() }}</span> data
                            </p>
                            <div class="shrink-0">
                                {{ $agendas->onEachSide(1)->links() }}
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

  // Build params untuk FETCH KE SERVER (tanpa 'q/search'!)
  function buildServerParams(){
    const f = form(); const fd = new FormData(f);

    // pakai perPage saja
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp);
    fd.delete('per_page');

    // hapus q/search -> server tidak akan menyaring berdasarkan q
    fd.delete('q'); fd.delete('search');

    // hapus status jika kosong
    if (!fd.get('status')) fd.delete('status');

    return fd;
  }

  // URL untuk FETCH
  function urlForServer(){
    const f = form(); const fd = buildServerParams();
    const qs = new URLSearchParams(fd).toString();
    return f.action + (qs ? '?' + qs : '');
  }

  // URL untuk DITAMPILKAN di address bar (kita simpan 'q' supaya state-nya persist)
  function urlForState(){
    const f = form(); const fd = new FormData(f);

    // mirror perPage
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp); fd.delete('per_page');

    // sertakan q/search dari input
    const qVal = f.querySelector('#qInput')?.value || '';
    if (qVal.trim() !== '') { fd.set('q', qVal); fd.set('search', qVal); }
    else { fd.delete('q'); fd.delete('search'); }

    // hapus status kalau kosong
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
      const idx = (tr.dataset.index || '').toLowerCase(); // gabungan semua kolom
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

      // setelah ganti table, re-bind pagination dan apply filter lokal lagi
      bindInsideWrap();

      const qVal = form()?.querySelector('#qInput')?.value || '';
      const sVal = form()?.querySelector('#statusSelect')?.value || '';
      liveFilter(qVal, sVal);

      // perbarui URL (dengan q) agar state tersimpan
      updateUrlState();
    })
    .catch(e => { if (e.name !== 'AbortError') console.error(e); })
    .finally(() => { document.querySelector(WRAP_SEL)?.classList.remove('opacity-60'); });
  }

  function bindInsideWrap(){
    const container = document.querySelector(WRAP_SEL);
    if (!container) return;
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

    // SHOW
    const perSel = f.querySelector('#perPageSelect');
    if (perSel){
      perSel.addEventListener('change', () => {
        const perMirror = f.querySelector('input[name="perPage"]');
        if (perMirror) perMirror.value = perSel.value;
        setPage(1);

        // filter lokal dulu
        const q = f.querySelector('#qInput')?.value || '';
        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(q, s);

        // fetch server (tanpa q)
        ajaxLoad(urlForServer());
      });
    }

    // STATUS
    const statusSel = f.querySelector('#statusSelect');
    if (statusSel){
      statusSel.addEventListener('change', () => {
        setPage(1);
        // filter lokal
        const q = f.querySelector('#qInput')?.value || '';
        liveFilter(q, statusSel.value);
        // fetch server (tanpa q)
        ajaxLoad(urlForServer());
      });
    }

    // SEARCH — client-side only (tidak fetch server agar bisa cari "Febru", jam, tempat, dll.)
    const qInput  = f.querySelector('#qInput');
    const qMirror = f.querySelector('#qMirror'); // hanya untuk keep state
    if (qInput){
      qInput.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

      qInput.addEventListener('input', () => {
        if (qMirror) qMirror.value = qInput.value; // simpan di hidden (untuk URL state)
        setPage(1);

        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(qInput.value, s); // langsung filter DOM

        // perbarui URL supaya q tersimpan (tanpa reload)
        updateUrlState();

        // kalau mau sync server juga, aktifkan debounce berikut:
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

    // Pastikan URL state benar pada awal
    updateUrlState();
  }

  document.addEventListener('DOMContentLoaded', bind);
})();
</script>
@endpush

</x-layout>
