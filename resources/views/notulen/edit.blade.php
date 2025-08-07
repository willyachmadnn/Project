<x-layout>
    <x-slot:title>Edit Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Notulen Agenda: {{ $agenda->nama_agenda }}</h2>

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

            <form action="{{ route('agenda.notulen.update', ['agendaId' => $agenda->id, 'id' => $notulen->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="pembuat" class="block text-sm font-medium text-gray-700 mb-1">Pembuat Notulen <span class="text-red-500">*</span></label>
                    <input type="text" name="pembuat" id="pembuat" value="{{ old('pembuat', $notulen->pembuat) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div class="mb-6">
                    <label for="isi_notulen" class="block text-sm font-medium text-gray-700 mb-1">Isi Notulen <span class="text-red-500">*</span></label>
                    <textarea name="isi_notulen" id="isi_notulen" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('isi_notulen', $notulen->isi_notulen) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Tuliskan hasil notulen agenda secara lengkap dan jelas.</p>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('agenda.notulen.show', ['agendaId' => $agenda->id, 'id' => $notulen->id]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>