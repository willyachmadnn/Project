<x-layout title="Kelola Tamu - {{ $agenda->nama_agenda }}">
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Tamu</h1>
                    <p class="text-gray-600">{{ $agenda->nama_agenda }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2m8 0V9a2 2 0 00-2-2H10a2 2 0 00-2 2v8.01"></path>
                        </svg>
                        {{ $agenda->tempat }} • {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }} • {{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}
                    </div>
                </div>
                <a href="{{ route('agenda.show', $agenda->agenda_id) }}#tamu"
                class="group inline-flex items-center px-4 py-2 bg-[#ac1616] hover:bg-red-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#ac1616] focus:ring-offset-2"
                onclick="sessionStorage.setItem('activeTab', 'tamu')">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            </div>
        </div>

        <!-- Statistics Cards -->
    

        <!-- Tamu List -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Daftar Tamu</h2>
                    
                    <!-- Search, Filter, and PDF Controls -->
                    <div class="flex items-center gap-3" x-data="{ 
                        open: false, 
                        label: 'Add Filter'
                    }">
                        <!-- Search Input -->
                         <div class="group relative flex items-center w-80 rounded-md bg-white ring-1 ring-inset ring-red-700/50 hover:ring-red-700/80 focus-within:ring-1 focus-within:ring-red-600">
                             <svg class="ml-3 mr-2 h-4 w-4 stroke-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                             </svg>
                             <input id="searchInput" type="search" autocomplete="off" placeholder="Cari NIP, Nama, Jenis Kelamin, Instansi..." class="block w-full appearance-none bg-transparent py-2 pr-3 text-base text-gray-700 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6" oninput="updateSearch(this.value)"/>
                         </div>
                        
                        <!-- Add Filter Dropdown -->
                        <div class="relative inline-block text-left shrink-0">
                            <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex w-40 items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                     <a href="#" @click.prevent="updateFilter('', 'Add Filter'); open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-900 hover:border-l-4 hover:border-blue-500 transition-all duration-200" role="menuitem">Semua</a>
                                     <a href="#" @click.prevent="updateFilter('pegawai', 'Pegawai'); open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-900 hover:border-l-4 hover:border-blue-500 transition-all duration-200" role="menuitem">Pegawai</a>
                                     <a href="#" @click.prevent="updateFilter('non-pegawai', 'Non-Pegawai'); open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-900 hover:border-l-4 hover:border-blue-500 transition-all duration-200" role="menuitem">Non-Pegawai</a>
                                 </div>
                            </div>
                        </div>
                        
                        <!-- UNDUH PDF Button -->
                        <button type="button" onclick="downloadTamuPDF()" class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#ac1616] to-red-700 hover:from-red-700 hover:to-red-800 disabled:from-red-400 disabled:to-red-500 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-red-500/50">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm">UNDUH PDF</span>
                        </button>
                    </div>
                </div>
            </div>
            
            @if($tamu->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tamu as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-mono text-gray-900">{{ $item->NIP }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_tamu }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium
                                            {{ $item->jk === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $item->jk }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $item->opd ? $item->opd->nama_opd : 'Tidak Diketahui' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium
                                            {{ str_starts_with($item->NIP, 'NP') ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                            {{ str_starts_with($item->NIP, 'NP') ? 'Non-Pegawai' : 'Pegawai' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Tamu</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada tamu yang mendaftar untuk agenda ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ====================== UNDuh PDF: sesuai contoh Mojokerto (FINAL: text center + standar logo) ====================== --}}
<script>
    // Fungsi utility: garis titik-titik (fallback jika setLineDash tidak tersedia)
    function drawDottedLine(doc, x1, y, x2, segment = 1.6, gap = 1.2) {
        if (typeof doc.setLineDash === 'function') {
            doc.setLineDash([1.5, 1.5], 0);
            doc.line(x1, y, x2, y);
            doc.setLineDash(); // reset
            return;
        }
        for (let x = x1; x < x2; x += (segment + gap)) {
            const end = Math.min(x + segment, x2);
            doc.line(x, y, end, y);
        }
    }

    // =======================
    // TOMBOL: downloadTamuPDF
    // =======================
    function downloadTamuPDF() {
        const visibleRows = Array.from(document.querySelectorAll('tbody tr'))
            .filter(row => row.style.display !== 'none');

        if (visibleRows.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Tidak Ada Data', text: 'Tidak ada tamu yang dapat diunduh.' });
            } else {
                alert('Tidak ada tamu yang dapat diunduh.');
            }
            return;
        }

        const doc = new jspdf.jsPDF({ unit: 'mm', format: 'a4', orientation: 'portrait' });
        const pageW = doc.internal.pageSize.getWidth();   // 210
        const pageH = doc.internal.pageSize.getHeight();  // 297
        const margin = 18;          // kiri/kanan
        const topMargin = 18;
        const bottomMargin = 16;
        const usableW = pageW - margin * 2;

        // Kolom tabel (NO | NAMA | JENIS KELAMIN (L|P) | INSTANSI / JABATAN)
        const colNo   = 12;
        const colNama = 48;
        const colJKL  = 10;   // Jenis Kelamin L (diperbesar)
        const colJKP  = 10;   // Jenis Kelamin P (diperbesar)
        const colInstansiJabatan = usableW - (colNo + colNama + colJKL + colJKP);

        // Util vertical-center (untuk single line)
        const vCenter = (y, h, fontSize = doc.getFontSize()) =>
            y + (h / 2) + (fontSize * 0.35 / 2.0);

        // ===== Centering helper untuk cell multi-line =====
        const LINE_H = 4.2;       // tinggi 1 baris (mm) @ font 9
        const BASELINE_FIX = 3.0; // koreksi baseline agar optik pas @ font 9

        function drawCenteredCellText(doc, text, cellX, cellY, cellW, cellH) {
            const lines  = doc.splitTextToSize(text || '', cellW - 2);
            const blockH = Math.max(1, lines.length) * LINE_H;
            const startY = cellY + (cellH - blockH) / 2 + BASELINE_FIX;
            doc.text(lines, cellX + (cellW / 2), startY, { align: 'center' });
        }

        // Hitung tinggi baris dinamis (sinkron dengan helper di atas)
        function computeRowH(nama, instansiJabatan) {
            doc.setFont('helvetica', 'normal'); doc.setFontSize(9);
            const linesNama = doc.splitTextToSize(nama || '', colNama - 2).length;
            const linesInstansiJabatan = doc.splitTextToSize(instansiJabatan || '', colInstansiJabatan - 2).length;
            const maxLines  = Math.max(linesNama, linesInstansiJabatan, 1);
            return Math.max(10, maxLines * LINE_H + 2); // minimal 10mm
        }

        // Header pertama (kop + judul + garis)
        function drawKop() {
            let y = topMargin;

            // ===== Logo standar (ukuran dan posisi standar pemerintahan) =====
            try {
                const logo = new Image();
                logo.src = '{{ asset("img/mojokerto_kab.png") }}';

                const LOGO_W = 18;                // standar lebar logo (mm)
                const LOGO_H = 22;                // standar tinggi logo (mm) - rasio standar
                const LOGO_X = margin + 2;       // posisi X dengan sedikit margin
                const LOGO_Y = topMargin - 1;    // posisi Y (digeser ke atas)

                // gambar logo dengan kualitas standar
                doc.addImage(logo, 'PNG', LOGO_X, LOGO_Y, LOGO_W, LOGO_H, undefined, 'MEDIUM');
            } catch (e) {
                // aman bila gagal memuat logo
            }

            // Teks kop sesuai format Mojokerto
            doc.setFont('helvetica', 'bold');  doc.setFontSize(14);
            doc.text('PEMERINTAH KABUPATEN MOJOKERTO', pageW / 2, y + 4, { align: 'center' });
            doc.setFontSize(16);
            doc.text('DINAS KOMUNIKASI DAN INFORMATIKA', pageW / 2, y + 10, { align: 'center' });

            doc.setFont('helvetica', 'normal'); doc.setFontSize(10);
            doc.text('Jl. Kyai H. Hasyim Ashari Nomor 12, Kode Pos 61318, Jawa Timur.', pageW / 2, y + 16, { align: 'center' });
            doc.text('Telp. (0321) 391268 Fax. (0321) 391268', pageW / 2, y + 20, { align: 'center' });
            doc.text('Website : https://diskominfo.mojokertokab.go.id', pageW / 2, y + 24, { align: 'center' });

            // Garis tebal
            doc.setLineWidth(1.0);
            doc.line(margin, y + 28, pageW - margin, y + 28);
            doc.setLineWidth(0.2);

            // Judul
            doc.setFont('helvetica', 'bold'); doc.setFontSize(14);
            doc.text('DAFTAR HADIR PERTEMUAN RAPAT', pageW / 2, y + 38, { align: 'center' });

            return y + 44; // Y posisi setelah judul
        }

        // Blok informasi (Hari/Tanggal, Waktu, Tempat, Acara)
        function drawInfoBlock(yStart) {
            let y = yStart + 5;
            const labelW = 30;
            doc.setFont('helvetica', 'normal'); doc.setFontSize(11);

            const hariTanggal = '{{ \Carbon\Carbon::parse($agenda->tanggal)->locale("id")->translatedFormat("l, d F Y") }}';
            const info = [
                ['Hari / Tanggal', hariTanggal],
                ['Waktu', `{{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}`],
                ['Tempat', `{{ $agenda->tempat }}`],
                ['Acara', `{{ $agenda->nama_agenda }}`],
            ];

            info.forEach(([label, val]) => {
                const lines = doc.splitTextToSize(String(val || ''), usableW - labelW - 5);
                doc.text(`${label}`, margin, y);
                doc.text(':', margin + labelW, y);
                doc.text(lines, margin + labelW + 5, y);
                y += 5 * Math.max(1, lines.length);
            });

            return y + 3;
        }

        // Header tabel (hanya di halaman pertama, sesuai contoh)
        function drawTableHeader(yStart) {
            const h1 = 8;  // tinggi baris pertama
            const h2 = 6;  // tinggi baris kedua
            let x = margin;

            doc.setFont('helvetica', 'bold'); doc.setFontSize(9);
            
            // Baris pertama header
            doc.rect(x, yStart, colNo, h1 + h2);
            doc.text('NO.', x + colNo / 2, vCenter(yStart, h1 + h2, 9), { align: 'center' });
            x += colNo;

            doc.rect(x, yStart, colNama, h1 + h2);
            doc.text('NAMA', x + colNama / 2, vCenter(yStart, h1 + h2, 9), { align: 'center' });
            x += colNama;

            // Kolom Jenis Kelamin dengan sub-header L dan P (diperbaiki garis)
            const jkTotalW = colJKL + colJKP;
            doc.rect(x, yStart, jkTotalW, h1);
            doc.text('JENIS', x + jkTotalW / 2, vCenter(yStart, h1, 9) - 1, { align: 'center' });
            doc.text('KELAMIN', x + jkTotalW / 2, vCenter(yStart, h1, 9) + 2.5, { align: 'center' });
            
            // Sub-kolom L dengan garis yang tepat
            doc.rect(x, yStart + h1, colJKL, h2);
            doc.text('L', x + colJKL / 2, vCenter(yStart + h1, h2, 9), { align: 'center' });
            x += colJKL;
            
            // Sub-kolom P dengan garis yang tepat
            doc.rect(x, yStart + h1, colJKP, h2);
            doc.text('P', x + colJKP / 2, vCenter(yStart + h1, h2, 9), { align: 'center' });
            x += colJKP;

            doc.rect(x, yStart, colInstansiJabatan, h1 + h2);
            doc.text('INSTANSI / JABATAN', x + colInstansiJabatan / 2, vCenter(yStart, h1 + h2, 9), { align: 'center' });

            return yStart + h1 + h2;
        }

        // Satu baris data
        function drawRow(idx, nama, jk, instansiJabatan, y) {
            const h = computeRowH(nama, instansiJabatan);
            let x = margin;

            doc.setFont('helvetica', 'normal'); doc.setFontSize(9);

            // NO (center)
            doc.rect(x, y, colNo, h);
            doc.text(String(idx), x + colNo / 2, vCenter(y, h, 9), { align: 'center' });
            x += colNo;

            // NAMA (center horizontal & vertikal)
            doc.rect(x, y, colNama, h);
            drawCenteredCellText(doc, nama, x, y, colNama, h);
            x += colNama;

            // Jenis Kelamin L (center)
            doc.rect(x, y, colJKL, h);
            const isLaki = ((jk || '').trim().toUpperCase().startsWith('L'));
            if (isLaki) {
                doc.text('✓', x + colJKL / 2, vCenter(y, h, 9), { align: 'center' });
            }
            x += colJKL;

            // Jenis Kelamin P (center)
            doc.rect(x, y, colJKP, h);
            const isPerempuan = ((jk || '').trim().toUpperCase().startsWith('P'));
            if (isPerempuan) {
                doc.text('✓', x + colJKP / 2, vCenter(y, h, 9), { align: 'center' });
            }
            x += colJKP;

            // INSTANSI / JABATAN (center horizontal & vertikal)
            doc.rect(x, y, colInstansiJabatan, h);
            drawCenteredCellText(doc, instansiJabatan, x, y, colInstansiJabatan, h);

            return h;
        }

        // Footnote bawah tiap halaman
        function addFootnoteAllPages() {
            const total = doc.getNumberOfPages();
            for (let i = 1; i <= total; i++) {
                doc.setPage(i);
                doc.setFont('helvetica', 'normal'); doc.setFontSize(8);
                doc.text('Data ini dihasilkan dari aplikasi https://agenda.mojokertokab.go.id/', pageW / 2, pageH - 6, { align: 'center' });
            }
        }

        // Blok tanda tangan "Mengetahui" di halaman terakhir
        function drawSignatureBlock() {
            const startY =  pageH - 60; // posisi blok
            const areaW = 80;           // lebar area tanda tangan
            const x0 = pageW - margin - areaW;

            doc.setFont('helvetica', 'normal'); doc.setFontSize(10);
            doc.text('Mengetahui,', x0, startY);

            // Garis titik-titik untuk tanda tangan
            const line1Y = startY + 25;
            drawDottedLine(doc, x0, line1Y, x0 + areaW);

            // NIP
            const line2Y = line1Y + 18;
            doc.text('NIP.', x0, line2Y - 2);
            drawDottedLine(doc, x0 + 9, line2Y, x0 + areaW);
        }

        // ================== Penyusunan halaman ==================
        let y = drawKop();
        y = drawInfoBlock(y);

        // Header tabel (hanya halaman pertama)
        y = drawTableHeader(y);

        // Render baris
        const spaceForSignature = 60; // mm (blok "Mengetahui")

        visibleRows.forEach((row, idx) => {
            const tds = row.querySelectorAll('td');
            const nama = (tds[2]?.textContent || '').trim();
            const jk   = (tds[3]?.textContent || '').trim();
            const instansiNama = (tds[4]?.textContent || '').trim();

            // Gabungan / format teks INSTANSI/JABATAN
            const instansi = instansiNama;

            const jabatan = 'PESERTA'; // Default jabatan
            const unitKerja = 'DINAS PENDIDIKAN'; // Default unit kerja
            const instansiJabatan = `${unitKerja} / ${jabatan}`;
            
            const neededH = computeRowH(nama, instansiJabatan);
            const limit = pageH - bottomMargin - 4;

            if (y + neededH > limit) {
                doc.addPage();
                y = topMargin; // halaman berikutnya tanpa kop/judul (sesuai contoh)
            }

            const h = drawRow(idx + 1, nama, jk, instansiJabatan, y);
            y += h;
        });

        // Pastikan blok tanda tangan muat di halaman terakhir
        if (y + spaceForSignature > pageH - bottomMargin) {
            doc.addPage();
            y = topMargin;
        }
        drawSignatureBlock();

        addFootnoteAllPages();

        const fileName =
            `Daftar_Hadir_{{ \Illuminate\Support\Str::slug($agenda->nama_agenda) }}_{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d_m_Y') }}.pdf`;
        doc.save(fileName);
    }
</script>

</x-layout>