<x-layout>
    <x-slot:title>Tambah Tamu Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Tamu Agenda: {{ $agenda->nama_agenda }}</h2>

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

            <form action="{{ route('agenda.tamu.store', $agenda->id) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Tamu <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <label for="instansi" class="block text-sm font-medium text-gray-700 mb-1">Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="kehadiran" class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                        <select name="kehadiran" id="kehadiran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0" {{ old('kehadiran') == '0' ? 'selected' : '' }}>Belum Hadir</option>
                            <option value="1" {{ old('kehadiran') == '1' ? 'selected' : '' }}>Hadir</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('agenda.tamu.index', $agenda->id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>