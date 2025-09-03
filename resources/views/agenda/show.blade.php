{{-- resources/views/agenda/show.blade.php --}}
<x-layout>
    <x-slot:title>Detail Agenda</x-slot:title>

    <x-slot:styles>
        {{-- jQuery tidak lagi diperlukan --}}
    </x-slot:styles>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Alert styles */
        [role="alert"] {
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .popup-alert {
            transform: translateX(-50%);
        }

        @keyframes fade-out-up {
            0% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
    </style>

    @if (session('success'))
        <div id="popup-alert" class="fixed top-[10%] left-1/2 z-50 popup-alert">
            <div class="flex items-center border-l-4 border-green-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform"
                role="alert">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-green-700 text-center">{{ session('success') }}</p>
                </div>
                <button type="button" class="group ml-4" onclick="hideAlert()">
                    <svg class="h-5 w-5 stroke-green-500 group-hover:stroke-green-700 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <script>
            function hideAlert() {
                const alert = document.getElementById('popup-alert');
                if (alert) {
                    alert.style.animation = 'fade-out-up 0.3s ease-in forwards';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }
            }

            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideAlert();
            }, 5000);
        </script>
    @endif

    @if (session('error'))
        <div id="popup-alert-error" class="fixed top-[10%] left-1/2 z-50 popup-alert">
            <div class="flex items-center border-l-4 border-red-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform"
                role="alert">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-red-700 text-center">{{ session('error') }}</p>
                </div>
                <button type="button" class="group ml-4" onclick="hideAlertError()">
                    <svg class="h-5 w-5 stroke-red-500 group-hover:stroke-red-700 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <script>
            function hideAlertError() {
                const alert = document.getElementById('popup-alert-error');
                if (alert) {
                    alert.style.animation = 'fade-out-up 0.3s ease-in forwards';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }
            }

            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideAlertError();
            }, 5000);
        </script>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $index => $error)
            <div id="popup-alert-validation-{{ $index }}"
                class="fixed top-[{{ 10 + $index * 8 }}%] left-1/2 z-50 popup-alert">
                <div class="flex items-center border-l-4 border-red-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform"
                    role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-red-700 text-center">{{ $error }}</p>
                    </div>
                    <button type="button" class="group ml-4" onclick="hideAlertValidation({{ $index }})">
                        <svg class="h-5 w-5 stroke-red-500 group-hover:stroke-red-700 transition-colors duration-200"
                            fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
        <script>
            function hideAlertValidation(index) {
                const alert = document.getElementById('popup-alert-validation-' + index);
                if (alert) {
                    alert.style.animation = 'fade-out-up 0.3s ease-in forwards';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }
            }

            // Auto-hide validation errors after 7 seconds
            @foreach ($errors->all() as $index => $error)
                setTimeout(() => {
                    hideAlertValidation({{ $index }});
                }, 7000);
            @endforeach
        </script>
    @endif

    <div id="agendaTabComponent" x-data="{
        isCreateModalOpen: false,
        isEditModalOpen: false,
        isDeleteModalOpen: false,
        showQrModal: false,
        editAgenda: {},
        editFormAction: '',
        showCreateConfirm: false,
        showEditConfirm: false,
        showDeleteConfirm: false,
        pendingAction: null,
        activeTab: 'detail',
        
        // ... di dalam x-data ...
