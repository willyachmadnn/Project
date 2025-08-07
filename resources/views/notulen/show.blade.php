<x-layout>
    <x-slot:title>Detail Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Notulen Agenda: {{ $agenda->nama_agenda }}</h2>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Agenda</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Agenda</dt>
                            <dd class="text-sm text-gray-900">{{ $agenda->nama_agenda }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tempat</dt>
                            <dd class="text-sm text-gray-900">{{ $agenda->tempat }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                            <dd class="text-sm text-gray-900">{{ $agenda->tanggal->format('d-m-Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                            <dd class="text-sm text-gray-900">{{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pembuat Notulen</h3>
                    <p class="text-gray-900">{{ $notulen->pembuat }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Isi Notulen</h3>
                    <div class="prose max-w-none bg-white p-4 rounded border border-gray-200">
                        <p class="whitespace-pre-line">{{ $notulen->isi_notulen }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('agenda.show', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Detail Agenda
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('agenda.notulen.edit', ['agendaId' => $agenda->id, 'id' => $notulen->id]) }}" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Edit
                    </a>
                    <form action="{{ route('agenda.notulen.destroy', ['agendaId' => $agenda->id, 'id' => $notulen->id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notulen ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>