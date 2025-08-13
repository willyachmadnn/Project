{{-- 
    CATATAN PENTING:
    Pastikan Anda telah menyertakan Alpine.js di dalam layout utama Anda (misal: app.blade.php).
    Jika belum, tambahkan script berikut sebelum tag penutup </body>:
    <script src="//unpkg.com/alpinejs" defer></script>
--}}

<x-layout>
    <x-slot:title>Daftar Tamu Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    {{-- 
        =====================================================================
        CONTAINER UTAMA DENGAN STATE MANAGEMENT ALPINE.JS
        =====================================================================
        'x-data' menginisialisasi state untuk komponen ini.
        - isCreateModalOpen: boolean, mengontrol visibilitas modal "Tambah Tamu".
        - isEditModalOpen: boolean, mengontrol visibilitas modal "Edit Tamu".
        - editTamu: object, untuk menampung data tamu yang sedang diedit.
        - deleteNip: string|null, menyimpan NIP tamu yang akan dihapus untuk konfirmasi.
        - openEditModal(tamu): fungsi untuk membuka modal edit. Ia menerima objek 'tamu',
          mengisinya ke state 'editTamu', memperbarui URL action form edit secara dinamis,
          dan akhirnya menampilkan modal edit.
    --}}
    <div x-data="{ 
        isCreateModalOpen: false, 
        isEditModalOpen: false, 
        editTamu: {},
        deleteNip: null,
        openEditModal(tamu) {
            this.editTamu = tamu;
            const form = document.getElementById('editForm');
            const actionUrl = '{{ route('agenda.tamu.update', ['agendaId' => $agenda->agenda_id, 'tamu' => ':nip']) }}';
            form.action = actionUrl.replace(':nip', tamu.NIP);
            this.isEditModalOpen = true;
        }
    }">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                {{-- HEADER HALAMAN --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Tamu Agenda: {{ $agenda->nama_agenda }}</h2>
                    {{-- Tombol ini, saat diklik (@click), akan mengubah state 'isCreateModalOpen' menjadi true untuk menampilkan modal tambah tamu. --}}
                    <button @click="isCreateModalOpen = true" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Tambah Tamu
                    </button>
                </div>

                {{-- NOTIFIKASI SUKSES & ERROR --}}
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
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

                {{-- =====================================================================
                    TABEL DATA TAMU
                ===================================================================== --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tamu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Loop melalui data tamu. Jika tidak ada data, bagian @empty akan dijalankan. --}}
                            @forelse ($tamu as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $item->NIP }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_tamu }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->instansi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="flex space-x-2 justify-center">
                                            {{-- Tombol Edit: Saat diklik, memanggil fungsi openEditModal dengan data tamu saat ini ($item) sebagai argumen. --}}
                                            <button @click="openEditModal({{ json_encode($item) }})" class="text-yellow-600 hover:text-yellow-900">Edit</button>
                                            
                                            {{-- Form Hapus: Mengirim request DELETE ke server. Konfirmasi JS sederhana digunakan untuk mencegah penghapusan yang tidak disengaja. --}}
                                            <form action="{{ route('agenda.tamu.destroy', ['agendaId' => $agenda->agenda_id, 'tamu' => $item->NIP]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Tampilan jika tidak ada data tamu. --}}
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada tamu untuk agenda ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tombol navigasi kembali ke detail agenda --}}
                <div class="mt-6">
                    <a href="{{ route('agenda.show', $agenda->agenda_id) }}" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Kembali ke Detail Agenda
                    </a>
                </div>
            </div>
        </div>

        {{-- =====================================================================
            MODAL UNTUK TAMBAH TAMU
        ===================================================================== --}}
        {{-- 
            Modal ini hanya akan terlihat (x-show) jika 'isCreateModalOpen' bernilai true.
            Menekan tombol Escape (@keydown.escape.window) akan menutup modal.
        --}}
        <div x-show="isCreateModalOpen" @keydown.escape.window="isCreateModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop/Overlay abu-abu. Saat diklik (@click), akan menutup modal. --}}
                <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isCreateModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                {{-- Konten Modal dengan animasi transisi. --}}
                <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Tambah Tamu Baru</h3>
                    {{-- Form untuk mengirim data tamu baru ke method 'store'. --}}
                    <form action="{{ route('agenda.tamu.store', $agenda->agenda_id) }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="create_NIP" class="block text-sm font-medium text-gray-700">NIP <span class="text-red-500">*</span></label>
                            <input type="text" name="NIP" id="create_NIP" value="{{ old('NIP') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required maxlength="8">
                        </div>
                        <div>
                            <label for="create_nama_tamu" class="block text-sm font-medium text-gray-700">Nama Tamu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_tamu" id="create_nama_tamu" value="{{ old('nama_tamu') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="create_instansi" class="block text-sm font-medium text-gray-700">Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="instansi" id="create_instansi" value="{{ old('instansi') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="jk_laki" name="jk" type="radio" value="Laki-laki" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" required>
                                    <label for="jk_laki" class="ml-3 block text-sm font-medium text-gray-700">Laki-laki</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="jk_perempuan" name="jk" type="radio" value="Perempuan" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="jk_perempuan" class="ml-3 block text-sm font-medium text-gray-700">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isCreateModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- =====================================================================
            MODAL UNTUK EDIT TAMU
        ===================================================================== --}}
        {{-- Modal ini hanya akan terlihat (x-show) jika 'isEditModalOpen' bernilai true. --}}
        <div x-show="isEditModalOpen" @keydown.escape.window="isEditModalOpen = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isEditModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-edit">Edit Tamu</h3>
                    {{-- Form action diatur secara dinamis oleh fungsi 'openEditModal' di Alpine.js. --}}
                    <form id="editForm" method="POST" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_NIP" class="block text-sm font-medium text-gray-700">NIP</label>
                            {{-- 'x-model' mengikat nilai input ini ke properti 'NIP' dari objek 'editTamu'. NIP dibuat 'readonly' karena merupakan primary key. --}}
                            <input type="text" name="NIP" id="edit_NIP" x-model="editTamu.NIP" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="edit_nama_tamu" class="block text-sm font-medium text-gray-700">Nama Tamu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_tamu" id="edit_nama_tamu" x-model="editTamu.nama_tamu" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit_instansi" class="block text-sm font-medium text-gray-700">Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="instansi" id="edit_instansi" x-model="editTamu.instansi" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    {{-- 'x-bind:checked' akan memilih radio button ini jika properti 'jk' dari 'editTamu' cocok dengan value. --}}
                                    <input id="edit_jk_laki" name="jk" type="radio" value="Laki-laki" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" x-bind:checked="editTamu.jk === 'Laki-laki'">
                                    <label for="edit_jk_laki" class="ml-3 block text-sm font-medium text-gray-700">Laki-laki</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="edit_jk_perempuan" name="jk" type="radio" value="Perempuan" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" x-bind:checked="editTamu.jk === 'Perempuan'">
                                    <label for="edit_jk_perempuan" class="ml-3 block text-sm font-medium text-gray-700">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end space-x-2">
                            <button type="button" @click="isEditModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
