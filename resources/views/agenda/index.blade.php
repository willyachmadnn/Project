{{-- resources/views/agenda/index.blade.php --}}
<x-layout>
    <style>
        [x-cloak]{ display:none!important }
        /* Optimized minimal styles */
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
        
        /* Reduced animations for better performance */
        .modern-card {
            transition: transform 0.2s ease;
        }
        .modern-card:hover {
            transform: translateY(-2px);
        }

        .modern-card:active {
            transform: translateY(0) scale(0.98) !important;
        }

        .modern-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <x-slot:title>Daftar Agenda</x-slot:title>

    <div
        x-data="{
            isCreateModalOpen: false,
            isEditModalOpen: false,
            editAgenda: {},
            editFormAction: '',
            showCreateConfirm: false,
            showEditConfirm: false,
            showDeleteConfirm: false,
            pendingAction: null,
            openEditModal(agenda) {
                this.editAgenda = {
                    ...agenda,
                    tanggal: new Date(agenda.tanggal).toISOString().split('T')[0],
                    jam_mulai: (agenda.jam_mulai || '').toString().substring(0,5),
                    jam_selesai: (agenda.jam_selesai || '').toString().substring(0,5)
                };
                const actionUrl = '{{ route('agenda.update', ':id') }}';
                this.editFormAction = actionUrl.replace(':id', agenda.agenda_id);
                this.isEditModalOpen = true;
            },
            closeModal() {
                // Close modal with smooth animation
                this.isCreateModalOpen = false;
                this.isEditModalOpen = false;
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
            }
        }"
    >
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            @if(session('success'))
                <div id="popup-alert" class="fixed top-[10%] left-1/2 z-50 popup-alert">
                    <div class="flex items-center border-l-4 border-green-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform" role="alert">
                        <div class="flex-shrink-0 mr-3">
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-green-700 text-center">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="group ml-4" onclick="hideAlert()">
                            <svg class="h-5 w-5 stroke-green-500 group-hover:stroke-green-700 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2">
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
            
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Agenda</h1>
                    <p class="text-sm text-gray-500">SI-AGENDA Pemerintah Kabupaten Mojokerto</p>
                </div>

                <button type="button"
                    @click="isCreateModalOpen = true"
                    class="group inline-flex w-40 items-center rounded-md  bg-red-700 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800 transition-all duration-300 ease-in-out">
                    <span class="mr-1 transition-transform duration-300 transform group-hover:rotate-90">+</span>
                    <span class="flex-grow text-center mx-2">Tambah Agenda</span>
                </button>
            </div>
            
            <div id="kpi-cards" class="mb-16">
                <section class="relative z-20 mx-auto mb-10">
                  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- 1. Kartu Agenda Menunggu --}}
                    <div class="modern-card relative bg-white p-6 rounded-md overflow-hidden transition-all duration-300 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-2xl">
                      {{-- Garis warna samping --}}
                      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-cyan-500"></div>
                      
                      <div class="ml-3">
                        <h3 class="text-slate-800 text-base font-semibold">Agenda Menunggu</h3>
                        <p class="mt-1 text-3xl font-bold text-cyan-600">{{ $pendingAgendasCount ?? 0 }}</p>
                        @auth('admin')
                        <p class="mt-2 text-sm text-gray-700 font-medium">Menunggu jadwal pelaksanaan</p>
                        @endauth
                      </div>
                    </div>

                    {{-- 2. Kartu Agenda Berlangsung --}}
                    <div class="modern-card relative bg-white p-6 rounded-md overflow-hidden transition-all duration-300 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-2xl">
                      {{-- Garis warna samping --}}
                      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-green-600"></div>

                      <div class="ml-3">
                        <h3 class="text-slate-800 text-base font-semibold">Agenda Berlangsung</h3>
                        <p class="mt-1 text-3xl font-bold text-green-700">{{ $ongoingAgendasCount ?? 0 }}</p>
                        @auth('admin')
                        <p class="mt-2 text-sm text-gray-700 font-medium">Agenda aktif saat ini</p>
                        @endauth
                      </div>
                    </div>

                    {{-- 3. Kartu Agenda Berakhir --}}
                    <div class="modern-card relative bg-white p-6 rounded-md overflow-hidden transition-all duration-300 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-2xl">
                      {{-- Garis warna samping --}}
                      <div class="absolute left-0 top-0 bottom-0 w-4 rounded-l-lg bg-red-700"></div>
                      
                      <div class="ml-3">
                        <h3 class="text-slate-800 text-base font-semibold">Agenda Berakhir</h3>
                        <p class="mt-1 text-3xl font-bold text-red-800">{{ $finishedAgendasCount ?? 0 }}</p>
                        @auth('admin')
                        <p class="mt-2 text-sm text-gray-700 font-medium">Riwayat agenda terlaksana</p>
                        @endauth
                      </div>
                    </div>
                  </div>
                </section>
            </div>

            <div class="mt-12">
                <x-dashboard :agendas="$agendas" class="shadow-md rounded-lg overflow-hidden transition-all duration-300" />
            </div>
            
        </div>

        {{-- Include Modal Form --}}
        @include('modal.tambah')


        
        {{-- Confirm Modals --}}
        <x-confirm 
            message="Apakah Anda yakin ingin menyimpan agenda ini?" 
            confirm-text="Simpan" 
            cancel-text="Batal" 
            x-show="showCreateConfirm" 
            @confirm="submitCreateForm(); showCreateConfirm = false" 
            @cancel="showCreateConfirm = false" 
        />
        
        <x-confirm 
            message="Apakah Anda yakin ingin menyimpan perubahan agenda ini?" 
            confirm-text="Simpan" 
            cancel-text="Batal" 
            x-show="showEditConfirm" 
            @confirm="submitEditForm(); showEditConfirm = false" 
            @cancel="showEditConfirm = false" 
        />
    </div>
        @push('scripts')
        @vite('resources/js/landing.js')
    @endpush
</x-layout>
