<x-layout>
    <x-slot:title>Edit Agenda</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Agenda</h2>

            <form action="{{ route('agenda.update', $agenda->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                    <div class="mt-1">
                        <input type="text" name="nama_agenda" id="nama_agenda" value="{{ old('nama_agenda', $agenda->nama_agenda) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_agenda') border-red-500 @enderror">
                    </div>
                    @error('nama_agenda')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                    <div class="mt-1">
                        <input type="text" name="tempat" id="tempat" value="{{ old('tempat', $agenda->tempat) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempat') border-red-500 @enderror">
                    </div>
                    @error('tempat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <div class="mt-1">
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $agenda->tanggal->format('Y-m-d')) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal') border-red-500 @enderror">
                    </div>
                    @error('tanggal')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <div class="mt-1">
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $agenda->jam_mulai) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_mulai') border-red-500 @enderror">
                        </div>
                        @error('jam_mulai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <div class="mt-1">
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', $agenda->jam_selesai) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_selesai') border-red-500 @enderror">
                        </div>
                        @error('jam_selesai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('agenda.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>