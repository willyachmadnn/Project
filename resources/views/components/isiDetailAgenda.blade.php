{{-- resources/views/components/isiDetailAgenda.blade.php --}}
@props(['agenda'])

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
}" class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 h-full overflow-y-auto shadow-lg border border-gray-200">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Detail Agenda</h3>
                <p class="text-gray-500 mt-1">Informasi lengkap agenda rapat</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button @click="showQrModal = true; openQrModal()" class="group px-5 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl shadow-lg hover:shadow-emerald-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12v4m6-4h.01M12 8h.01M12 8h4.01M12 8H7.99M12 8V4m0 0H7.99M12 4h4.01"></path></svg>
                QR Code
            </button>
             <button @click="openEditModal()" class="group px-5 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </button>
            {{-- PERBAIKAN FINAL: Menggunakan $dispatch untuk mengirim sinyal --}}
            <button @click="openDeleteModal()" class="group px-5 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-500/50 flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="group bg-gradient-to-br from-emerald-50 to-green-50 hover:from-emerald-100 hover:to-green-100 p-6 rounded-2xl border border-emerald-200/50 hover:border-emerald-300/50 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Tempat</label>
                </div>
                <p class="text-gray-900 font-bold text-lg">{{ $agenda->tempat }}</p>
            </div>
            
            <div class="group bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 p-6 rounded-2xl border border-purple-200/50 hover:border-purple-300/50 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v11a2 2 0 002 2h6a2 2 0 002-2V8a1 1 0 00-1-1V7"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Tanggal</label>
                </div>
                <p class="text-gray-900 font-bold text-lg">{{ $agenda->tanggal->format('d F Y') }}</p>
            </div>
            
            <div class="group bg-gradient-to-br from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 p-6 rounded-2xl border border-orange-200/50 hover:border-orange-300/50 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-orange-700 uppercase tracking-wide">Waktu</label>
                </div>
                <p class="text-gray-900 font-bold text-lg">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</p>
            </div>
            
            <div class="group bg-gradient-to-br from-teal-50 to-cyan-50 hover:from-teal-100 hover:to-cyan-100 p-6 rounded-2xl border border-teal-200/50 hover:border-teal-300/50 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-teal-700 uppercase tracking-wide">Nama Admin</label>
                </div>
                <p class="text-gray-900 font-bold text-lg">{{ $agenda->admin->nama_admin ?? 'Data belum diisi' }}</p>
            </div>
            
            <div class="group bg-gradient-to-br from-indigo-50 to-blue-50 hover:from-indigo-100 hover:to-blue-100 p-6 rounded-2xl border border-indigo-200/50 hover:border-indigo-300/50 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">OPD Admin</label>
                </div>
                <p class="text-gray-900 font-bold text-lg">{{ $agenda->admin->opd_admin ?? 'Data belum diisi' }}</p>
            </div>
            
            <div class="group bg-gradient-to-br from-rose-50 to-pink-50 hover:from-rose-100 hover:to-pink-100 p-6 rounded-2xl border border-rose-200/50 hover:border-rose-300/50 transition-all duration-300 hover:shadow-lg md:col-span-2 lg:col-span-3">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <label class="text-sm font-semibold text-rose-700 uppercase tracking-wide">Dihadiri</label>
                </div>
                <p class="text-gray-900 font-bold text-lg leading-relaxed">{{ $agenda->dihadiri ?? 'Data belum diisi' }}</p>
            </div>
        </div>
    <div class="mt-8">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200/50">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <label class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Status Agenda</label>
            </div>
            
            <div class="flex justify-center">
                @if($agenda->status === 'Menunggu')
                    <div class="inline-flex items-center px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-400 to-yellow-500 text-white shadow-lg hover:shadow-amber-500/25 transform hover:scale-105 transition-all duration-200">
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">Agenda Menunggu</span>
                    </div>
                @elseif($agenda->status === 'Berlangsung')
                    <div class="inline-flex items-center px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 animate-pulse">
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">Sedang Berlangsung</span>
                    </div>
                @else
                    <div class="inline-flex items-center px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg hover:shadow-emerald-500/25 transform hover:scale-105 transition-all duration-200">
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-lg">Agenda Selesai</span>
                    </div>
                @endif
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
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showQrModal = false"></div>
            <div x-show="showQrModal" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                 class="relative z-[1002] bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-200/50 w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">QR Code Agenda</h3>
                            <p class="text-blue-100 mt-1">{{ $agenda->nama_agenda }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showQrModal = false" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="modal-content p-8 space-y-6 overflow-y-auto bg-gradient-to-br from-gray-50 to-white">
                <!-- QR Codes Container -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- QR Code Pegawai -->
                    <div class="group bg-gradient-to-br from-emerald-50 to-green-50 hover:from-emerald-100 hover:to-green-100 p-8 rounded-3xl border border-emerald-200/50 hover:border-emerald-300/50 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/10 transform hover:scale-105">
                        <div class="flex items-center justify-center mb-6">
                            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-bold text-emerald-700">Untuk Pegawai</h4>
                        </div>
                        <div class="flex justify-center mb-6">
                            <div id="qr-pegawai" class="p-6 bg-white rounded-2xl shadow-lg border border-emerald-200/30"></div>
                        </div>
                        <p class="text-emerald-600 font-medium text-center leading-relaxed">Scan QR Code ini untuk mengisi form kehadiran pegawai</p>
                    </div>
                    
                    <!-- QR Code Non Pegawai -->
                    <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 p-8 rounded-3xl border border-blue-200/50 hover:border-blue-300/50 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/10 transform hover:scale-105">
                        <div class="flex items-center justify-center mb-6">
                            <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-bold text-blue-700">Untuk Non Pegawai</h4>
                        </div>
                        <div class="flex justify-center mb-6">
                            <div id="qr-non-pegawai" class="p-6 bg-white rounded-2xl shadow-lg border border-blue-200/30"></div>
                        </div>
                        <p class="text-blue-600 font-medium text-center leading-relaxed">Scan QR Code ini untuk mengisi form kehadiran tamu</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-content bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-t border-gray-200/50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <p class="font-medium">Tips: Pastikan kamera dapat membaca QR Code dengan jelas</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="downloadQrPdfBtn" type="button" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 disabled:from-blue-400 disabled:to-indigo-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-500/50">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                        <button @click="showQrModal = false" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-gray-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-500/50">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Modal Edit Agenda --}}
    <template x-teleport="body">
        <div x-cloak x-show="isEditModalOpen" @keydown.escape.window="closeModal()" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>
            <div x-show="isEditModalOpen" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                 class="relative z-[1000] bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-200/50 w-full max-w-3xl max-h-[90vh] overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold">Edit Agenda</h3>
                                <p class="text-blue-100 mt-1">Perbarui informasi agenda</p>
                            </div>
                        </div>
                        <button type="button" @click="closeModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Modal Content --}}
                <div class="p-8 space-y-8 max-h-[60vh] overflow-y-auto bg-gradient-to-br from-gray-50 to-white">
                    <form id="edit-agenda-form" :action="editFormAction" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            {{-- Nama Agenda - Full Width --}}
                            <div class="space-y-3">
                                <label for="edit_nama_agenda" class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Nama Agenda
                                </label>
                                <textarea name="nama_agenda" id="edit_nama_agenda" rows="3" 
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300 resize-none" 
                                        placeholder="Masukkan nama agenda" required
                                        x-model="editAgenda.nama_agenda"></textarea>
                            </div>
                            
                            {{-- Tempat dan Tanggal - 2 Columns on larger screens --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <label for="edit_tempat" class="block text-sm font-semibold text-gray-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Tempat
                                    </label>
                                    <input type="text" name="tempat" id="edit_tempat" 
                                           class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-300" 
                                           placeholder="Masukkan tempat agenda" required
                                           x-model="editAgenda.tempat">
                                </div>
                                
                                <div class="space-y-3">
                                    <label for="edit_tanggal" class="block text-sm font-semibold text-gray-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Tanggal
                                    </label>
                                    <input type="date" name="tanggal" id="edit_tanggal" 
                                           class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 hover:border-gray-300" 
                                           required x-model="editAgenda.tanggal">
                                </div>
                            </div>
                            
                            {{-- Waktu Section --}}
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Waktu
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label for="edit_jam_mulai" class="block text-xs font-medium text-gray-600">Jam Mulai</label>
                                        <input type="time" name="jam_mulai" id="edit_jam_mulai" 
                                               class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 hover:border-gray-300" 
                                               required x-model="editAgenda.jam_mulai">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="edit_jam_selesai" class="block text-xs font-medium text-gray-600">Jam Selesai</label>
                                        <input type="time" name="jam_selesai" id="edit_jam_selesai" 
                                               class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 hover:border-gray-300" 
                                               required x-model="editAgenda.jam_selesai">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Dihadiri - Full Width --}}
                            <div class="space-y-3">
                                <label for="edit_dihadiri" class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Dihadiri
                                </label>
                                <textarea name="dihadiri" id="edit_dihadiri" rows="4" 
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-300 resize-none" 
                                        placeholder="Masukkan daftar peserta yang hadir..." required
                                        x-model="editAgenda.dihadiri"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                
                {{-- Modal Footer --}}
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 border-t border-gray-200/50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <p class="font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pastikan semua data sudah benar sebelum menyimpan
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" @click="closeModal()" 
                                    class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-gray-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-500/50">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>
                            <button type="button" @click="confirmEditAgenda()" 
                                    class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-500/50">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Modal Delete Confirmation --}}
    <template x-teleport="body">
        <div x-cloak x-show="isDeleteModalOpen" @keydown.escape.window="closeModal()" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="closeModal()"></div>
            <div x-show="isDeleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-[1000] bg-white rounded-lg shadow-xl w-full max-w-md">
                
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-600">Apakah Anda yakin ingin menghapus agenda ini?</h3>
                    <p class="mb-6 text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait agenda.</p>
                    
                    <form id="delete-agenda-form" action="{{ route('agenda.destroy', $agenda->agenda_id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="closeModal()" type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                            Batal
                        </button>
                        <button @click="submitDeleteForm()" type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-800 rounded-lg transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
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
</style>

