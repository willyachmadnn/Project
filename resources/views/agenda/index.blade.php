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
                const form = document.getElementById('editForm');
                if (form) {
                    const actionUrl = '{{ route('agenda.update', ':id') }}';
                    form.action = actionUrl.replace(':id', agenda.agenda_id);
                }
                this.isEditModalOpen = true;
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
                document.getElementById('editForm').submit();
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

        {{-- ===== Modal Tambah (teleport to body, trap focus, no-scroll) ===== --}}
        <template x-teleport="body">
            <div
                x-cloak
                x-show="isCreateModalOpen"
                x-trap.inert.noscroll="isCreateModalOpen"
                @keydown.escape.window="isCreateModalOpen = false"
                class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="fixed inset-0 backdrop-blur-sm" @click="isCreateModalOpen = false"></div>

                {{-- Container untuk Modal dengan dua bagian terpisah --}}
                <div
                    x-show="isCreateModalOpen"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    style="width: 900px; max-height: 88vh;"
                    class="relative z-[1000] inline-block align-bottom text-left transform transition-all space-y-4"
                    @click.stop
                >
                    {{-- Kontainer Form (Kotak Putih) --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-md px-4 pt-3 pb-3 overflow-y-auto shadow-2xl border border-black/30 mb-2 mt-2" style="max-height: 88vh;">
                        <div class="flex items-center justify-center mb-6 pb-2 pt-2 relative">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Form Tambah Agenda
                            </h3>
                            <button type="button" @click="isCreateModalOpen = false" class="absolute right-0 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="create-agenda-form" action="{{ route('agenda.store') }}" method="POST" class="space-y-3" x-init="setTimeout(() => { document.getElementById('create_tanggal').valueAsDate = new Date() }, 100)">
                            @csrf
                            <div>
                                <label for="create_nama_agenda" class="block text-sm font-semibold text-gray-800 mb-0.5">Nama Agenda</label>
                                <textarea name="nama_agenda" id="create_nama_agenda" rows="2" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resize-none" placeholder="Masukkan nama agenda" required>{{ old('nama_agenda') }}</textarea>
                            </div>
                            <div>
                                <label for="create_tempat" class="block text-sm font-semibold text-gray-800 mb-2">Tempat</label>
                                <input type="text" name="tempat" id="create_tempat" value="{{ old('tempat') }}" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200" placeholder="Masukkan lokasi agenda" required>
                            </div>
                            <div>
                                <label for="create_tanggal" class="block text-sm font-semibold text-gray-800 mb-2">Tanggal</label>
                                <input type="date" name="tanggal" id="create_tanggal" value="{{ old('tanggal') }}" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="time-picker-container">
                                    <label for="create_jam_mulai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="create_jam_mulai" value="{{ old('jam_mulai', '00:00') }}" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer" onclick="this.showPicker()" required>
                                </div>
                                <div class="time-picker-container">
                                    <label for="create_jam_selesai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" id="create_jam_selesai" value="{{ old('jam_selesai', '00:00') }}" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer" onclick="this.showPicker()" required>
                                </div>
                            </div>
                            <div>
                                <label for="create_dihadiri" class="block text-sm font-semibold text-gray-800 mb-2">Dihadiri</label>
                                <textarea name="dihadiri" id="create_dihadiri" rows="2" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resize-none" placeholder="Masukkan daftar yang hadir dalam agenda ini" required>{{ old('dihadiri') }}</textarea>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Kontainer Tombol Simpan (Di luar kotak putih) --}}
                    <div class="flex justify-center">
                        <button type="button" @click="confirmCreateAgenda()" class="px-3 py-1 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md text-base font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-red-500/20">
                            Simpan Agenda
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- ===== Modal Edit (teleport to body, trap focus, no-scroll) ===== --}}
        <template x-teleport="body">
            <div
                x-cloak
                x-show="isEditModalOpen"
                x-trap.inert.noscroll="isEditModalOpen"
                @keydown.escape.window="isEditModalOpen = false"
                class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="fixed inset-0 backdrop-blur-sm" @click="isEditModalOpen = false"></div>

                <div
                    x-show="isEditModalOpen"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    style="width: 800px; height: 600px;"
                    class="relative z-[1000] inline-block align-bottom bg-white/95 backdrop-blur-md rounded-md px-4 pt-4 pb-4 text-left overflow-hidden shadow-2xl border border-black/30 transform transition-all"
                    @click.stop
                >
                    <div class="flex items-center justify-center mb-6 relative">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Form Edit Agenda
                        </h3>
                        <button type="button" @click="isEditModalOpen = false" class="absolute right-0 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editForm" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_nama_agenda" class="block text-sm font-semibold text-gray-800 mb-2">Nama Agenda</label>
                            <input type="text" name="nama_agenda" id="edit_nama_agenda" x-model="editAgenda.nama_agenda" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200" placeholder="Masukkan nama agenda" required>
                        </div>
                        <div>
                            <label for="edit_tempat" class="block text-sm font-semibold text-gray-800 mb-2">Tempat</label>
                            <input type="text" name="tempat" id="edit_tempat" x-model="editAgenda.tempat" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200" placeholder="Masukkan lokasi agenda" required>
                        </div>
                        <div>
                            <label for="edit_tanggal" class="block text-sm font-semibold text-gray-800 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" x-model="editAgenda.tanggal" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200" required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="time-picker-container">
                                <label for="edit_jam_mulai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editAgenda.jam_mulai" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer" onclick="this.showPicker()" step="300" required>
                            </div>
                            <div class="time-picker-container">
                                <label for="edit_jam_selesai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editAgenda.jam_selesai" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer" onclick="this.showPicker()" step="300" required>
                            </div>
                        </div>
                        <div class="pt-6 flex justify-end space-x-3">
    <button type="button" @click="isEditModalOpen = false" class="px-6 py-3 border-2 border-gray-300 rounded-md text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500/20 transition-all duration-200">
        Batal
    </button>
    
    <button type="button" @click="confirmEditAgenda()" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md text-sm font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500/20 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan Perubahan
    </button>
</div>
                    </form>
                </div>
            </div>
        </template>
        
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
