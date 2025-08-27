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

    <div x-data="{
        isCreateModalOpen: false,
        isEditModalOpen: false,
        isDeleteModalOpen: false,
        editAgenda: {},
        editFormAction: '',
        showCreateConfirm: false,
        showEditConfirm: false,
        showDeleteConfirm: false,
        pendingAction: null,
        activeTab: 'detail',
    
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
            // Close modal with smooth animation
            this.isCreateModalOpen = false;
            this.isEditModalOpen = false;
            this.isDeleteModalOpen = false;
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
        class="h-screen flex flex-col overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">

        <!-- Header Halaman -->
        <div class="bg-transparent border-b border-gray-100 flex-shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-red-700 to-orange-700 bg-clip-text text-transparent break-words">
                            Detail Agenda: {{ $agenda->nama_agenda }}
                        </h1>
                        <p class="text-gray-600 mt-1">Informasi lengkap agenda dan peserta.</p>
                    </div>
                    <a href="{{ route('agenda.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-l from-red-700 to-orange-700 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-transform">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-2">
                {{-- Navigasi Tab --}}
                <div class="mb-10">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" role="presentation">
                            <button @click="activeTab = 'detail'"
                                :class="activeTab === 'detail' ? 'border-blue-600 text-blue-600' :
                                    'border-transparent text-gray-500 hover:text-blue-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Detail
                                Agenda</button>
                            <button @click="activeTab = 'tamu'"
                                :class="activeTab === 'tamu' ? 'border-green-600 text-green-600' :
                                    'border-transparent text-gray-500 hover:text-green-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Daftar
                                Tamu</button>
                            <button @click="activeTab = 'notulen'"
                                :class="activeTab === 'notulen' ? 'border-purple-600 text-purple-600' :
                                    'border-transparent text-gray-500 hover:text-purple-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Notulen</button>
                        </nav>
                    </div>

                    {{-- Konten Tab --}}
                    <div class="mt-6 h-[calc(100vh-280px)] overflow-y-auto">
                        {{-- PERUBAHAN: Menggunakan x-if alih-alih x-show untuk menghindari masalah CSS --}}
                        <template x-if="activeTab === 'detail'">
                            <div role="tabpanel"
                                class="h-full bg-blue-50 rounded-lg border-2 border-dashed border-blue-200">
                                <x-isiDetailAgenda :agenda="$agenda" />
                            </div>
                        </template>
                        
                        <template x-if="activeTab === 'tamu'">
                            <div role="tabpanel"
                                class="h-full flex flex-col bg-green-50 rounded-lg border-2 border-dashed border-green-200">
                                <x-isiDaftarTamu :agenda="$agenda" />
                            </div>
                        </template>
                        
                        <template x-if="activeTab === 'notulen'">
                            <div role="tabpanel"
                                class="h-full flex flex-col items-center justify-center bg-purple-50 rounded-lg border-2 border-dashed border-purple-200 p-8">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Manajemen Notulen</h3>
                                <p class="text-gray-600 mt-2 mb-6 max-w-md text-center">Klik tombol di bawah untuk membuka halaman
                                    editor, di mana Anda dapat menulis atau mengedit notulen untuk agenda ini.</p>
                                @php
                                    $route = $agenda->notulen
                                        ? route('agenda.notulen.edit', [
                                            'agenda' => $agenda->agenda_id,
                                            'notulen' => $agenda->notulen->id,
                                        ])
                                        : route('agenda.notulen.create', ['agenda' => $agenda->agenda_id]);
                                @endphp
                                <a href="{{ $route }}"
                                    class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-lg hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-lg flex items-center text-lg font-bold transform hover:scale-105 transition-all">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z">
                                        </path>
                                    </svg>
                                    @if ($agenda->notulen)
                                        Edit Notulen
                                    @else
                                        Buat Notulen Baru
                                    @endif
                                </a>
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
            class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="isDeleteModalOpen = false"></div>
            <div x-show="isDeleteModalOpen" class="relative z-[1000] bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-14 h-14" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-600">Apakah Anda yakin ingin menghapus agenda ini?
                    </h3>
                    <form id="delete-agenda-form" action="{{ route('agenda.destroy', $agenda->agenda_id) }}"
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button @click="submitDeleteForm()" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Ya, Hapus
                    </button>
                    <button @click="isDeleteModalOpen = false" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                        Batal
                    </button>
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
</x-layout>
