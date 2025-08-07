<x-layout>
    <x-slot:title>Detail Agenda</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Agenda</h2>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Agenda</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $agenda->nama_agenda }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tempat</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $agenda->tempat }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $agenda->tanggal->format('d-m-Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Bagian Tamu -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Tamu</h3>
                    <a href="{{ route('agenda.tamu.create', $agenda->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Tamu
                    </a>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    @if(count($agenda->tamu) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($agenda->tamu->take(5) as $tamu)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $tamu->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tamu->instansi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="{{ $tamu->kehadiran ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-xs font-semibold">
                                                    {{ $tamu->kehadiran ? 'Hadir' : 'Belum Hadir' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
                            <a href="{{ route('agenda.tamu.index', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Lihat Semua Tamu ({{ count($agenda->tamu) }})
                            </a>
                        </div>
                    @else
                        <div class="p-4 text-center text-gray-500">
                            <p>Belum ada tamu untuk agenda ini.</p>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
                            <a href="{{ route('agenda.tamu.index', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Kelola Tamu
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bagian Notulen -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Notulen Agenda</h3>
                    @if(!$agenda->notulen)
                    <a href="{{ route('agenda.notulen.create', $agenda->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Notulen
                    </a>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    @if($agenda->notulen)
                        <div class="px-4 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Notulen oleh: {{ $agenda->notulen->pembuat }}</span>
                            <a href="{{ route('agenda.notulen.show', ['agendaId' => $agenda->id, 'id' => $agenda->notulen->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Lihat Notulen
                            </a>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($agenda->notulen->isi_notulen, 200) }}</p>
                        </div>
                    @else
                        <div class="p-4 text-center text-gray-500">
                            <p>Belum ada notulen untuk agenda ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('agenda.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Daftar Agenda
                </a>

                <div class="flex space-x-3">
                    <button @click="$dispatch('open-modal', {name: 'edit-agenda-{{ $agenda->id }}', agenda: {{ json_encode($agenda) }}})" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Edit
                    </button>
                    <form action="{{ route('agenda.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
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

    <!-- Modal Edit Agenda -->
    <div
        x-data="{ 
            show: false, 
            formData: { 
                nama_agenda: '{{ $agenda->nama_agenda }}', 
                tempat: '{{ $agenda->tempat }}', 
                tanggal: '{{ $agenda->tanggal->format('Y-m-d') }}', 
                jam_mulai: '{{ $agenda->jam_mulai }}', 
                jam_selesai: '{{ $agenda->jam_selesai }}' 
            } 
        }"
        x-show="show"
        x-on:open-modal.window="if ($event.detail.name === 'edit-agenda-{{ $agenda->id }}') { show = true }"
        x-on:keydown.escape.window="show = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 transition-opacity" 
                aria-hidden="true"
            >
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Agenda</h3>
                            
                            <form action="{{ route('agenda.update', $agenda->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="nama_agenda_edit" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                                    <div class="mt-1">
                                        <input type="text" name="nama_agenda" id="nama_agenda_edit" value="{{ old('nama_agenda', $agenda->nama_agenda) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_agenda') border-red-500 @enderror">
                                    </div>
                                    @error('nama_agenda')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_edit" class="block text-sm font-medium text-gray-700">Tempat</label>
                                    <div class="mt-1">
                                        <input type="text" name="tempat" id="tempat_edit" value="{{ old('tempat', $agenda->tempat) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempat') border-red-500 @enderror">
                                    </div>
                                    @error('tempat')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_edit" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <div class="mt-1">
                                        <input type="date" name="tanggal" id="tanggal_edit" value="{{ old('tanggal', $agenda->tanggal->format('Y-m-d')) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal') border-red-500 @enderror">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="jam_mulai_edit" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_mulai" id="jam_mulai_edit" value="{{ old('jam_mulai', $agenda->jam_mulai) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_mulai') border-red-500 @enderror">
                                        </div>
                                        @error('jam_mulai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jam_selesai_edit" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_selesai" id="jam_selesai_edit" value="{{ old('jam_selesai', $agenda->jam_selesai) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_selesai') border-red-500 @enderror">
                                        </div>
                                        @error('jam_selesai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Perbarui
                                    </button>
                                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>