<x-layout>
    <x-slot:title>Daftar Agenda</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Agenda</h2>
                <button @click="$dispatch('open-modal', {name: 'create-agenda'})" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Agenda
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($agendas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Agenda</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($agendas as $agenda)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('agenda.show', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $agenda->nama_agenda }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tempat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tanggal->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button @click="$dispatch('open-modal', {name: 'edit-agenda-{{ $agenda->id }}', agenda: {{ json_encode($agenda) }}})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <form action="{{ route('agenda.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Belum ada agenda yang tersedia.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Agenda -->
    <div
        x-data="{ show: false, formData: { nama_agenda: '', tempat: '', tanggal: '', jam_mulai: '', jam_selesai: '' } }"
        x-show="show"
        x-on:open-modal.window="if ($event.detail.name === 'create-agenda') { show = true }"
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Agenda Baru</h3>
                            
                            <form action="{{ route('agenda.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                                    <div class="mt-1">
                                        <input type="text" name="nama_agenda" id="nama_agenda" value="{{ old('nama_agenda') }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_agenda') border-red-500 @enderror">
                                    </div>
                                    @error('nama_agenda')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat" class="block text-sm font-medium text-gray-700">Tempat</label>
                                    <div class="mt-1">
                                        <input type="text" name="tempat" id="tempat" value="{{ old('tempat') }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempat') border-red-500 @enderror">
                                    </div>
                                    @error('tempat')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <div class="mt-1">
                                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal') border-red-500 @enderror">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_mulai') border-red-500 @enderror">
                                        </div>
                                        @error('jam_mulai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_selesai') border-red-500 @enderror">
                                        </div>
                                        @error('jam_selesai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Simpan
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

    <!-- Modal Edit Agenda (Dinamis untuk setiap agenda) -->
    @foreach($agendas as $agenda)
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
                                    <label for="nama_agenda_{{ $agenda->id }}" class="block text-sm font-medium text-gray-700">Nama Agenda</label>
                                    <div class="mt-1">
                                        <input type="text" name="nama_agenda" id="nama_agenda_{{ $agenda->id }}" value="{{ old('nama_agenda', $agenda->nama_agenda) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_agenda') border-red-500 @enderror">
                                    </div>
                                    @error('nama_agenda')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_{{ $agenda->id }}" class="block text-sm font-medium text-gray-700">Tempat</label>
                                    <div class="mt-1">
                                        <input type="text" name="tempat" id="tempat_{{ $agenda->id }}" value="{{ old('tempat', $agenda->tempat) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tempat') border-red-500 @enderror">
                                    </div>
                                    @error('tempat')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_{{ $agenda->id }}" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <div class="mt-1">
                                        <input type="date" name="tanggal" id="tanggal_{{ $agenda->id }}" value="{{ old('tanggal', $agenda->tanggal->format('Y-m-d')) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tanggal') border-red-500 @enderror">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="jam_mulai_{{ $agenda->id }}" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_mulai" id="jam_mulai_{{ $agenda->id }}" value="{{ old('jam_mulai', $agenda->jam_mulai) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('jam_mulai') border-red-500 @enderror">
                                        </div>
                                        @error('jam_mulai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jam_selesai_{{ $agenda->id }}" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                        <div class="mt-1">
                                            <input type="time" name="jam_selesai" id="jam_selesai_{{ $agenda->id }}" value="{{ old('jam_selesai', $agenda->jam_selesai) }}"
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
    @endforeach
</x-layout>