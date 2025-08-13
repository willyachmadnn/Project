<x-layout>
    <x-slot:title>Detail Agenda</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Detail Agenda</h2>
                <a href="{{ route('agenda.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                    &larr; Kembali ke Daftar Agenda
                </a>
            </div>

            {{-- Informasi Utama Agenda --}}
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Nama Agenda</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $agenda->nama_agenda }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tempat</dt>
                        <dd class="mt-1 text-gray-900">{{ $agenda->tempat }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                        <dd class="mt-1 text-gray-900">{{ $agenda->tanggal->format('d F Y') }}, {{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Bagian Tamu --}}
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Tamu</h3>
                    {{-- 
                        =====================================================================
                        PERUBAHAN DI SINI:
                        Link diubah dari route('agenda.tamu.create') menjadi route('agenda.tamu.index').
                        Ini akan mengarahkan pengguna ke halaman daftar tamu, di mana mereka bisa
                        menggunakan modal untuk menambah tamu baru. Ini memperbaiki error dan
                        membuat alur kerja menjadi konsisten.
                        =====================================================================
                    --}}
                    <a href="{{ route('agenda.tamu.index', ['agenda' => $agenda->agenda_id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Kelola Tamu
                    </a>
                </div>
                @if($agenda->tamu->isNotEmpty())
                    <ul class="divide-y divide-gray-200 border rounded-md">
                        {{-- Menampilkan maksimal 5 tamu sebagai pratinjau --}}
                        @foreach($agenda->tamu->take(5) as $tamu)
                            <li class="px-4 py-3 flex justify-between items-center">
                                <span class="text-sm text-gray-700">{{ $tamu->nama_tamu }} ({{ $tamu->instansi }})</span>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                    {{ $tamu->jk }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-center text-gray-500 py-4 border rounded-md">Belum ada tamu yang ditambahkan.</p>
                @endif
            </div>

            {{-- Bagian Notulen --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Notulen Agenda</h3>
                    @if($agenda->notulen)
                         {{-- Menggunakan Route Model Binding, cukup teruskan objeknya --}}
                        <a href="{{ route('agenda.notulen.edit', ['agenda' => $agenda->agenda_id, 'notulen' => $agenda->notulen]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Edit Notulen
                        </a>
                    @endif
                </div>
                <div class="border rounded-md p-4">
                    @if($agenda->notulen)
                        <p class="text-sm text-gray-600 line-clamp-4">{{ $agenda->notulen->isi_notulen }}</p>
                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                            <span class="text-xs text-gray-500">Dibuat oleh: {{ $agenda->notulen->pembuat }}</span>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Belum ada notulen untuk agenda ini.</p>
                            <a href="{{ route('agenda.notulen.create', ['agenda' => $agenda->agenda_id]) }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                + Buat Notulen
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
