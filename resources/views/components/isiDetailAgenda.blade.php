{{-- resources/views/components/isiDetailAgenda.blade.php --}}
@props(['agenda'])

{{-- CSS untuk format 24 jam sudah dihandle secara global di layout.blade.php --}}

{{-- JavaScript untuk format 24 jam sudah dihandle secara global di layout.blade.php --}}

<div x-data="{
    showQrModal: false,
    isEditModalOpen: false,
    isDeleteModalOpen: false,
    editAgenda: {},
    editFormAction: '',
    showEditConfirm: false,
    
    openEditModal() {
        this.editAgenda = {
            agenda_id: {{ json_encode($agenda->agenda_id) }},
            nama_agenda: {{ json_encode($agenda->nama_agenda) }},
            tempat: {{ json_encode($agenda->tempat) }},
            tanggal: {{ json_encode($agenda->tanggal->format('Y-m-d')) }},
            jam_mulai: {{ json_encode(\Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i')) }},
            jam_selesai: {{ json_encode(\Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i')) }},
            dihadiri: {{ json_encode($agenda->dihadiri) }}
        };
        const actionUrl = '{{ route('agenda.update', ':id') }}';
        this.editFormAction = actionUrl.replace(':id', {{ json_encode($agenda->agenda_id) }});
        this.isEditModalOpen = true;
    },
    
    openDeleteModal() {
        this.isDeleteModalOpen = true;
    },
    
    closeModal() {
        this.isEditModalOpen = false;
        this.isDeleteModalOpen = false;
        this.showQrModal = false;
        setTimeout(() => {
            this.editAgenda = {};
            this.editFormAction = '';
        }, 350);
    },
    
    confirmEditAgenda() {
        this.showEditConfirm = true;
    },
    
    submitEditForm() {
        document.getElementById('edit-agenda-form').submit();
    },
    
    submitDeleteForm() {
        document.getElementById('delete-agenda-form').submit();
    }
}" class="bg-white/90 backdrop-blur-sm rounded-lg p-8 h-full overflow-y-auto shadow-lg border border-gray-200">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">Informasi Lengkap Agenda Rapat</h3>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button @click="showQrModal = true; openQrModal()" class="group px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-0 focus:ring-blue-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12v4m6-4h.01M12 8h.01M12 8h4.01M12 8H7.99M12 8V4m0 0H7.99M12 4h4.01"></path></svg>
                QR Code
            </button>
             <button @click="openEditModal()" class="group px-3 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-0 focus:ring-green-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </button>
            {{-- PERBAIKAN FINAL: Menggunakan $dispatch untuk mengirim sinyal --}}
            <button @click="openDeleteModal()" class="group px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-0 focus:ring-red-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>
    {{-- Tabel Detail Agenda --}}
    <div class="bg-white rounded-lg shadow-lg border border-gray-200/50 overflow-hidden">
        <div class="divide-y divide-gray-200">
            {{-- Baris Tempat --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Tempat</label>
                        <p class="text-gray-900 font-bold text-lg text-left">{{ $agenda->tempat }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris Tanggal --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v11a2 2 0 002 2h6a2 2 0 002-2V8a1 1 0 00-1-1V7"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Tanggal</label>
                        <p class="text-gray-900 font-bold text-lg text-left">{{ $agenda->tanggal->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris Waktu --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Waktu</label>
                        <p class="text-gray-900 font-bold text-lg text-left">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris Nama Admin --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Nama Admin</label>
                        <p class="text-gray-900 font-bold text-lg text-left">{{ $agenda->admin->nama_admin ?? 'Data belum diisi' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris OPD Admin --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">OPD Admin</label>
                        <p class="text-gray-900 font-bold text-lg text-left">{{ $agenda->admin->opd->nama_opd ?? 'Data belum diisi' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris Dihadiri --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-1">Dihadiri</label>
                        <p class="text-gray-900 font-bold text-lg text-left leading-relaxed">{{ $agenda->dihadiri ?? 'Data belum diisi' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Baris Status Agenda --}}
            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors duration-200">
    <div class="flex items-center justify-center">
        <div class="flex items-center space-x-3">
        </div>
        <div class="text-center">
            @if($agenda->status === 'Menunggu')
                <div class="inline-flex items-center justify-center w-44 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200">
                    <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-base">Menunggu</span>
                </div>
            @elseif($agenda->status === 'Berlangsung')
                <div class="inline-flex items-center justify-center w-44 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-200 animate-pulse">
                    <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-base">Berlangsung</span>
                </div>
            @else
                <div class="inline-flex items-center justify-center w-44 py-2 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-200">
                    <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-base">Selesai</span>
                </div>
            @endif
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <template x-teleport="body">
        <div x-cloak x-show="showQrModal" @keydown.escape.window="showQrModal = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="qr-modal fixed inset-0 z-[1001] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="showQrModal = false"></div>
            <div x-show="showQrModal" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                 class="relative z-[1002] bg-white rounded-lg shadow-2xl border border-gray-200 w-full max-w-6xl max-h-[95vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#ac1616] to-red-700 p-6 text-white relative overflow-hidden flex-shrink-0 -m-0.5 pt-7 px-7 rounded-t-lg">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                        <pattern id="government-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="10" cy="10" r="1" fill="currentColor"/>
                        </pattern>
                        <rect width="100" height="100" fill="url(#government-pattern)"/>
                    </svg>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Government Emblem -->
                        <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm border border-white/30">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                                <path d="M12 4.5L4.5 8.5v8c0 4.2 2.9 7.4 7.5 8.5 4.6-1.1 7.5-4.3 7.5-8.5v-8L12 4.5z" fill="#ac1616"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">QR Code Kehadiran</h3>
                            <p class="text-red-200 text-sm">{{ $agenda->nama_agenda }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showQrModal = false" 
                            class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-white/30 hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="modal-content flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-white">
                <div class="p-8 space-y-8">


                <!-- QR Codes Container -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- QR Code Pegawai -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                        <div class="text-center mb-6">
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Pegawai Pemerintah</h4>
                            <p class="text-slate-600 text-sm">Khusus untuk ASN & Pegawai Kontrak</p>
                        </div>
                        
                        <div class="flex justify-center mb-6">
                            <div id="qr-pegawai" class="p-4 bg-gray-50 rounded-lg shadow-inner border-2 border-dashed border-gray-300"></div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-slate-600 text-sm leading-relaxed">Scan QR Code ini untuk mengisi daftar kehadiran pegawai pemerintah</p>
                        </div>
                    </div>
                    
                    <!-- QR Code Non Pegawai -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                        <div class="text-center mb-6">
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Tamu Undangan</h4>
                            <p class="text-slate-600 text-sm">Untuk Masyarakat & Instansi Lain</p>
                        </div>
                        
                        <div class="flex justify-center mb-6">
                            <div id="qr-non-pegawai" class="p-4 bg-gray-50 rounded-lg shadow-inner border-2 border-dashed border-gray-300"></div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-slate-600 text-sm leading-relaxed">Scan QR Code ini untuk mengisi daftar kehadiran tamu undangan</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex-shrink-0 bg-gradient-to-r from-slate-100 to-slate-200 border-t border-slate-300 p-4 rounded-b-lg">
                <div class="flex flex-col lg:flex-row items-center justify-between space-y-3 lg:space-y-0">
                    <!-- Tips Section -->
                    <div class="flex items-center space-x-3 text-slate-600">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-sm">Tips Penggunaan</p>
                            <p class="text-xs">Pastikan pencahayaan cukup dan kamera fokus pada QR Code</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <!-- Download PDF Button -->
                        <button id="downloadQrPdfBtn" type="button" 
                                class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#ac1616] to-red-700 hover:from-red-700 hover:to-red-800 disabled:from-red-400 disabled:to-red-500 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-red-500/50">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm">Unduh PDF</span>
                        </button>
                        


                    </div>
                </div>
                
                <!-- Government Footer Info -->
                <div class="government-footer mt-3 pt-3 border-t border-slate-300">
                    <div class="flex items-center justify-center space-x-2 text-slate-500">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                        </svg>
                        <p class="text-xs font-medium">Sistem Informasi Agenda - Pemerintah Kabupaten Mojokerto</p>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Modal Edit Agenda --}}
    <template x-teleport="body">
        <div
            x-cloak
            x-show="isEditModalOpen"
            x-trap.inert.noscroll="isEditModalOpen"
            @keydown.escape.window="closeModal()"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                class="fixed inset-0 modal-backdrop" 
                @click="closeModal()"
            ></div>

            {{-- Container untuk Modal dengan dua bagian terpisah --}}
            <div
                style="width: 900px; max-height: 88vh;"
                class="relative z-[1000] inline-block align-bottom text-left transform modal-container space-y-4"
                @click.stop
                x-transition:enter="transition ease-out duration-300 delay-50"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-250"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                {{-- Kontainer Form (Kotak Putih) --}}
                <div 
                    class="bg-white/95 backdrop-blur-md rounded-lg px-4 pt-3 pb-3 overflow-y-auto shadow-2xl border border-black/30 mb-2 mt-2" 
                    style="max-height: 88vh;"
                >
                    <div class="flex items-center justify-center mb-6 pb-2 pt-2 relative">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center transition-all duration-200">
                            <svg class="w-6 h-6 mr-3 text-red-700 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Form Edit Agenda</span>
                        </h3>
                        <button 
                            type="button" 
                            @click="closeModal()" 
                            class="absolute right-0 text-gray-400 hover:text-gray-600 hover:scale-110 transition-all duration-200 ease-out rounded-lg p-1 hover:bg-gray-100"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Edit --}}
                    <form 
                        id="edit-agenda-form" 
                        :action="editFormAction"
                        method="POST" 
                        class="space-y-3"
                    >
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="edit_nama_agenda" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Nama Agenda</label>
                            <textarea 
                                name="nama_agenda" 
                                id="edit_nama_agenda" 
                                rows="3" 
                                class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resizable-textarea hover:border-gray-300" 
                                placeholder="Masukkan nama agenda" 
                                required
                                x-model="editAgenda.nama_agenda"
                            ></textarea>
                        </div>
                        
                        <div>
                            <label for="edit_tempat" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Tempat</label>
                            <input 
                                type="text" 
                                name="tempat" 
                                id="edit_tempat" 
                                class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 hover:border-gray-300" 
                                placeholder="Masukkan lokasi agenda" 
                                required
                                x-model="editAgenda.tempat"
                            >
                        </div>
                        
                        <div>
                            <label for="edit_tanggal" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Tanggal</label>
                            <input 
                                type="date" 
                                name="tanggal" 
                                id="edit_tanggal" 
                                class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 hover:border-gray-300" 
                                required
                                x-model="editAgenda.tanggal"
                            >
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="time-picker-container">
                                <label for="edit_jam_mulai" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Waktu Mulai</label>
                                <input 
                                type="time" 
                                name="jam_mulai" 
                                id="edit_jam_mulai" 
                                class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer hover:border-gray-300" 
                                step="60"

                                required
                                x-model="editAgenda.jam_mulai"
                            >
                            </div>
                            <div class="time-picker-container">
                                <label for="edit_jam_selesai" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Waktu Selesai</label>
                                <input 
                                type="time" 
                                name="jam_selesai" 
                                id="edit_jam_selesai" 
                                class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer hover:border-gray-300" 
                                step="60"

                                required
                                x-model="editAgenda.jam_selesai"
                            >
                            </div>
                        </div>
                        
                        <div>
                            <label for="edit_dihadiri" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Dihadiri</label>
                            <textarea 
                                name="dihadiri" 
                                id="edit_dihadiri" 
                                rows="3" 
                                class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resizable-textarea hover:border-gray-300" 
                                placeholder="Masukkan daftar yang hadir dalam agenda ini" 
                                required
                                x-model="editAgenda.dihadiri"
                            ></textarea>
                        </div>
                        
                        {{-- Pesan Validasi --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mt-4">
                            <p class="text-sm text-blue-700 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pastikan semua data sudah benar sebelum menyimpan
                            </p>
                        </div>
                    </form>
                </div>
                
                {{-- Kontainer Tombol Simpan (Di luar kotak putih) --}}
                <div class="flex justify-center">
                    <button 
                        type="button" 
                        @click="confirmEditAgenda()" 
                        class="px-3 py-1 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md text-base font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg hover:shadow-xl transition-all duration-200 ease-out transform hover:scale-105 active:scale-95 border-2 border-red-500/20"
                    >
                        <span class="transition-all duration-150">Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- Modal Delete Confirmation --}}
    <template x-teleport="body">
        <div x-cloak x-show="isDeleteModalOpen" @keydown.escape.window="closeModal()" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="closeModal()"></div>
            <div x-show="isDeleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-[1000] bg-white rounded-2xl shadow-2xl w-full max-w-lg border-2 border-red-200/50 overflow-hidden">
                
                {{-- Header dengan tema pemerintahan --}}
                <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 px-6 py-4 border-b-4 border-yellow-400">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white tracking-wide">KONFIRMASI PENGHAPUSAN AGENDA</h2>
                            <p class="text-red-100 text-sm font-medium">Sistem Informasi Manajemen Agenda</p>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 text-center">
                    {{-- Warning Icon --}}
                    <div class="mx-auto mb-6 w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    
                    {{-- Main Message --}}
                    <h3 class="mb-4 text-2xl font-bold text-gray-800">Konfirmasi Penghapusan</h3>
                    <p class="mb-3 text-lg font-semibold text-gray-700">Apakah Anda yakin ingin menghapus agenda ini?</p>
                    <p class="mb-6 text-sm text-gray-600 leading-relaxed bg-red-50 p-4 rounded-xl border-l-4 border-red-400">
                        <span class="font-semibold text-red-700">⚠️ PERINGATAN:</span> Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait agenda secara permanen dari arsip resmi.
                    </p>
                    
                    <form id="delete-agenda-form" action="{{ route('agenda.destroy', $agenda->agenda_id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    
                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button @click="closeModal()" type="button" 
                                class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold border-2 border-gray-300 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300/50 transform hover:scale-105">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Batal</span>
                            </span>
                        </button>
                        <button @click="submitDeleteForm()" type="button" 
                                class="flex-1 px-6 py-3 text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl font-bold shadow-lg hover:shadow-red-500/25 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-500/50 transform hover:scale-105">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Ya, Hapus Agenda</span>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Footer Branding --}}
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center font-medium">Sistem Manajemen Agenda Resmi • Pemerintah Indonesia</p>
                </div>
            </div>
        </div>
    </template>

    {{-- Confirm Modal untuk Edit --}}
    <template x-teleport="body">
        <div x-cloak x-show="showEditConfirm" @keydown.escape.window="showEditConfirm = false" class="fixed inset-0 z-[1001] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showEditConfirm = false"></div>
            <div x-show="showEditConfirm" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-[1002] bg-white rounded-lg shadow-xl w-full max-w-md">
                
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-blue-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-600">Apakah Anda yakin ingin menyimpan perubahan agenda ini?</h3>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="showEditConfirm = false" type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                            Batal
                        </button>
                        <button @click="submitEditForm(); showEditConfirm = false" type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>



<style class="ignore-pdf">
    /* Override any oklch colors with fallbacks for PDF generation */
    #qr-pegawai, #qr-non-pegawai {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    
    #qr-pegawai *, #qr-non-pegawai * {
        background-color: transparent !important;
        color: inherit !important;
    }
    
    #qr-pegawai canvas, #qr-non-pegawai canvas {
        background-color: #ffffff !important;
    }
    
    /* Ensure no modern CSS color functions are used */
    .qr-container {
        background: #ffffff !important;
        color: #000000 !important;
    }
    
    /* Modal responsive fixes */
    .qr-modal {
        padding: 0.75rem;
    }
    
    @media (max-height: 700px) {
        .qr-modal .modal-content {
            padding: 1rem;
        }
        
        .qr-modal .modal-content > div {
            padding: 1rem;
            space-y: 1rem;
        }
    }
    
    @media (max-height: 600px) {
        .qr-modal .flex-shrink-0 {
            padding: 0.75rem;
        }
        
        .qr-modal .government-footer {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }
    }
</style>

<!-- All QR Code and PDF Scripts are loaded in layout.blade.php -->

<script>
    // Flag untuk memastikan QR code hanya dibuat sekali
    let qrCodesGenerated = false;

    /**
     * Fungsi untuk membuka modal QR dan generate QR codes
     */
    function openQrModal() {
        // Generate QR codes immediately if not already generated
        if (!qrCodesGenerated) {
            // Tunggu sebentar untuk merender modal ke dalam DOM
            setTimeout(() => {
                generateQrCodes();
            }, 100);
        }
    }
    
    /**
     * Fungsi untuk generate QR codes
     */
    function generateQrCodes() {
        console.log('Generating QR codes...');
        
        const baseUrl = window.location.origin;
        const agendaId = {{ $agenda->agenda_id }};
        const urlPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=pegawai`;
        const urlNonPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=non-pegawai`;

        // Retry mechanism for QRious library
        function attemptQrGeneration(retryCount = 0) {
            // 1. Memeriksa apakah library QRious sudah termuat
            if (typeof QRious === 'undefined') {
                if (retryCount < 3) {
                    console.log(`QRious not loaded, retrying... (${retryCount + 1}/3)`);
                    setTimeout(() => attemptQrGeneration(retryCount + 1), 500);
                    return;
                }
                console.error('QRious library not loaded after retries');
                showQrFallback();
                return;
            }

            const pegawaiContainer = document.getElementById('qr-pegawai');
            const nonPegawaiContainer = document.getElementById('qr-non-pegawai');
            
            // 2. Memastikan elemen container untuk QR code sudah ada di DOM
            if (!pegawaiContainer || !nonPegawaiContainer) {
                console.error('QR container elements not found!');
                showQrFallback();
                return;
            }

            try {
                // Bersihkan container sebelum membuat QR code baru
                pegawaiContainer.innerHTML = '';
                nonPegawaiContainer.innerHTML = '';

                // Membuat QR Code untuk Pegawai
                const pegawaiCanvas = document.createElement('canvas');
                new QRious({
                    element: pegawaiCanvas,
                    value: urlPegawai,
                    size: 180,
                    background: 'white',
                    foreground: 'black',
                    level: 'M' // Tingkat koreksi error
                });
                pegawaiContainer.appendChild(pegawaiCanvas);

                // Membuat QR Code untuk Non-Pegawai
                const nonPegawaiCanvas = document.createElement('canvas');
                new QRious({
                    element: nonPegawaiCanvas,
                    value: urlNonPegawai,
                    size: 180,
                    background: 'white',
                    foreground: 'black',
                    level: 'M'
                });
                nonPegawaiContainer.appendChild(nonPegawaiCanvas);

                // 3. Set flag menjadi true agar tidak dibuat ulang
                qrCodesGenerated = true;
                console.log('QR Codes generated successfully!');

            } catch (error) {
                console.error('Error generating QR codes:', error);
                showQrFallback();
            }
        }
        
        attemptQrGeneration();
    }
    
    // Membuat fungsi dapat diakses secara global
    window.openQrModal = openQrModal;
    window.generateQrCodes = generateQrCodes;
    
    // Pre-generate QR codes when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (!qrCodesGenerated) {
                generateQrCodes();
            }
        }, 100);
    });
    
    /**
     * Fungsi fallback jika pembuatan QR code gagal.
     */
    function showQrFallback() {
        const baseUrl = window.location.origin;
        const agendaId = {{ $agenda->agenda_id }};
        const pegawaiUrl = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=pegawai`;
        const nonPegawaiUrl = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=non-pegawai`;

        const pegawaiContainer = document.getElementById('qr-pegawai');
        const nonPegawaiContainer = document.getElementById('qr-non-pegawai');

        const fallbackHtml = (url) => `
            <div class="text-center p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <div class="text-sm text-gray-600 mb-2">QR Code tidak dapat dimuat</div>
                <div class="text-xs text-gray-500 mb-2">Gunakan link berikut:</div>
                <a href="${url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs break-all">${url}</a>
            </div>
        `;

        if (pegawaiContainer) pegawaiContainer.innerHTML = fallbackHtml(pegawaiUrl);
        if (nonPegawaiContainer) nonPegawaiContainer.innerHTML = fallbackHtml(nonPegawaiUrl);
    }
    
    // Skrip untuk download PDF dan Print
    $(document).ready(function() {
        // Event handler untuk tombol download PDF
        $(document).on('click', '#downloadQrPdfBtn', function(e) {
            e.preventDefault();
            console.log('Download PDF button clicked');
            
            const button = $(this);
            const originalText = button.html();
            
            // Tampilkan loading state
            button.html('<span class="flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Membuat PDF...</span>').prop('disabled', true);

            // Pastikan QR code sudah dibuat sebelum download
            if (!qrCodesGenerated) {
                console.log('QR codes not generated yet, generating first...');
                generateQrCodes();
                setTimeout(() => processPdfGeneration(button, originalText), 500);
            } else {
                console.log('QR codes already generated, proceeding with PDF generation...');
                processPdfGeneration(button, originalText);
            }
        });
    });

    function processPdfGeneration(button, originalText) {
        console.log('Starting PDF generation process...');
        
        try {
            // Periksa apakah library jsPDF tersedia
            if (typeof window.jspdf === 'undefined') {
                throw new Error('Library jsPDF tidak tersedia. Pastikan library sudah dimuat.');
            }
            
            const { jsPDF } = window.jspdf;
            console.log('jsPDF library loaded successfully');
            
            // Buat instance PDF baru
            const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
            
            // Header PDF
            pdf.setFontSize(18);
            pdf.text('PEMERINTAH KABUPATEN MOJOKERTO', 105, 20, { align: 'center' });
            pdf.setFontSize(16);
            pdf.text('QR Code Kehadiran Agenda', 105, 35, { align: 'center' });
            pdf.setFontSize(14);
            pdf.text("{{ Illuminate\Support\Str::limit($agenda->nama_agenda, 50) }}", 105, 50, { align: 'center' });
            
            // Ambil canvas QR code
            const pegawaiCanvas = document.querySelector('#qr-pegawai canvas');
            const nonPegawaiCanvas = document.querySelector('#qr-non-pegawai canvas');
            
            console.log('Pegawai canvas found:', !!pegawaiCanvas);
            console.log('Non-pegawai canvas found:', !!nonPegawaiCanvas);

            // Tambahkan QR Code Pegawai jika ada
            if (pegawaiCanvas) {
                try {
                    const pegawaiDataUrl = pegawaiCanvas.toDataURL('image/png');
                    pdf.addImage(pegawaiDataUrl, 'PNG', 20, 95, 65, 65);
                    pdf.text('QR Code Pegawai Pemerintah', 52.5, 85, { align: 'center' });
                    console.log('Pegawai QR code added to PDF');
                } catch (canvasError) {
                    console.error('Error processing pegawai canvas:', canvasError);
                }
            }

            // Tambahkan QR Code Non-Pegawai jika ada
            if (nonPegawaiCanvas) {
                try {
                    const nonPegawaiDataUrl = nonPegawaiCanvas.toDataURL('image/png');
                    pdf.addImage(nonPegawaiDataUrl, 'PNG', 125, 95, 65, 65);
                    pdf.text('QR Code Tamu Undangan', 157.5, 85, { align: 'center' });
                    console.log('Non-pegawai QR code added to PDF');
                } catch (canvasError) {
                    console.error('Error processing non-pegawai canvas:', canvasError);
                }
            }
            
            // Simpan PDF
             const fileName = `QR-Code-{{ Str::slug($agenda->nama_agenda) }}.pdf`;
             pdf.save(fileName);
             console.log('PDF saved successfully:', fileName);

        } catch (error) {
            console.error('PDF generation error:', error);
            alert('Gagal membuat PDF: ' + error.message + '\n\nSilakan coba lagi atau hubungi administrator.');
        } finally {
            // Kembalikan tombol ke state semula
            button.html(originalText).prop('disabled', false);
            console.log('PDF generation process completed');
        }
    }




    

</script>
