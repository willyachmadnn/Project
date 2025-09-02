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

<script>
// Global variables untuk search dan filter
let currentSearchQuery = '';
let currentFilter = '';

// Fungsi untuk update search
function updateSearch(query) {
    currentSearchQuery = query;
    filterTamu();
}

// Fungsi untuk update filter
function updateFilter(filter, label) {
    currentFilter = filter;
    // Update label di dropdown
    const labelElement = document.querySelector('[x-text="label"]');
    if (labelElement) {
        labelElement.textContent = label;
    }
    filterTamu();
}

// Fungsi untuk filter tamu
function filterTamu() {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const nip = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const nama = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const jenisKelamin = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        const instansi = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
        const tipe = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
        
        const searchText = currentSearchQuery.toLowerCase();
        const matchesSearch = !searchText || 
            nip.includes(searchText) || 
            nama.includes(searchText) || 
            jenisKelamin.includes(searchText) || 
            instansi.includes(searchText);
        
        const matchesFilter = !currentFilter || 
            (currentFilter === 'pegawai' && tipe.includes('pegawai') && !tipe.includes('non')) ||
            (currentFilter === 'non-pegawai' && tipe.includes('non-pegawai'));
        
        if (matchesSearch && matchesFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fungsi untuk download PDF daftar tamu
function downloadTamuPDF() {
    // Validasi: Cek apakah ada tamu yang hadir
    const tamuRows = document.querySelectorAll('tbody tr');
    if (tamuRows.length === 0) {
        // Tampilkan alert error dengan styling yang menarik dan animasi smooth
        Swal.fire({
            icon: 'error',
            title: 'Tidak Ada Tamu!',
            text: 'Belum ada tamu yang hadir. PDF tidak dapat diunduh karena tidak ada data tamu untuk ditampilkan.',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc2626',
            background: '#fff',
            backdrop: 'rgba(0,0,0,0.4)',
            allowOutsideClick: false,
            allowEscapeKey: true,
            showClass: {
                popup: 'animate__animated animate__fadeIn animate__faster',
                backdrop: 'animate__animated animate__fadeIn animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut animate__faster',
                backdrop: 'animate__animated animate__fadeOut animate__faster'
            },
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-red-600 font-bold',
                content: 'text-gray-700'
            },
            didOpen: () => {
                // Mencegah scroll pada body saat modal terbuka
                document.body.style.overflow = 'hidden';
            },
            willClose: () => {
                // Mengembalikan scroll pada body saat modal ditutup
                document.body.style.overflow = 'auto';
            }
        })
        return;
    }
    
    // Pastikan library jsPDF tersedia
    if (typeof window.jspdf === 'undefined') {
        alert('Library PDF tidak tersedia. Silakan refresh halaman dan coba lagi.');
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    
    // Header PDF
    pdf.setFontSize(16);
    pdf.text('PEMERINTAH KABUPATEN MOJOKERTO', 148, 20, { align: 'center' });
    pdf.setFontSize(14);
    pdf.text('DAFTAR TAMU AGENDA', 148, 30, { align: 'center' });
    pdf.setFontSize(12);
    pdf.text('{{ $agenda->nama_agenda }}', 148, 40, { align: 'center' });
    pdf.text('{{ $agenda->tempat }} • {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }} • {{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}', 148, 50, { align: 'center' });
    
    // Ambil data tamu yang terlihat (tidak di-filter)
    const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
    
    if (visibleRows.length === 0) {
        alert('Tidak ada data tamu untuk diunduh.');
        return;
    }
    
    // Header tabel
    const headers = ['No', 'NIP', 'Nama', 'Jenis Kelamin', 'Instansi', 'Tipe', 'Waktu Daftar'];
    const startY = 70;
    let currentY = startY;
    
    // Gambar header tabel
    pdf.setFontSize(10);
    pdf.setFont(undefined, 'bold');
    const colWidths = [15, 35, 50, 30, 60, 25, 40];
    let currentX = 20;
    
    headers.forEach((header, index) => {
        pdf.rect(currentX, currentY, colWidths[index], 8);
        pdf.text(header, currentX + colWidths[index]/2, currentY + 5, { align: 'center' });
        currentX += colWidths[index];
    });
    
    currentY += 8;
    pdf.setFont(undefined, 'normal');
    
    // Gambar data tamu
    visibleRows.forEach((row, index) => {
        if (currentY > 180) { // Jika mendekati batas halaman
            pdf.addPage();
            currentY = 20;
        }
        
        const cells = row.querySelectorAll('td');
        currentX = 20;
        
        cells.forEach((cell, cellIndex) => {
            if (cellIndex < 7) { // Hanya ambil 7 kolom pertama
                let text = cell.textContent.trim();
                if (cellIndex === 0) text = (index + 1).toString(); // Nomor urut
                
                pdf.rect(currentX, currentY, colWidths[cellIndex], 8);
                
                // Potong teks jika terlalu panjang
                if (text.length > 20) {
                    text = text.substring(0, 17) + '...';
                }
                
                pdf.text(text, currentX + colWidths[cellIndex]/2, currentY + 5, { align: 'center' });
                currentX += colWidths[cellIndex];
            }
        });
        
        currentY += 8;
    });
    
    // Footer
    const totalPages = pdf.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
        pdf.setPage(i);
        pdf.setFontSize(8);
        pdf.text(`Halaman ${i} dari ${totalPages}`, 280, 200, { align: 'right' });
        pdf.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 20, 200);
    }
    
    // Simpan PDF
    const fileName = `Daftar-Tamu-{{ Str::slug($agenda->nama_agenda) }}.pdf`;
    pdf.save(fileName);
}
</script>

</x-layout>