<x-layout>
    <x-slot:title>Daftar Agenda</x-slot:title>

    {{-- Asumsi file layout utama (x-layout) memiliki background bg-gray-50 atau #F7F7F7 --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Agenda</h1>
                <p class="text-sm text-gray-500">SI-AGENDA Pemerintah Kota Mojokerto</p>
            </div>
            <a href="{{ route('agenda.create') }}" class="inline-flex items-center px-4 py-2 bg-[#8A0303] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#6e0202] focus:bg-[#6e0202] active:bg-[#9d0303] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Tambah Agenda
            </a>
        </div>

        {{-- Kartu Status --}}
        {{-- CATATAN: Variabel count ini perlu di-pass dari AgendaController@index --}}
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

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        {{-- Tabel Agenda --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#8A0303]">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama Agenda</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tempat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($agendas as $index => $agenda)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agendas->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('agenda.show', ['agenda' => $agenda->agenda_id]) }}" class="text-gray-800 hover:text-[#8A0303] hover:underline">
                                        {{ $agenda->nama_agenda }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tempat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tanggal->format('d F Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                     <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-yellow-100 text-yellow-800' => $agenda->status === 'Menunggu',
                                        'bg-green-100 text-green-800' => $agenda->status === 'Berlangsung',
                                        'bg-red-100 text-red-800' => $agenda->status === 'Selesai',
                                    ])>
                                        {{ $agenda->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('agenda.edit', ['agenda' => $agenda->agenda_id]) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                        <form action="{{ route('agenda.destroy', ['agenda' => $agenda->agenda_id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada agenda yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if ($agendas->hasPages())
                <div class="p-4 border-t border-gray-200">
                   {{ $agendas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
