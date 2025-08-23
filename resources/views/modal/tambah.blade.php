{{-- resources/views/modal/tambah.blade.php --}}
<template x-teleport="body">
    <div
        x-cloak
        x-show="isCreateModalOpen"
        x-trap.inert.noscroll="isCreateModalOpen"
        @keydown.escape.window="isCreateModalOpen = false"
        class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        x-transition.opacity
    >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="isCreateModalOpen = false"></div>

        <div
            x-show="isCreateModalOpen"
            x-transition.scale.origin.center
            class="relative z-[1000] inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            @click.stop
        >
            <h3 class="text-lg font-medium leading-6 text-gray-900">Tambah Agenda Baru</h3>

            <form action="{{ route('agenda.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                {{-- 1. Nama Agenda --}}
                <div>
                    <label for="create_nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="create_nama_agenda" value="{{ old('nama_agenda') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                {{-- 2. Tempat --}}
                <div>
                    <label for="create_tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                    <input type="text" name="tempat" id="create_tempat" value="{{ old('tempat') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                {{-- 3. Tanggal --}}
                <div>
                    <label for="create_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" id="create_tanggal" value="{{ old('tanggal') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 4. Waktu Mulai --}}
                    <div>
                        <label for="create_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
<input type="time" name="jam_mulai" id="create_jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>

                    {{-- 5. Waktu Selesai --}}
                    <div>
                        <label for="create_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
<input type="time" name="jam_selesai" id="create_jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>

                {{-- 6. OPD --}}
                <div>
                    <label for="create_opd" class="block text-sm font-medium text-gray-700">OPD</label>
                    <input type="text" name="opd" id="create_opd" value="{{ old('opd') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- 7. Dihadiri --}}
                <div>
                    <label for="create_dihadiri" class="block text-sm font-medium text-gray-700">Dihadiri</label>
                    <textarea name="dihadiri" id="create_dihadiri" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Bupati, Sekretaris Daerah, ...">{{ old('dihadiri') }}</textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" @click="isCreateModalOpen = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan</button>
                </div>
            </form>
            
            {{-- Tombol TAMBAH di bawah container form --}}
            <div class="mt-4 flex justify-center">
                <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    TAMBAH
                </button>
            </div>
        </div>
    </div>
</template>
