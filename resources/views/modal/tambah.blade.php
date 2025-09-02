{{-- resources/views/modal/tambah.blade.php --}}
<style>
    .resizable-textarea {
        resize: vertical;
        min-height: 60px;
        max-height: 200px;
    }
    
    .modal-backdrop {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
    }
    
    .modal-container {
        transform-origin: center;
    }
    
    /* Modal-specific styles */
</style>

{{-- Modal Dinamis untuk Tambah/Edit Agenda --}}
<template x-teleport="body">
    <div
        x-cloak
        x-show="isCreateModalOpen || isEditModalOpen"
        x-trap.inert.noscroll="isCreateModalOpen || isEditModalOpen"
        @keydown.escape.window="closeModal()"
        class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="fixed inset-0 modal-backdrop" 
            @click="closeModal()"
        ></div>

        {{-- Container untuk Modal dengan dua bagian terpisah --}}
        <div
            style="width: 900px; max-height: 88vh;"
            class="relative z-[1000] inline-block align-bottom text-left transform modal-container space-y-4"
            @click.stop
            x-transition:enter="transition ease-out duration-300 delay-50"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-250"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        >
            {{-- Kontainer Form (Kotak Putih) --}}
            <div 
                class="bg-white/95 backdrop-blur-md rounded-lg px-4 pt-3 pb-3 overflow-y-auto shadow-2xl border border-black/30 mb-2 mt-2" 
                style="max-height: 88vh;"
            >
                <div class="flex items-center justify-center mb-6 pb-2 pt-2 relative">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center transition-all duration-200">
                        <svg class="w-6 h-6 mr-3 text-red-700 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span x-text="isEditModalOpen ? 'Form Edit Agenda' : 'Form Tambah Agenda'"></span>
                    </h3>
                    <button 
                        type="button" 
                        @click="closeModal()" 
                        class="absolute right-0 text-gray-400 hover:text-gray-600 hover:scale-110 transition-all duration-200 ease-out rounded-lg p-1 hover:bg-gray-100"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form Dinamis --}}
                <form 
                    :id="isEditModalOpen ? 'edit-agenda-form' : 'create-agenda-form'" 
                    :action="isEditModalOpen ? editFormAction : '{{ route('agenda.store') }}'"
                    method="POST" 
                    class="space-y-3" 
                    x-init="if (!isEditModalOpen) setTimeout(() => { document.getElementById('agenda_tanggal').valueAsDate = new Date() }, 100)"
                >
                    @csrf
                    <template x-if="isEditModalOpen">
                        @method('PUT')
                    </template>
                    
                    <div>
                        <label for="agenda_nama_agenda" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Nama Agenda</label>
                        <textarea 
                            name="nama_agenda" 
                            id="agenda_nama_agenda" 
                            rows="3" 
                            class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resizable-textarea hover:border-gray-300" 
                            placeholder="Masukkan nama agenda" 
                            required
                            x-model="isEditModalOpen ? editAgenda.nama_agenda : ''"
                        >{{ old('nama_agenda') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="agenda_tempat" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Tempat</label>
                        <input 
                            type="text" 
                            name="tempat" 
                            id="agenda_tempat" 
                            class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 hover:border-gray-300" 
                            placeholder="Masukkan lokasi agenda" 
                            required
                            x-model="isEditModalOpen ? editAgenda.tempat : ''"
                            :value="!isEditModalOpen ? '{{ old('tempat') }}' : ''"
                        >
                    </div>
                    
                    <div>
                        <label for="agenda_tanggal" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Tanggal</label>
                        <input 
                            type="date" 
                            name="tanggal" 
                            id="agenda_tanggal" 
                            class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 hover:border-gray-300" 
                            required
                            x-model="isEditModalOpen ? editAgenda.tanggal : ''"
                            :value="!isEditModalOpen ? '{{ old('tanggal') }}' : ''"
                        >
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="time-picker-container">
                            <label for="agenda_jam_mulai" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Waktu Mulai</label>
                            <input 
                                type="time" 
                                name="jam_mulai" 
                                id="agenda_jam_mulai" 
                                class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer hover:border-gray-300" 
                                step="60"

                                required
                                x-model="isEditModalOpen ? editAgenda.jam_mulai : ''"
                                :value="!isEditModalOpen ? '{{ old('jam_mulai', '00:00') }}' : ''"
                            >
                        </div>
                        <div class="time-picker-container">
                            <label for="agenda_jam_selesai" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Waktu Selesai</label>
                            <input 
                                type="time" 
                                name="jam_selesai" 
                                id="agenda_jam_selesai" 
                                class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200 cursor-pointer hover:border-gray-300" 
                                step="60"

                                required
                                x-model="isEditModalOpen ? editAgenda.jam_selesai : ''"
                                :value="!isEditModalOpen ? '{{ old('jam_selesai', '00:00') }}' : ''"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label for="agenda_dihadiri" class="block text-sm font-semibold text-gray-800 mb-2 transition-all duration-200">Dihadiri</label>
                        <textarea 
                            name="dihadiri" 
                            id="agenda_dihadiri" 
                            rows="3" 
                            class="block w-full rounded-lg border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resizable-textarea hover:border-gray-300" 
                            placeholder="Masukkan daftar yang hadir dalam agenda ini" 
                            required
                            x-model="isEditModalOpen ? editAgenda.dihadiri : ''"
                        >{{ old('dihadiri') }}</textarea>
                    </div>
                </form>
            </div>
            
            {{-- Kontainer Tombol Simpan (Di luar kotak putih) --}}
            <div class="flex justify-center">
                <button 
                    type="button" 
                    @click="isEditModalOpen ? confirmEditAgenda() : confirmCreateAgenda()" 
                    class="px-3 py-1 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-base font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg hover:shadow-xl transition-all duration-200 ease-out transform hover:scale-105 active:scale-95 border-2 border-red-500/20"
                >
                    <span x-text="isEditModalOpen ? 'Simpan Perubahan' : 'Simpan Agenda'" class="transition-all duration-150"></span>
                </button>
            </div>
        </div>
    </div>
</template>
