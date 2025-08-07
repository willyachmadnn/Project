<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Situs resmi agenda Pemerintah Kabupaten Mojokerto">
    <title>Agenda Mojokerto</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    
    {{-- Memuat font Inter dari Google Fonts untuk tipografi yang konsisten --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Memuat file CSS dan JS yang sudah dikompilasi oleh Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Gaya dasar untuk body, menggunakan font Inter dan warna latar belakang dari referensi */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDFBF6;
        }
        /* Kustomisasi scrollbar agar terlihat lebih modern */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #D9D9D9; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #595959; }

        /* Menyembunyikan teks "Showing results" bawaan dari paginasi Laravel untuk menghindari duplikasi */
        .pagination-container nav .hidden.sm\:flex-1 {
            display: none;
        }
    </style>
</head>
<body class="antialiased text-[#1F1F1F]">
    {{-- Memuat komponen header --}}
    @include('components.header')

    <main class="container mx-auto mt-8 px-4">
        <!-- Bagian Hero Section / Tentang Halaman -->
        <section class="relative bg-cover bg-center py-20 rounded-lg overflow-hidden mb-12" style="background-image: url('/img/MJK.jpg');">
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm"></div>
            <div class="relative z-10 text-white text-center px-4">
                <h2 class="text-4xl font-bold mb-4">Tentang Halaman Ini</h2>
                <p class="text-lg max-w-2xl mx-auto">Halaman ini dirancang untuk memberikan informasi terkini mengenai berbagai agenda dan kegiatan yang diselenggarakan oleh pemerintah Kabupaten Mojokerto. Kami berkomitmen untuk menyajikan informasi yang akurat dan mudah diakses oleh seluruh masyarakat.</p>
            </div>
        </section>

        <!-- Kartu Status Agenda -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-[#D9D9D9]">
                <h3 class="text-sm font-medium text-[#595959] mb-1">Agenda Menunggu</h3>
                <p id="pendingCount" class="text-3xl font-bold text-[#800000]">{{ $pendingAgendasCount }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-[#D9D9D9]">
                <h3 class="text-sm font-medium text-[#595959] mb-1">Agenda Berlangsung</h3>
                <p id="ongoingCount" class="text-3xl font-bold text-[#800000]">{{ $ongoingAgendasCount }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-[#D9D9D9]">
                <h3 class="text-sm font-medium text-[#595959] mb-1">Agenda Selesai</h3>
                <p id="finishedCount" class="text-3xl font-bold text-[#800000]">{{ $finishedAgendasCount }}</p>
            </div>
        </section>

        <!-- Filter Rentang Waktu (Slider) -->
        <section class="mb-12">
            <label for="timeRange" class="block text-sm font-medium text-gray-700 mb-2">Filter Rentang Waktu (Hanya Mempengaruhi Kartu Status)</label>
            <input type="range" id="timeRange" min="1" max="5" value="{{ request('timeRange', '5') }}" class="w-full">
            <div class="flex justify-between text-sm font-medium text-gray-600 mt-2">
                <span>1 Hari</span>
                <span>7 Hari</span>
                <span>30 Hari</span>
                <span>1 Tahun</span>
                <span>Semua</span>
            </div>
        </section>

        <!-- Form Filter Utama (untuk filter tabel) -->
        <form action="{{ request()->url() }}" method="GET" id="filterForm">
            <section>
                <div class="bg-white rounded-lg shadow-sm border border-[#D9D9D9] overflow-hidden">
                    <!-- Header Tabel & Kontrol Filter -->
                    <div class="p-6 border-b border-[#D9D9D9] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Daftar Agenda</h2>
                            <p class="text-sm text-[#595959]">Menampilkan semua agenda yang terdaftar.</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select name="status" onchange="this.form.submit()" class="h-10 rounded-md border border-[#D9D9D9] bg-transparent pl-3 pr-10 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFBF00]">
                                <option value="">Semua Status</option>
                                <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                                <option value="berlangsung" @selected(request('status') == 'berlangsung')>Berlangsung</option>
                                <option value="berakhir" @selected(request('status') == 'berakhir')>Selesai</option>
                            </select>
                            <input type="text" name="search" placeholder="Cari agenda..." value="{{ request('search') }}" class="h-10 rounded-md border border-[#D9D9D9] bg-transparent px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFBF00]">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-semibold h-10 px-4 py-2 bg-[#800000] text-white shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-[#FFBF00] focus:ring-offset-2 transition-all">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tabel Agenda -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-[#595959] font-semibold">
                                <tr>
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Nama Agenda</th>
                                    <th class="px-6 py-4">Tempat</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Dihadiri</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#D9D9D9]">
                                {{-- Loop melalui data agenda yang dikirim dari controller --}}
                                @forelse($agendas as $agenda)
                                <tr class="hover:bg-[#FDFBF6]">
                                    <td class="px-6 py-4">{{ $agendas->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4 font-semibold text-[#800000] hover:underline">{{ $agenda->nama_agenda }}</td>
                                    <td class="px-6 py-4">{{ $agenda->tempat }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_berakhir)->format('H:i') }}</td>
                                    <td class="px-6 py-4">{{ $agenda->dihadiri ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            // Menentukan warna tag status berdasarkan nilai dari accessor `$agenda->status`
                                            $colorClasses = match($agenda->status) {
                                                'Menunggu'    => 'bg-[#AD6800]/20 text-[#AD6800]',
                                                'Berlangsung' => 'bg-[green]/20 text-green-700',
                                                'Selesai'     => 'bg-[red]/20 text-red-700',
                                                default       => 'bg-gray-200 text-gray-700',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClasses }}">
                                            {{ $agenda->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                {{-- Tampilan jika tidak ada data agenda yang ditemukan --}}
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-500">
                                        <p class="font-bold text-lg">Tidak ada agenda yang ditemukan.</p>
                                        <p>Coba ubah atau hapus filter pencarian Anda.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Tabel (Paginasi & Kontrol Per Halaman) -->
                    <div class="p-4 border-t border-[#D9D9D9] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Kiri: Info Hasil -->
                        <div class="flex-1 flex justify-start text-sm text-[#595959]">
                             @if ($agendas->hasPages())
                                <span>
                                    Menampilkan <strong>{{ $agendas->firstItem() }}</strong>-<strong>{{ $agendas->lastItem() }}</strong> dari <strong>{{ $agendas->total() }}</strong> hasil
                                </span>
                            @endif
                        </div>

                        <!-- Tengah: Paginasi -->
                        <div class="flex-shrink-0 pagination-container">
                            {!! $agendas->links('vendor.pagination.custom') !!}
                        </div>

                        <!-- Kanan: Baris per halaman -->
                        <div class="flex-1 flex justify-end">
                            <div class="flex items-center space-x-2 text-sm text-[#595959]">
                                <span>Baris per halaman:</span>
                                <select name="perPage" onchange="this.form.submit()" class="h-8 rounded-md border border-[#D9D9D9] bg-transparent pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFBF00]">
                                    <option value="10" @selected(request('perPage', 10) == 10)>10</option>
                                    <option value="20" @selected(request('perPage') == 20)>20</option>
                                    <option value="50" @selected(request('perPage') == 50)>50</option>
                                    <option value="100" @selected(request('perPage') == 100)>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </main>

    {{-- Memuat komponen footer --}}
    @include('components.footer')

    {{-- JAVASCRIPT UNTUK FILTER DINAMIS (AJAX) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timeRangeSlider = document.getElementById('timeRange');
            
            const pendingCountEl = document.getElementById('pendingCount');
            const ongoingCountEl = document.getElementById('ongoingCount');
            const finishedCountEl = document.getElementById('finishedCount');

            async function updateCardCounts() {
                const timeRangeValue = timeRangeSlider.value;
                pendingCountEl.textContent = '...';
                ongoingCountEl.textContent = '...';
                finishedCountEl.textContent = '...';
                try {
                    const response = await fetch(`{{ route('api.agenda.counts') }}?timeRange=${timeRangeValue}`);
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    const data = await response.json();
                    pendingCountEl.textContent = data.pending;
                    ongoingCountEl.textContent = data.ongoing;
                    finishedCountEl.textContent = data.finished;
                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                    pendingCountEl.textContent = 'Error';
                    ongoingCountEl.textContent = 'Error';
                    finishedCountEl.textContent = 'Error';
                }
            }

            if (timeRangeSlider) {
                timeRangeSlider.addEventListener('change', function(event) {
                    event.preventDefault();
                    updateCardCounts();
                });
            }
        });
    </script>
</body>
</html>