init() {
    // PRIORITAS 1: Baca parameter '?tab=' dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromUrl = urlParams.get('tab');

    if (tabFromUrl === 'notulen' || tabFromUrl === 'tamu') {
        this.activeTab = tabFromUrl;
        return; // Hentikan jika tab sudah diatur dari parameter URL
    }
    
    // PRIORITAS 2 (Fallback): Logika lama Anda untuk hash (#) dan sessionStorage
    const urlFragment = window.location.hash.substring(1);
    const storedTab = sessionStorage.getItem('activeTab');
    
    if (urlFragment === 'notulen' || storedTab === 'notulen') {
        this.activeTab = 'notulen';
    } else if (urlFragment === 'tamu' || storedTab === 'tamu') {
        this.activeTab = 'tamu';
    }

    // Selalu hapus sessionStorage setelah digunakan
    sessionStorage.removeItem('activeTab');
},
// ...,
    
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
        openQrModal() {
            this.showQrModal = true;
            // Call the global generateQrCodes function to ensure QR codes are generated
            if (typeof window.generateQrCodes === 'function') {
                setTimeout(() => {
                    window.generateQrCodes();
                }, 100);
            }
        },
        closeModal() {
            // Close modal with smooth animation
            this.isCreateModalOpen = false;
            this.isEditModalOpen = false;
            this.isDeleteModalOpen = false;
            this.showQrModal = false;
            // Reset form data after animation completes
            setTimeout(() => {
                this.editAgenda = {};
                this.editFormAction = '';
            }, 350);
        },
        confirmCreateAgenda() {
            this.showCreateConfirm = true;
        },
        confirmEditAgenda() {
            this.showEditConfirm = true;
        },
        submitCreateForm() {
            document.getElementById('create-agenda-form').submit();
        },
        submitEditForm() {
            document.getElementById('edit-agenda-form').submit();
        },
        submitDeleteForm() {
            document.getElementById('delete-agenda-form').submit();
        }
    }" @open-delete-modal.window="openDeleteModal()"
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50 relative overflow-hidden">

        <!-- Header Halaman -->
        <div class="relative bg-white border-b border-gray-200 shadow-sm">
            <!-- Government theme background -->
            <div class="absolute inset-0 bg-gradient-to-r from-red-50/30 via-white to-slate-50/30"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb Navigation -->
                

                <!-- Main Header Content -->
                <div class="py-6">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <!-- Left Section: Title and Info -->
                        <div class="flex-1 space-y-4">
                            <!-- Title with Government Icon -->
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#ac1616] to-red-700 rounded-lg flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 leading-tight">
                                        Detail Agenda Pemerintah
                                    </h1>
                                </div>
                            </div>

                            <!-- Agenda Info Card -->
                            <div class="w-304 bg-gradient-to-r from-slate-50 to-gray-50 rounded-lg px-5 py-4 border border-gray-200 shadow-xl">
                                <div class="flex items-center space-x-5">
                                    <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-center min-w-0">
                                        <h2 class="font-bold text-lg text-slate-800 mb-1 leading-tight">{{ $agenda->nama_agenda }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3 lg:flex-shrink-0">
                            <a href="{{ route('agenda.index') }}"
                                class="group inline-flex items-center px-3 py-2 bg-[#ac1616] hover:bg-red-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#ac1616] focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Daftar
                            </a>
                            
                            @auth('admin')
                                @if(auth('admin')->user()->opd_admin === $agenda->opd_penyelenggara || auth('admin')->user()->role === 'super_admin')
                                    <a href="{{ route('agenda.edit', $agenda->agenda_id) }}"
                                        class="group inline-flex items-center px-3 py-2 bg-[#ac1616] hover:bg-red-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#ac1616] focus:ring-offset-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Agenda
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-40 sm:px-6 lg:px-8">
                {{-- Navigasi Tab --}}
                <div class="mb-8">
                    <div class="bg-white/70 backdrop-blur-sm rounded-lg p-2 shadow-lg border border-gray-200/50">
                        <nav class="flex space-x-1" role="presentation">
                            <button @click="activeTab = 'detail'"
                                :class="activeTab === 'detail' ? 
                                    'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg scale-105' :
                                    'text-gray-600 hover:text-blue-600 hover:bg-blue-50/50'"
                                class="group relative flex-1 flex items-center justify-center px-6 py-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-102">
                                <svg class="w-5 h-5 mr-2" :class="activeTab === 'detail' ? 'text-white' : 'text-blue-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Detail Agenda
                            </button>
                            <button @click="activeTab = 'tamu'"
                                :class="activeTab === 'tamu' ? 
                                    'bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg scale-105' :
                                    'text-gray-600 hover:text-emerald-600 hover:bg-emerald-50/50'"
                                class="group relative flex-1 flex items-center justify-center px-6 py-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-102">
                                <svg class="w-5 h-5 mr-2" :class="activeTab === 'tamu' ? 'text-white' : 'text-emerald-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Daftar Tamu
                            </button>
                            <button @click="activeTab = 'notulen'"
                                :class="activeTab === 'notulen' ? 
                                    'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg scale-105' :
                                    'text-gray-600 hover:text-red-600 hover:bg-red-50/50'"
                                class="group relative flex-1 flex items-center justify-center px-6 py-4 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-102">
                                <svg class="w-5 h-5 mr-2" :class="activeTab === 'notulen' ? 'text-white' : 'text-red-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path>
                                </svg>
                                Notulen
                            </button>
                        </nav>
                    </div>

                    {{-- Konten Tab --}}
                    <div class="mt-8">
                        {{-- Tab Content dengan animasi smooth --}}
                        <template x-if="activeTab === 'detail'">
                            <div role="tabpanel" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-gray-200/50 overflow-hidden">
                                <x-isiDetailAgenda :agenda="$agenda" />
                            </div>
                        </template>
                        
                        <template x-if="activeTab === 'tamu'">
                            <div role="tabpanel"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-gray-200/50 overflow-hidden">
                                <x-isiDaftarTamu :agenda="$agenda" />
                            </div>
                        </template>
                        
                        <template x-if="activeTab === 'notulen'">
                            <div role="tabpanel"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-gray-200/50 p-8 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 bg-gradient-to-br from-red-500 via-red-500 to-red-600 rounded-lg flex items-center justify-center mb-8 shadow-2xl mx-auto transform hover:scale-105 transition-transform duration-300">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Notulen</h3>
                                    <p class="text-gray-600 text-lg leading-relaxed mb-2">Kelola dan edit notulen rapat untuk agenda ini dengan mudah melalui editor yang tersedia.</p>
                                    
                                    @php
                                        $route = $agenda->notulen
                                            ? route('agenda.notulen.edit', [
                                                'agenda' => $agenda->agenda_id,
                                                'notulen' => $agenda->notulen->id,
                                            ])
                                            : route('agenda.notulen.create', ['agenda' => $agenda->agenda_id]);
                                    @endphp
                                    
                                    <a href="{{ $route }}"
                                    class="group inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 via-red-600 to-red-700 hover:from-red-700 hover:via-red-700 hover:to-red-800 text-white rounded-lg shadow-2xl hover:shadow-red-500/25 
                                            transform hover:scale-105 transition-all duration-300 
                                            focus:outline-none focus:ring-2 focus:ring-red-500/50 text-lg font-semibold">
                                        
                                        {{-- SVG Kiri --}}
                                        <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path>
                                        </svg>

                                        {{-- Teks Tombol --}}
                                        @if ($agenda->notulen)
                                            Edit Notulen
                                        @else
                                            Buat Notulen Baru
                                        @endif

                                        {{-- SVG Kanan --}}
                                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Form Dinamis --}}
    @include('modal.tambah')
    <template x-teleport="body">
        <div x-cloak x-show="isDeleteModalOpen" @keydown.escape.window="isDeleteModalOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="isDeleteModalOpen = false"></div>
            <div x-show="isDeleteModalOpen" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative z-[1000] bg-white/95 backdrop-blur-sm rounded-lg shadow-2xl border border-gray-200/50 w-full max-w-md">
                <div class="p-8 text-center">
                    <!-- Warning Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">Apakah Anda yakin ingin menghapus agenda ini? Tindakan ini tidak dapat dibatalkan dan semua data terkait akan hilang permanen.</p>
                    
                    <form id="delete-agenda-form" action="{{ route('agenda.destroy', $agenda->agenda_id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button @click="isDeleteModalOpen = false" type="button" 
                            class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300/50">
                            Batal
                        </button>
                        <button @click="submitDeleteForm()" type="button" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500/50">
                            Hapus Agenda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Confirm Modal untuk Edit --}}
    <x-confirm message="Apakah Anda yakin ingin menyimpan perubahan agenda ini?" confirm-text="Simpan"
        cancel-text="Batal" x-show="showEditConfirm" @confirm="submitEditForm(); showEditConfirm = false"
        @cancel="showEditConfirm = false" />

    </div>

    <x-slot:scripts>
    </x-slot:scripts>
    {{-- Di dalam file resources/views/agenda/show.blade.php --}}

</x-layout>
