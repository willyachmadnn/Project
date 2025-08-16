@props(['agendas'])
<script src="//unpkg.com/alpinejs" defer></script>
<form action="{{ auth('admin')->check() ? route('agenda.index') : route('landing') }}"
      method="GET" id="filterForm" data-ajax="true">
  {{-- hidden untuk sync via JS --}}
  <input type="hidden" name="timeRange" value="{{ request('timeRange', 5) }}">
  {{-- <input type="hidden" name="perPage" ...> TELAH DIHAPUS --}}
  <input type="hidden" id="qMirror" name="search" value="{{ request('search', request('q')) }}">
  <input type="hidden" id="pageHidden" name="page" value="{{ request('page', 1) }}">

  {{-- ================== CONTROLS (Show / Search / Filter) ================== --}}
  <section>
    <div id="controls" class="flex flex-wrap items-center justify-between gap-4">

      <div id="showWrap" class="inline-flex items-center gap-2 rounded-lg bg-white px-2 py-1 ring-1 ring-inset ring-red-600/70 hover:ring-red-600/60 focus-within:ring-1 focus-within:ring-red-600 shrink-0">
        <span class="text-sm font-medium text-slate-700">Show:</span>
        <div class="relative">
          {{-- NAMA DIUBAH MENJADI perPage --}}
          <select id="perPageSelect" name="perPage" class="appearance-none h-7 w-16 rounded-md border border-red-700/50 bg-transparent pl-3 pr-7 text-sm font-medium leading-tight text-slate-700 focus:outline-none focus:ring-1 focus:ring-red-600">
            @foreach ([10,25,50,100] as $n)
              <option value="{{ $n }}" @selected((int)request('perPage', 10) === $n)>{{ $n }}</option>
            @endforeach
          </select>
          <svg class="pointer-events-none absolute right-1 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.53a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
        </div>
      </div>

      <div class="flex items-center gap-2 md:gap-3">
        <div class="group relative flex items-center w-96 rounded-md bg-white ring-1 ring-inset ring-red-700/50 hover:ring-red-700/80 focus-within:ring-2 focus-within:ring-red-600">
          <svg class="ml-3 mr-2 h-4 w-4 stroke-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          <input id="searchInput" name="search" value="{{ request('search', request('q')) }}" type="search" autocomplete="off" placeholder="Kegiatan, Tempat, Tanggal, Waktu, Admin OPD, Dihadiri" class="block w-full appearance-none bg-transparent py-2 pr-3 text-base text-gray-700 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6"/>
        </div>
            @php
            $currentStatus = strtolower((string)request('status'));
            $statusLabel = match ($currentStatus) {
                'berlangsung' => 'Berlangsung',
                'selesai', 'berakhir' => 'Berakhir',
                'menunggu', 'pending' => 'Menunggu',
                default => 'Add Filter',
            };
              @endphp
        
        <div x-data="{ 
                open: false, 
                status: '{{ $currentStatus }}', 
                label: '{{ $statusLabel }}' 
             }" 
             class="relative inline-block text-left shrink-0">
            
            <input type="hidden" name="status" x-model="status">

            <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex w-40 items-center rounded-md bg-red-700 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800">
                {{-- PERUBAHAN: Mengganti ikon filter dengan ikon "sort" --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M3 3a1 1 0 000 2h14a1 1 0 000-2H3zm0 4a1 1 0 000 2h10a1 1 0 000-2H3zm0 4a1 1 0 000 2h6a1 1 0 000-2H3z" />
                </svg>
                <span x-text="label" class="flex-grow text-center mx-2"></span>
                <svg class="-mr-1 h-5 w-5 text-gray-300 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.53a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 z-10 mt-2 w-full origin-top-right rounded-md bg-white shadow-lg border border-slate-300 focus:outline-none"
                 style="display: none;">
                <div class="py-1" role="menu" aria-orientation="vertical">
                    <a href="#" @click.prevent="status=''; label='Add Filter'; open=false; $nextTick(() => { $el.closest('form').dispatchEvent(new Event('change', { bubbles: true })) })" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-900" role="menuitem">Semua</a>
                    <a href="#" @click.prevent="status='berlangsung'; label='Berlangsung'; open=false; $nextTick(() => { $el.closest('form').dispatchEvent(new Event('change', { bubbles: true })) })" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-900" role="menuitem">Berlangsung</a>
                    <a href="#" @click.prevent="status='selesai'; label='Berakhir'; open=false; $nextTick(() => { $el.closest('form').dispatchEvent(new Event('change', { bubbles: true })) })" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-900" role="menuitem">Berakhir</a>
                    <a href="#" @click.prevent="status='menunggu'; label='Menunggu'; open=false; $nextTick(() => { $el.closest('form').dispatchEvent(new Event('change', { bubbles: true })) })" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-900" role="menuitem">Menunggu</a>
                </div>
            </div>
        </div>

      </div>
    </div>
  </section>

  {{-- ==================== TABLE + PAGINATION (dibungkus #tableWrap untuk AJAX) ==================== --}}
  <section id="tableWrap" class="mx-auto mt-4 max-w-7xl px-2 sm:px-1">
    <div class="rounded-lg bg-white shadow-xl transition-all duration-300">
      <div class="overflow-x-auto rounded-t-lg">
        <table class="agenda-table w-full table-fixed text-xs sm:text-sm
               ![&_th]:text-center ![&_td]:text-center ![&_td]:align-middle
               [&_td>div]:flex [&_td>div]:items-center [&_td>div]:justify-center
               [&_td>a]:inline-flex [&_td>a]:items-center [&_td>a]:justify-center [&_td>a]:mx-auto
               [&_td>span]:block [&_td>span]:mx-auto">

          {{-- Lebar kolom --}}
          <colgroup>
            <col class="w-12 sm:w-14 md:w-16" />   {{-- NO --}}
            <col class="w-[24%]" />                 {{-- Nama Agenda --}}
            <col class="w-[16%]" />                 {{-- Tempat --}}
            <col class="w-[16%]" />                 {{-- Tanggal --}}
            <col class="w-[12%]" />                 {{-- Waktu --}}
            <col class="w-[14%]" />                 {{-- Penanggung Jawab --}}
            <col class="w-[14%]" />                 {{-- Kehadiran --}}
            <col class="w-[12%]" />                 {{-- Status --}}
          </colgroup>

          <thead class="bg-red-700 text-white">
            <tr>
              <th class="px-4 py-3 font-semibold rounded-tl-lg">No</th>
              <th class="px-4 py-3 font-semibold">Kegiatan</th>
              <th class="px-4 py-3 font-semibold">Tempat</th>
              <th class="px-4 py-3 font-semibold">Tanggal</th>
              <th class="px-4 py-3 font-semibold">Waktu</th>
              <th class="px-4 py-3 font-semibold">Admin OPD</th>
              <th class="px-4 py-3 font-semibold">Dihadiri</th>
              <th class="px-4 py-3 font-semibold rounded-tr-lg">Status</th>
            </tr>
          </thead>

          <tbody id="agendaBody">
          @forelse($agendas as $agenda)
            @php
              $rowIndex = ($agendas->firstItem() ?? 1) + $loop->index;

              // STATUS -> label + badge
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

              // TANGGAL
              $tgl = $agenda->tanggal ?? $agenda->date ?? null;
              if ($tgl instanceof \Carbon\Carbon || $tgl instanceof \Illuminate\Support\Carbon) {
                $tglText   = method_exists($tgl, 'translatedFormat') ? $tgl->translatedFormat('d F Y') : $tgl->format('d F Y');
                $monthNum  = $tgl->format('m'); $yearNum = $tgl->format('Y');
                $monthName = method_exists($tgl, 'translatedFormat') ? $tgl->translatedFormat('F') : $tgl->format('F');
              } else {
                $tglText = $tgl ? (string)$tgl : '-'; $monthNum=''; $yearNum=''; $monthName='';
              }

              // WAKTU -> tampil HH:mm atau rentang
              $fmt = function ($v) {
                if (blank($v)) return null;
                if ($v instanceof \DateTimeInterface) {
                  return \Illuminate\Support\Carbon::parse($v)->format('H:i');
                }
                if (preg_match('/\b(\d{1,2}):(\d{2})(?::\d{2})?\b/', (string)$v, $m)) {
                  return sprintf('%02d:%02d', $m[1], $m[2]);
                }
                try { return \Illuminate\Support\Carbon::parse((string)$v)->format('H:i'); }
                catch (\Throwable $e) { return null; }
              };
              $wMulaiRaw   = $agenda->waktu_mulai  ?? $agenda->jam_mulai ?? $agenda->pukul_mulai ?? $agenda->start_time ?? null;
              $wSelesaiRaw = $agenda->waktu_selesai ?? $agenda->jam_selesai ?? $agenda->pukul_selesai ?? $agenda->end_time   ?? null;
              $wSatuRaw    = $agenda->waktu ?? $agenda->jam ?? $agenda->pukul ?? null;

              $wMulai   = $fmt($wMulaiRaw);
              $wSelesai = $fmt($wSelesaiRaw);
              $wSatu    = (filled($wSatuRaw) && preg_match('/[-–]/', (string)$wSatuRaw))
                          ? collect(preg_split('/\s*[-–]\s*/', (string)$wSatuRaw))->map($fmt)->filter()->implode(' - ')
                          : $fmt($wSatuRaw);

              $waktuText = ($wMulai || $wSelesai) ? collect([$wMulai, $wSelesai])->filter()->implode(' - ') : ($wSatu ?? '-');

              // PIC
              $picText = data_get($agenda, 'admin.opd_admin') ?? $agenda->admin_id ?? null;
              foreach (['pic','PIC','nama_pic','penanggung_jawab','petugas','user.name','creator.name','created_by_name','dibuat_oleh'] as $key) {
                $val = data_get($agenda, $key);
                if (is_string($val) && trim($val)!=='') { $picText = trim($val); break; }
                if (is_object($val))   { $n = data_get($val,'name') ?? data_get($val,'nama') ?? data_get($val,'title'); if (is_string($n) && trim($n)!=='') { $picText = trim($n); break; } }
                if (is_array($val))    { $n = $val['name'] ?? $val['nama'] ?? $val['title'] ?? null; if (is_string($n) && trim($n)!=='') { $picText = trim($n); break; } }
              }
              if (is_numeric($picText)) $picText = '-';

              // DIHADIRI
              $dihadiriText = data_get($agenda,'dihadiri') ?? data_get($agenda,'dihadiri_oleh') ?? data_get($agenda,'dihadiri_oleh_text') ?? '-';

              // Index pencarian client-side
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

            <tr class="odd:bg-white hover:bg-red-100/90 transition-colors duration-3">
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800 align-middle">{{ $rowIndex }}.</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm font-semibold text-slate-900">{{ $agenda->nama_agenda ?? '-' }}</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800">{{ $agenda->tempat ?? '-' }}</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800">{{ $tglText }}</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800">{{ $waktuText ?: '-' }}</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800">{{ $picText }}</td>
              <td class="border-b border-slate-400 px-4 py-3 text-sm text-slate-800">{{ $dihadiriText }}</td>
              <td class="border-b border-slate-400 px-4 py-3">
                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeClass }}">
                  {{ ucfirst($labelStatus) }}
                </span>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-sm text-slate-700">Data tidak ditemukan.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination - Improved styling to appear integrated but separate --}}
      @if (method_exists($agendas, 'links'))
        <div class="flex flex-col gap-3 border-t border-slate-200 px-2 py-4 bg-gray-50 rounded-b-lg">
          <p class="text-sm text-slate-600 self-start">
            Menampilkan <span class="font-medium">{{ $agendas->firstItem() }}</span> –
            <span class="font-medium">{{ $agendas->lastItem() }}</span> dari
            <span class="font-medium">{{ $agendas->total() }}</span> Data
          </p>
          <div class="flex justify-center">
            {{ $agendas->withQueryString()->links('pagination::tailwind') }}
          </div>
        </div>
      @endif
    </div>
  </section>
</form>
