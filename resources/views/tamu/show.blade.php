<x-layout>
    <x-slot:title>Detail Tamu: {{ $tamu->nama }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Tamu: {{ $tamu->nama }}</h2>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Tamu</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tamu->nama }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Instansi</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $tamu->instansi }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jabatan</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $tamu->jabatan }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status Kehadiran</dt>
                        <dd class="mt-1">
                            <span class="{{ $tamu->kehadiran ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-sm font-semibold">
                                {{ $tamu->kehadiran ? 'Hadir' : 'Belum Hadir' }}
                            </span>
                        </dd>
                    </div>

                    @if($tamu->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $tamu->email }}</dd>
                    </div>
                    @endif

                    @if($tamu->telepon)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $tamu->telepon }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Agenda</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $agenda->nama_agenda }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('agenda.tamu.index', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Daftar Tamu
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('agenda.tamu.edit', ['agendaId' => $agenda->id, 'id' => $tamu->id]) }}" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Edit
                    </a>
                    <form action="{{ route('agenda.tamu.destroy', ['agendaId' => $agenda->id, 'id' => $tamu->id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?')">
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