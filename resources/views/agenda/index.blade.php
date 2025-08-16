{{-- resources/views/agenda/index.blade.php --}}
<x-layout>
    <style>
        [x-cloak]{ display:none!important }
        /* Animasi untuk alert dan transisi halaman */
        [role="alert"] {
            transition: all 0.3s ease-in-out;
            transform-origin: top center;
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .opacity-0 {
            opacity: 0;
            transform: scale(0.97) translateY(-10px);
        }
        .opacity-100 {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        /* Transisi untuk card dan container */
        #kpi-cards, #tableWrap, .container {
            transition: all 0.4s ease-in-out;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/alert-handler.js') }}"></script>
    <x-slot:title>Daftar Agenda</x-slot:title>

    <div
        x-data="{
            isCreateModalOpen: false,
            isEditModalOpen: false,
            editAgenda: {},
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
            }
        }"
    >
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <!-- Alert Section - Posisikan di atas card dengan posisi fixed -->
            <div id="alert-section" class="w-full transition-all duration-300 ease-in-out transform">
                @if(session('success'))
                    <div class="flex items-center border-l-4 border-green-500 bg-white p-4 rounded-lg transition-all duration-300 ease-in-out transform" role="alert">
                        <div class="flex-shrink-0 mr-3">
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="flex items-start border-l-4 border-red-500 bg-white p-4 rounded-lg transition-all duration-300 ease-in-out transform" role="alert">
                        <div class="flex-shrink-0 mr-3 pt-0.5">
                            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-red-700">Terjadi kesalahan:</p>
                            <ul class="mt-2 list-disc list-inside text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center border-l-4 border-red-500 bg-white p-4 rounded-lg transition-all duration-300 ease-in-out transform" role="alert">
                        <div class="flex-shrink-0 mr-3">
                            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Agenda</h1>
                    <p class="text-sm text-gray-500">SI-AGENDA Pemerintah Kota Mojokerto</p>
                </div>

                <button type="button"
                    @click="isCreateModalOpen = true"
                    class="group inline-flex w-40 items-center rounded-md bg-red-700 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800 transition-all duration-300 ease-in-out">
                    <span class="mr-1 transition-transform duration-300 transform group-hover:rotate-90">+</span>
                    <span class="flex-grow text-center mx-2">Tambah Agenda</span>
                </button>
            </div>
            
            <div id="kpi-cards" class="mb-16">
                <section class="relative z-20 mx-auto mb-10">
                  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- 1. Kartu Agenda Menunggu --}}
                    <div class="relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-xl hover:-translate-y-1">
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
                    <div class="relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-xl hover:-translate-y-1">
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
                    <div class="relative bg-white p-6 rounded-lg overflow-hidden transition-all duration-200 [box-shadow:0_4px_12px_-5px_rgba(0,0,0,0.4)] hover:shadow-xl hover:-translate-y-1">
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

            <div class="mt-8">
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
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-lg" @click="isCreateModalOpen = false"></div>

                <div
                    x-show="isCreateModalOpen"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    class="relative z-[1000] inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    @click.stop
                >
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Tambah Agenda Baru</h3>

                    <form action="{{ route('agenda.store') }}" method="POST" class="mt-4 space-y-4" x-init="setTimeout(() => { document.getElementById('create_tanggal').valueAsDate = new Date() }, 100)">
                        @csrf
                        <div>
                            <label for="create_nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                            <input type="text" name="nama_agenda" id="create_nama_agenda" value="{{ old('nama_agenda') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="create_tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                            <input type="text" name="tempat" id="create_tempat" value="{{ old('tempat') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="create_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="create_tanggal" value="{{ old('tanggal') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="create_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="create_jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" step="300" required>
                        </div>
                        <div>
                            <label for="create_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="create_jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" step="300" required>
                        </div>
                        <div>
                            <label for="create_dihadiri" class="block text-sm font-medium text-gray-700">Dihadiri</label>
                            <textarea name="dihadiri" id="create_dihadiri" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan nama-nama yang menghadiri agenda">{{ old('dihadiri') }}</textarea>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isCreateModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan</button>
                        </div>
                    </form>
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
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-lg" @click="isEditModalOpen = false"></div>

                <div
                    x-show="isEditModalOpen"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    class="relative z-[1000] inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    @click.stop
                >
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-edit">Edit Agenda</h3>

                    <form id="editForm" method="POST" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                            <input type="text" name="nama_agenda" id="edit_nama_agenda" x-model="editAgenda.nama_agenda" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="edit_tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                            <input type="text" name="tempat" id="edit_tempat" x-model="editAgenda.tempat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="edit_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" x-model="editAgenda.tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="edit_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editAgenda.jam_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" step="300" required>
                        </div>
                        <div>
                            <label for="edit_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editAgenda.jam_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" step="300" required>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isEditModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
        @push('scripts')
        @vite('resources/js/landing.js')
    @endpush
</x-layout>
