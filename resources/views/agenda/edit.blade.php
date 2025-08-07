<x-layout>
    <x-slot:title>Edit Agenda</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Agenda</h2>

            {{-- PERBAIKAN: Menggunakan agenda_id dan array asosiatif --}}
            <form action="{{ route('agenda.update', ['agenda' => $agenda->agenda_id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                    <input type="text" name="nama_agenda" id="nama_agenda" value="{{ old('nama_agenda', $agenda->nama_agenda) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                    <input type="text" name="tempat" id="tempat" value="{{ old('tempat', $agenda->tempat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $agenda->tanggal->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('agenda.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
