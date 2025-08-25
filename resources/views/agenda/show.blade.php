{{-- resources/views/agenda/show.blade.php --}}
<x-layout>
    <x-slot:title>Detail Agenda</x-slot:title>
    
    <x-slot:styles>
        {{-- jQuery tidak lagi diperlukan --}}
    </x-slot:styles>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @php
        $initialToasts = [];
        if (session('success')) {
            $initialToasts[] = [
                'id' => uniqid(),
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => session('success'),
                'show' => true,
            ];
        }
        if (session('error')) {
            $initialToasts[] = [
                'id' => uniqid(),
                'type' => 'error',
                'title' => 'Terjadi Kesalahan!',
                'message' => session('error'),
                'show' => true,
            ];
        }
        if ($errors->any()) {
            foreach ($errors->all() as $error) {
                $initialToasts[] = [
                    'id' => uniqid(),
                    'type' => 'error',
                    'title' => 'Kesalahan Validasi!',
                    'message' => $error,
                    'show' => true,
                ];
            }
        }
    @endphp
    
    <div x-data="{
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            editAgenda: {},
            activeTab: 'detail',
            toasts: {{ json_encode($initialToasts) }},
            showQrModal: false,

            init() {
                this.toasts.forEach(toast => {
                    setTimeout(() => {
                        this.removeToast(toast.id);
                    }, 5000);
                });
            },
            
            addToast(toast) {
                const newToast = { ...toast, id: Date.now(), show: true };
                this.toasts.push(newToast);
                setTimeout(() => {
                    this.removeToast(newToast.id);
                }, 5000);
            },
            removeToast(id) {
                let toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            },

            openEditModal() {
                this.editAgenda = {
                    agenda_id: {{ json_encode($agenda->agenda_id) }},
                    nama_agenda: {{ json_encode($agenda->nama_agenda) }},
                    tempat: {{ json_encode($agenda->tempat) }},
                    tanggal: {{ json_encode($agenda->tanggal->format('Y-m-d')) }},
                    jam_mulai: {{ json_encode(\Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i')) }},
                    jam_selesai: {{ json_encode(\Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i')) }},
                    dihadiri: {{ json_encode($agenda->dihadiri) }}
                };
                this.isEditModalOpen = true;
            },
            openDeleteModal() {
                this.isDeleteModalOpen = true;
            },
            submitEditForm() {
                document.getElementById('edit-agenda-form').submit();
            },
            submitDeleteForm() {
                document.getElementById('delete-agenda-form').submit();
            }
        }"
         @open-delete-modal.window="openDeleteModal()"
         class="h-screen flex flex-col overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        
        <!-- Header Halaman -->
        <div class="bg-transparent border-b border-gray-100 flex-shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-red-700 to-orange-700 bg-clip-text text-transparent break-words">
                            Detail Agenda: {{ $agenda->nama_agenda }}
                        </h1>
                        <p class="text-gray-600 mt-1">Informasi lengkap agenda dan peserta.</p>
                    </div>
                    <a href="{{ route('agenda.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-l from-red-700 to-orange-700 text-white font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-transform">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-2">        
                {{-- Navigasi Tab --}}
                <div class="mb-10">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" role="presentation">
                            <button @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Detail Agenda</button>
                            <button @click="activeTab = 'tamu'" :class="activeTab === 'tamu' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-green-600'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Daftar Tamu</button>
                            <button @click="activeTab = 'notulen'" :class="activeTab === 'notulen' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-purple-600'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">Notulen</button>
                        </nav>
                    </div>

                    {{-- Konten Tab --}}
                    <div class="mt-6 h-[calc(100vh-280px)] overflow-hidden">
                        {{-- PERUBAHAN: Menambahkan border pada wrapper tab --}}
                        <div x-show="activeTab === 'detail'" role="tabpanel" class="h-full bg-blue-50 rounded-lg border-2 border-dashed border-blue-200" x-transition>
                            <x-isiDetailAgenda :agenda="$agenda" />
                        </div>
                        {{-- PERUBAHAN: Menambahkan border pada wrapper tab --}}
                        <div x-show="activeTab === 'tamu'" role="tabpanel" class="h-full flex flex-col bg-green-50 rounded-lg border-2 border-dashed border-green-200" x-transition>
                            <x-isiDaftarTamu :agenda="$agenda" />
                        </div>
                        <div x-show="activeTab === 'notulen'" role="tabpanel" class="h-full flex flex-col" x-transition">
                            <div class="flex flex-col items-center justify-center h-full p-8 text-center bg-purple-50 rounded-lg border-2 border-dashed border-purple-200">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Manajemen Notulen</h3>
                                <p class="text-gray-600 mt-2 mb-6 max-w-md">Klik tombol di bawah untuk membuka halaman editor, di mana Anda dapat menulis atau mengedit notulen untuk agenda ini.</p>
                                @php
                                    $route = $agenda->notulen 
                                        ? route('agenda.notulen.edit', ['agenda' => $agenda->agenda_id, 'notulen' => $agenda->notulen->id]) 
                                        : route('agenda.notulen.create', ['agenda' => $agenda->agenda_id]);
                                @endphp
                                <a href="{{ $route }}" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-lg hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-lg flex items-center text-lg font-bold transform hover:scale-105 transition-all">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                                    @if($agenda->notulen) Edit Notulen @else Buat Notulen Baru @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Modal Edit dan Hapus (Tidak ada perubahan) --}}
        <template x-teleport="body">
            <div x-cloak x-show="isEditModalOpen" @keydown.escape.window="isEditModalOpen = false" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="isEditModalOpen = false"></div>
                <div x-show="isEditModalOpen" class="relative z-[1000] bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                    <div class="flex items-center justify-between p-5 border-b"><h3 class="text-xl font-semibold text-gray-900">Form Edit Agenda</h3><button type="button" @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-full">&times;</button></div>
                    <div class="p-6 space-y-4 overflow-y-auto"><form id="edit-agenda-form" action="{{ route('agenda.update', $agenda->agenda_id) }}" method="POST" class="space-y-4">@csrf @method('PUT')<div><label for="edit_nama_agenda" class="block text-sm font-medium text-gray-700">Nama Agenda</label><textarea name="nama_agenda" id="edit_nama_agenda" rows="2" x-model="editAgenda.nama_agenda" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea></div><div><label for="edit_tempat" class="block text-sm font-medium text-gray-700">Tempat</label><input type="text" name="tempat" id="edit_tempat" x-model="editAgenda.tempat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div><div><label for="edit_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label><input type="date" name="tanggal" id="edit_tanggal" x-model="editAgenda.tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div><div class="grid grid-cols-1 sm:grid-cols-2 gap-4"><div><label for="edit_jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label><input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editAgenda.jam_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div><div><label for="edit_jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label><input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editAgenda.jam_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div></div><div><label for="edit_dihadiri" class="block text-sm font-medium text-gray-700">Dihadiri</label><textarea name="dihadiri" id="edit_dihadiri" rows="4" x-model="editAgenda.dihadiri" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea></div></form></div>
                    <div class="flex items-center justify-end p-6 border-t bg-gray-50 rounded-b-lg"><button type="button" @click="isEditModalOpen = false" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">Batal</button><button type="button" @click="submitEditForm()" class="ml-3 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Perubahan</button></div>
                </div>
            </div>
        </template>
        <template x-teleport="body">
            <div x-cloak x-show="isDeleteModalOpen" @keydown.escape.window="isDeleteModalOpen = false" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="isDeleteModalOpen = false"></div>
                <div x-show="isDeleteModalOpen" class="relative z-[1000] bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="p-6 text-center">
                        <svg class="mx-auto mb-4 text-red-500 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-600">Apakah Anda yakin ingin menghapus agenda ini?</h3>
                        <form id="delete-agenda-form" action="{{ route('agenda.destroy', $agenda->agenda_id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button @click="submitDeleteForm()" type="button" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Ya, Hapus
                        </button>
                        <button @click="isDeleteModalOpen = false" type="button" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Container untuk Notifikasi Toast (Tidak ada perubahan) -->
        <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-[1000]">
            <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
                <template x-for="toast in toasts" :key="toast.id">
                    <div x-show="toast.show" 
                         x-transition:enter="transform ease-out duration-300 transition" 
                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" 
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" 
                         x-transition:leave="transition ease-in duration-300" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg x-show="toast.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <svg x-show="toast.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-gray-900" x-text="toast.title"></p>
                                    <p class="mt-1 text-sm text-gray-500" x-text="toast.message"></p>
                                </div>
                                <div class="ml-4 flex flex-shrink-0">
                                    <button @click="removeToast(toast.id)" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>
    
    <x-slot:scripts>
    </x-slot:scripts>
</x-layout>
