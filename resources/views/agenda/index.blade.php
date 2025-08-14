{{-- 
    CATATAN PENTING:
    Pastikan Anda telah menyertakan Alpine.js di dalam layout utama Anda (misal: app.blade.php).
    Jika belum, tambahkan script berikut sebelum tag penutup </body>:
    <script src="//unpkg.com/alpinejs" defer></script>
--}}

<x-layout>
    <x-slot:title>Daftar Agenda</x-slot:title>

    {{-- Container utama dengan state management dari Alpine.js --}}
    <div x-data="{
        isCreateModalOpen: false,
        isEditModalOpen: false,
        editAgenda: {},
        openEditModal(agenda) {
            this.editAgenda = {
                ...agenda,
                // Format tanggal dan waktu agar sesuai dengan input type="date" dan "time"
                tanggal: new Date(agenda.tanggal).toISOString().split('T')[0],
                jam_mulai: agenda.jam_mulai.substring(0, 5),
                jam_selesai: agenda.jam_selesai.substring(0, 5)
            };
            const form = document.getElementById('editForm');
            const actionUrl = '{{ route('agenda.update', ':id') }}';
            form.action = actionUrl.replace(':id', agenda.agenda_id);
            this.isEditModalOpen = true;
        }
    }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Agenda</h1>
                    <p class="text-sm text-gray-500">SI-AGENDA Pemerintah Kota Mojokerto</p>
                </div>
                {{-- Tombol untuk membuka modal tambah agenda --}}
                <button @click="isCreateModalOpen = true" class="inline-flex items-center px-4 py-2 bg-[#8A0303] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#6e0202] focus:bg-[#6e0202] active:bg-[#9d0303] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Tambah Agenda
                </button>
            </div>

            {{-- Kartu Status --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <h3 class="text-sm font-medium text-gray-500">Agenda Menunggu</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingAgendasCount ?? '0' }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h3 class="text-sm font-medium text-gray-500">Agenda Berlangsung</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $ongoingAgendasCount ?? '0' }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-gray-500">Agenda Berakhir</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $finishedAgendasCount ?? '0' }}</p>
                </div>
            </section>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tabel Agenda --}}
            <x-table :agendas="$agendas" />
        </div>

        <!-- Modal untuk Tambah Agenda -->
        <div x-show="isCreateModalOpen" @keydown.escape.window="isCreateModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isCreateModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Tambah Agenda Baru</h3>
                    <form action="{{ route('agenda.store') }}" method="POST" class="mt-4 space-y-4">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="create_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="create_jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="create_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="create_jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isCreateModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal untuk Edit Agenda -->
        <div x-show="isEditModalOpen" @keydown.escape.window="isEditModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isEditModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editAgenda.jam_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="edit_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editAgenda.jam_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isEditModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
