{{-- resources/views/components/isiDetailAgenda.blade.php --}}
@props(['agenda'])

<div class="glass-effect rounded-2xl p-8 h-full flex flex-col">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-800 rounded-full flex items-center justify-center mr-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Detail Agenda</h3>
        </div>
        <div class="flex space-x-3">
            <button class="flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12v4m6-4h.01M12 8h.01M12 8h4.01M12 8H7.99M12 8V4m0 0H7.99M12 4h4.01"></path></svg>
                QR
            </button>
            <button @click="openEditModal()" class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                EDIT
            </button>
            {{-- PERBAIKAN FINAL: Menggunakan $dispatch untuk mengirim sinyal --}}
            <button @click="$dispatch('open-delete-modal')" class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                HAPUS
            </button>
        </div>
    </div>
    <div class="text-sm border border-rose-200 rounded-md overflow-hidden bg-white flex-1">
        <div class="flex bg-rose-100">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tempat</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tempat }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tanggal</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tanggal->format('d F Y') }}</div>
        </div>
        <div class="flex bg-rose-100 border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Waktu</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Nama Admin</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->admin->nama_admin ?? 'Data belum diisi' }}</div>
        </div>
        <div class="flex bg-rose-100 border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">OPD Admin</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->admin->opd_admin ?? 'Data belum diisi' }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Dihadiri</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->dihadiri ?? 'Data belum diisi' }}</div>
        </div>
    </div>
    <div class="mt-6 flex justify-center">
        @if($agenda->status === 'Menunggu')
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Agenda Menunggu
            </span>
        @elseif($agenda->status === 'Berlangsung')
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg animate-pulse">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                Sedang Berlangsung
            </span>
        @else
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Agenda Selesai
            </span>
        @endif
    </div>
</div>
