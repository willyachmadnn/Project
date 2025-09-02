{{-- resources/views/components/isiDaftarTamu.blade.php --}}
@props(['agenda'])

@php
    use Illuminate\Support\Facades\DB;
    // Ambil data OPD yang diundang untuk agenda ini
    $opdDiundang = DB::table('agenda_opd')
        ->join('opd', 'agenda_opd.opd_id', '=', 'opd.opd_id')
        ->where('agenda_opd.agenda_id', $agenda->agenda_id)
        ->get();
    
    // Ambil tamu non-pegawai (tamu yang instansinya = 58, yaitu OPD 'Umum')
    $tamuNonPegawai = $agenda->tamu->where('instansi', 58);
    
    // Total Diundang = jumlah OPD + tamu non-pegawai
    $totalDiundang = $opdDiundang->count() + $tamuNonPegawai->count();
    
    // Ambil OPD yang ada perwakilannya di tamu (cocokkan opd_id dengan instansi)
    $opdHadir = $opdDiundang->filter(function($opd) use ($agenda) {
        return $agenda->tamu->where('instansi', $opd->opd_id)->count() > 0;
    });
    
    // Total Hadir = tamu non-pegawai + OPD yang ada perwakilannya
    $totalHadir = $tamuNonPegawai->count() + $opdHadir->count();
    
    // Total Tidak Hadir = OPD yang tidak ada perwakilannya
    $totalTidakHadir = $opdDiundang->count() - $opdHadir->count();
@endphp

<div class="bg-white/90 backdrop-blur-sm rounded-lg p-8 h-full overflow-y-auto shadow-lg border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Statistik Tamu</h3>
        </div>
        <div class="flex space-x-3">
            {{-- Tombol Tambah OPD --}}
            <a href="{{ route('agenda.tamu.tambah-opd', ['agenda' => $agenda->agenda_id]) }}" 
            class="group flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-0 focus:ring-blue-500/50 font-semibold">
                
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah OPD
            </a>

            {{-- Tombol Kelola Tamu --}}
            <a href="{{ route('agenda.tamu.kelola', ['agenda' => $agenda->agenda_id]) }}" 
            class="group flex items-center px-3 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-0 focus:ring-green-500/50 font-semibold">
                
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Kelola Tamu
            </a>
        </div>
    </div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Tamu Diundang -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">Total Diundang</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $totalDiundang }}</p>
                    <p class="text-blue-500 text-xs mt-1">Tamu yang diundang</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tamu Hadir -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">Total Hadir</p>
                    <p class="text-3xl font-bold text-green-800">{{ $totalHadir }}</p>
                    <p class="text-green-500 text-xs mt-1">Tamu yang hadir</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tamu Tidak Hadir -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-6 border border-red-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">Tidak Hadir</p>
                    <p class="text-3xl font-bold text-red-800">{{ $totalTidakHadir }}</p>
                    <p class="text-red-500 text-xs mt-1">Tamu tidak hadir</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Link Lihat OPD yang Diundang -->
        <div class="mt-2 w-full text-right md:col-start-3">
            <a href="{{ route('agenda.tamu.opd-diundang', $agenda->agenda_id) }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 group">
                <span>Lihat OPD yang Diundang</span>
                <svg class="ml-1 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
     </div>
</div>