<!-- All QR Code and PDF Scripts are loaded in layout.blade.php -->

<script>
    function openQrModal() {
        console.log('Opening QR Modal...');
        
        // Force immediate generation without waiting
        generateQrCodes();
    }
    
    function generateQrCodes() {
        console.log('Generating QR Codes...');
        
        const baseUrl = window.location.origin;
        const agendaId = {{ $agenda->agenda_id }};
        
        const urlPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=pegawai`;
        const urlNonPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=non-pegawai`;
        
        console.log('QR URLs:', urlPegawai, urlNonPegawai);
        
        const pegawaiContainer = document.getElementById('qr-pegawai');
        const nonPegawaiContainer = document.getElementById('qr-non-pegawai');
        
        if (!pegawaiContainer || !nonPegawaiContainer) {
            console.error('Containers not found!');
            return;
        }
        
        // Clear containers completely
        pegawaiContainer.innerHTML = '';
        nonPegawaiContainer.innerHTML = '';
        
        try {
            // Create QR codes with simple approach
            if (typeof QRCode !== 'undefined') {
                // Clear any existing QR instances
                if (window.qrPegawai) {
                    window.qrPegawai.clear();
                    window.qrPegawai = null;
                }
                if (window.qrNonPegawai) {
                    window.qrNonPegawai.clear();
                    window.qrNonPegawai = null;
                }
                
                window.qrPegawai = new QRCode(pegawaiContainer, {
                    text: urlPegawai,
                    width: 180,
                    height: 180
                });
                
                window.qrNonPegawai = new QRCode(nonPegawaiContainer, {
                    text: urlNonPegawai,
                    width: 180,
                    height: 180
                });
                
                console.log('QR Codes generated successfully!');
            } else {
                console.error('QRCode library not available');
                // Fallback: create simple text
                pegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid #ccc;">QR Pegawai<br>' + urlPegawai + '</div>';
                nonPegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid #ccc;">QR Non-Pegawai<br>' + urlNonPegawai + '</div>';
            }
        } catch (error) {
            console.error('QR Generation Error:', error);
            pegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid red;">Error: ' + error.message + '</div>';
            nonPegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid red;">Error: ' + error.message + '</div>';
        }
    }
    
    // Simplified PDF download
    $(document).ready(function() {
        $(document).on('click', '#downloadQrPdfBtn', function() {
            const button = $(this);
            const originalText = button.html();
            button.html('Generating PDF...').prop('disabled', true);

            try {
                // Check if QR codes already exist, if not generate them
                const qrPegawai = document.getElementById('qr-pegawai');
                const qrNonPegawai = document.getElementById('qr-non-pegawai');
                
                if (!qrPegawai || !qrNonPegawai) {
                    throw new Error('QR containers not found');
                }
                
                // Check if QR codes are already generated
                const pegawaiHasContent = qrPegawai.innerHTML.trim() !== '';
                const nonPegawaiHasContent = qrNonPegawai.innerHTML.trim() !== '';
                
                if (!pegawaiHasContent || !nonPegawaiHasContent) {
                    console.log('QR codes not found, generating...');
                    generateQrCodes();
                    // Wait longer for generation to complete
                    setTimeout(() => {
                        processPdfGeneration();
                    }, 3000);
                } else {
                    console.log('QR codes already exist, proceeding with PDF generation...');
                    processPdfGeneration();
                }
                
                function processPdfGeneration() {
                    try {
                        // Ultra-simple approach: Generate PDF without html2canvas
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        });
                        
                        // Add title
                        pdf.setFontSize(20);
                        pdf.text('QR Code Agenda', 105, 30, { align: 'center' });
                        
                        pdf.setFontSize(16);
                        pdf.text('{{ $agenda->nama_agenda }}', 105, 45, { align: 'center' });
                        
                        // Add QR section headers
                        pdf.setFontSize(14);
                        pdf.text('QR Code Pegawai', 52.5, 70, { align: 'center' });
                        pdf.text('QR Code Non-Pegawai', 157.5, 70, { align: 'center' });
                        
                        // Get QR canvas elements
                        const qrPegawai = document.getElementById('qr-pegawai');
                        const qrNonPegawai = document.getElementById('qr-non-pegawai');
                        
                        let addedImages = 0;
                        const totalImages = 2;
                        
                        function checkComplete() {
                            addedImages++;
                            if (addedImages >= totalImages) {
                                pdf.save(`QR-Code-{{ Str::slug($agenda->nama_agenda) }}.pdf`);
                                button.html(originalText).prop('disabled', false);
                                console.log('PDF generated successfully!');
                            }
                        }
                        
                        // Add QR images directly from canvas
                        const pegawaiCanvas = qrPegawai ? qrPegawai.querySelector('canvas') : null;
                        if (pegawaiCanvas) {
                            try {
                                const imgData = pegawaiCanvas.toDataURL('image/png');
                                pdf.addImage(imgData, 'PNG', 20, 80, 65, 65);
                            } catch (e) {
                                console.warn('Failed to add pegawai QR:', e);
                                pdf.rect(20, 80, 65, 65);
                                pdf.text('QR Pegawai', 52.5, 115, { align: 'center' });
                            }
                        } else {
                            pdf.rect(20, 80, 65, 65);
                            pdf.text('QR Pegawai', 52.5, 115, { align: 'center' });
                        }
                        checkComplete();
                        
                        const nonPegawaiCanvas = qrNonPegawai ? qrNonPegawai.querySelector('canvas') : null;
                        if (nonPegawaiCanvas) {
                            try {
                                const imgData = nonPegawaiCanvas.toDataURL('image/png');
                                pdf.addImage(imgData, 'PNG', 125, 80, 65, 65);
                            } catch (e) {
                                console.warn('Failed to add non-pegawai QR:', e);
                                pdf.rect(125, 80, 65, 65);
                                pdf.text('QR Non-Pegawai', 157.5, 115, { align: 'center' });
                            }
                        } else {
                            pdf.rect(125, 80, 65, 65);
                            pdf.text('QR Non-Pegawai', 157.5, 115, { align: 'center' });
                        }
                        checkComplete();
                        
                    } catch (error) {
                        console.error('PDF generation error:', error);
                        alert('PDF generation failed: ' + (error.message || 'Unknown error'));
                        button.html(originalText).prop('disabled', false);
                    }
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                button.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
