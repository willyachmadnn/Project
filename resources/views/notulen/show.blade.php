<x-layout>
    <x-slot:title>Detail Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Notulen Agenda</h2>

            {{-- Informasi Agenda --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Agenda</h3>
                <p class="text-gray-700">{{ $agenda->nama_agenda }}</p>
                <p class="text-sm text-gray-500">{{ $agenda->tanggal->format('d F Y') }}, {{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</p>
            </div>

            {{-- Isi Notulen --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Isi Notulen</h3>
                <div class="prose max-w-none p-4 bg-gray-50 rounded-md border">
                    {!! nl2br(e($notulen->isi_notulen)) !!}
                </div>
                <p class="text-sm text-gray-500 mt-2">Dibuat oleh: {{ $notulen->pembuat }}</p>
            </div>

            <div class="flex justify-between items-center mt-8 pt-4 border-t">
                {{-- PERBAIKAN: Menggunakan array asosiatif dan agenda_id --}}
                <a href="{{ route('agenda.show', ['agenda' => $agenda->agenda_id]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                    &larr; Kembali ke Detail Agenda
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('agenda.notulen.edit', ['agendaId' => $agenda->agenda_id, 'id' => $notulen->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit
                    </a>
                    <form action="{{ route('agenda.notulen.destroy', ['agendaId' => $agenda->agenda_id, 'id' => $notulen->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notulen ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
