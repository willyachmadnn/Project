<x-layout>
    <x-slot:title>Detail Agenda</x-slot:title>
    


    <style>
        [x-cloak] { display: none !important; }
        
        /* Optimized minimal styles */
        .popup-alert {
            transform: translateX(-50%);
            opacity: 1;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-border {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2px;
            border-radius: 12px;
        }
        
        .gradient-border-content {
            background: white;
            border-radius: 10px;
        }
        
        .btn-smooth {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }
        
        .btn-smooth::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-smooth:hover::before {
            left: 100%;
        }
    </style>
    
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header dengan animasi slide in -->
        <div class="animate-slide-in-left bg-transparent border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-transparent">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-red-700 to-orange-700 bg-clip-text text-transparent animate-float break-words">
                            Detail Agenda: {{ $agenda->nama_agenda }}
                        </h1>
                        <p class="text-gray-600 mt-1">Informasi lengkap agenda dan peserta</p>
                    </div>
                    <a href="{{ route('agenda.index') }}" 
                       class="btn-smooth group inline-flex items-center px-4 py-2 bg-gradient-to-l from-orange-500 to-red-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 hover:scale-105 ml-34">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Alert Messages --}}
        @if(session('success'))
            <div id="popup-alert" class="fixed top-[10%] left-1/2 z-50 popup-alert">
                <div class="flex items-center border-l-4 border-green-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-green-700">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="ml-4 text-green-500 hover:text-green-700 transition-colors duration-200" onclick="hideAlert()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div id="popup-alert-error" class="fixed top-[10%] left-1/2 z-50 popup-alert">
                <div class="flex items-center border-l-4 border-red-500 bg-white p-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-red-700">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="ml-4 text-red-500 hover:text-red-700 transition-colors duration-200" onclick="hideErrorAlert()">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
            isEditModalOpen: false,
            editAgenda: {},
            showEditConfirm: false,
            showDeleteConfirm: false,
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
            confirmEditAgenda() {
                this.showEditConfirm = true;
            },
            confirmDeleteAgenda() {
                this.showDeleteConfirm = true;
            },
            submitEditForm() {
                document.getElementById('edit-agenda-form').submit();
            },
            deleteAgenda() {
                // Create the form element dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('agenda.destroy', $agenda->agenda_id) }}';

                // Create hidden input for CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Create hidden input for DELETE method spoofing
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Append the form to the body (it will not be visible) and submit it
                document.body.appendChild(form);
                form.submit();
            },
        }">        
            {{-- Tab Navigation --}}
            <div x-data="{ activeTab: 'detail' }" class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'detail'" 
                                :class="activeTab === 'detail' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Detail Agenda
                        </button>
                        <button @click="activeTab = 'tamu'" 
                                :class="activeTab === 'tamu' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Daftar Tamu
                        </button>
                        <button @click="activeTab = 'notulen'" 
                                :class="activeTab === 'notulen' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Notulen
                        </button>
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="mt-6">
                    {{-- Detail Agenda Tab --}}
                    <div x-show="activeTab === 'detail'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="glass-effect rounded-2xl p-8 shadow-xl">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800">Detail Agenda</h3>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <!-- QR Button -->
                                    <button class="flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12v4m6-4h.01M12 8h.01M12 8h4.01M12 8H7.99M12 8V4m0 0H7.99M12 4h4.01"></path>
                                        </svg>
                                        QR
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button @click="openEditModal()" class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        EDIT
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button @click="confirmDeleteAgenda()" class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        HAPUS
                                    </button>
                                </div>
                            </div>
                            
                <div class="text-sm border border-rose-200 rounded-lg overflow-hidden">
                    <!-- Baris 1: Tempat -->
                    <div class="flex bg-rose-100">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tempat</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tempat }}</div>
                    </div>
                    <!-- Baris 2: Tanggal -->
                    <div class="flex bg-white border-t border-rose-200">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tanggal</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tanggal->format('d F Y') }}</div>
                    </div>
                    <!-- Baris 3: Waktu -->
                    <div class="flex bg-rose-100 border-t border-rose-200">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Waktu</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</div>
                    </div>
                    <!-- Baris 4: Nama Admin -->
                    <div class="flex bg-white border-t border-rose-200">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Nama Admin</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">
                            {{-- Ganti variabel ini dengan relasi yang benar, contoh: $agenda->user->name --}}
                            {{ $agenda->admin->nama_admin ?? 'Data belum diisi' }}
                        </div>
                    </div>
                    <!-- Baris 5: OPD Admin -->
                    <div class="flex bg-rose-100 border-t border-rose-200">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">OPD Admin</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">
                            {{-- Ganti variabel ini dengan relasi yang benar, contoh: $agenda->user->opd->nama_opd --}}
                            {{ $agenda->admin->opd_admin ?? 'Data belum diisi' }}
                        </div>
                    </div>
                    <!-- Baris 6: Dihadiri -->
                    <div class="flex bg-white border-t border-rose-200">
                        <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Dihadiri</div>
                        <div class="flex-1 px-4 py-3 text-gray-700 break-words">
                            {{ $agenda->dihadiri ?? 'Data belum diisi' }}
                        </div>
                    </div>
                </div>
                            
                            <!-- Status Badge -->
                            <div class="mt-6 flex justify-center">
                                @php
                                    $now = now();
                                    $startTime = \Carbon\Carbon::parse($agenda->tanggal->format('Y-m-d') . ' ' . $agenda->jam_mulai);
                                    $endTime = \Carbon\Carbon::parse($agenda->tanggal->format('Y-m-d') . ' ' . $agenda->jam_selesai);
                                @endphp
                                
                                @if($now < $startTime)
                                    <span class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Agenda Menunggu
                                    </span>
                                @elseif($now >= $startTime && $now <= $endTime)
                                    <span class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg animate-pulse">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                        </svg>
                                        Sedang Berlangsung
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Agenda Selesai
                                    </span>
                                @endif
                            </div>
                            
                        </div>
                    </div>

                    {{-- Daftar Tamu Tab --}}
                    <div x-show="activeTab === 'tamu'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                        {{-- Bagian Tamu --}}
                        <div class="mb-8">
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Daftar Tamu</h3>
                        </div>
                        <a href="{{ route('agenda.tamu.index', ['agenda' => $agenda->agenda_id]) }}" 
                           class="btn-smooth group inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 hover:scale-105">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Kelola Tamu
                        </a>
                    </div>
                    
                    @if($agenda->tamu->isNotEmpty())
                        <div class="grid gap-4">
                            @foreach($agenda->tamu->take(5) as $index => $tamu)
                                <div class="bg-white rounded-xl p-4 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">{{ substr($tamu->nama_tamu, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $tamu->nama_tamu }}</h4>
                                                <p class="text-gray-600 text-sm flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    {{ $tamu->instansi }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($tamu->jk == 'L')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Laki-laki
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Perempuan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($agenda->tamu->count() > 5)
                                <div class="text-center mt-4">
                                    <p class="text-gray-600 text-sm mb-2">Dan {{ $agenda->tamu->count() - 5 }} tamu lainnya</p>
                                    <a href="{{ route('agenda.tamu.index', ['agenda' => $agenda->agenda_id]) }}" 
                                       class="inline-flex items-center text-green-600 hover:text-green-800 font-medium text-sm">
                                        Lihat Semua Tamu
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Tamu</h4>
                            <p class="text-gray-600 mb-4">Belum ada tamu yang ditambahkan untuk agenda ini.</p>
                            <a href="{{ route('agenda.tamu.index', ['agenda' => $agenda->agenda_id]) }}" 
                               class="btn-smooth inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 hover:scale-105">
                                <svg class="w-4 h-4 mr-2 transition-transform duration-300 hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Tamu Pertama
                            </a>
                        </div>
                    @endif
                        </div>
                    </div>

                    {{-- Notulen Tab --}}
                    <div x-show="activeTab === 'notulen'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        {{-- Bagian Notulen --}}
                        <div>
                <div class="glass-effect rounded-2xl p-8 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Notulen Agenda</h3>
                        </div>
                        @if($agenda->notulen)
                            <a href="{{ route('agenda.notulen.edit', ['agenda' => $agenda->agenda_id, 'notulen' => $agenda->notulen]) }}" 
                               class="btn-smooth group inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 hover:scale-105">
                                <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Notulen
                            </a>
                        @endif
                    </div>
                    
                    @if($agenda->notulen)
                        <div class="bg-white rounded-xl p-6 border border-gray-100">
                            <div class="prose max-w-none">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 mb-4">
                                    <h4 class="text-lg font-semibold text-purple-800 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Isi Notulen
                                    </h4>
                                </div>
                                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ $agenda->notulen->isi_notulen }}
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium">Dibuat oleh:</span>
                                    <span class="ml-1 text-purple-600 font-semibold">{{ $agenda->notulen->pembuat }}</span>
                                </div>
                                <a href="{{ route('agenda.notulen.show', ['agenda' => $agenda->agenda_id, 'notulen' => $agenda->notulen]) }}" 
                                   class="btn-smooth inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium text-sm rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 hover:from-purple-600 hover:to-pink-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Notulen</h4>
                            <p class="text-gray-600 mb-4">Belum ada notulen yang dibuat untuk agenda ini.</p>
                            <a href="{{ route('agenda.notulen.create', ['agenda' => $agenda->agenda_id]) }}" 
                               class="btn-smooth inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 hover:scale-105">
                                <svg class="w-4 h-4 mr-2 transition-transform duration-300 hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Notulen Pertama
                            </a>
                        </div>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Modal Edit --}}
        <template x-teleport="body">
            <div
                x-cloak
                x-show="isEditModalOpen"
                x-trap.inert.noscroll="isEditModalOpen"
                @keydown.escape.window="isEditModalOpen = false"
                class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="fixed inset-0 backdrop-blur-sm" @click="isEditModalOpen = false"></div>

                <div
                    x-show="isEditModalOpen"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    style="width: 900px; max-height: 88vh;"
                    class="relative z-[1000] inline-block align-bottom text-left transform transition-all space-y-4"
                    @click.stop
                >
                    {{-- Kontainer Form (Kotak Putih) --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-md px-4 pt-3 pb-3 overflow-y-auto shadow-2xl border border-black/30 mb-2 mt-2" style="max-height: 80vh;">
                        <div class="flex items-center justify-between mb-6 pb-2 pt-2">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Form Edit Agenda
                            </h3>
                            <button type="button" @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="edit-agenda-form" action="{{ route('agenda.update', $agenda->agenda_id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="edit_nama_agenda" class="block text-sm font-semibold text-gray-800 mb-0.5">Nama Agenda</label>
                                <textarea name="nama_agenda" id="edit_nama_agenda" rows="2" x-model="editAgenda.nama_agenda" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resize-none" placeholder="Masukkan nama agenda" required></textarea>
                            </div>
                            <div>
                                <label for="edit_tempat" class="block text-sm font-semibold text-gray-800 mb-2">Tempat</label>
                                <input type="text" name="tempat" id="edit_tempat" x-model="editAgenda.tempat" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200" placeholder="Masukkan lokasi agenda" required>
                            </div>
                            <div>
                                <label for="edit_tanggal" class="block text-sm font-semibold text-gray-800 mb-2">Tanggal</label>
                                <input type="date" name="tanggal" id="edit_tanggal" x-model="editAgenda.tanggal" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_jam_mulai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="edit_jam_mulai" x-model="editAgenda.jam_mulai" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200" required>
                                </div>
                                <div>
                                    <label for="edit_jam_selesai" class="block text-sm font-semibold text-gray-800 mb-2">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" id="edit_jam_selesai" x-model="editAgenda.jam_selesai" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 focus:border-gray-300 focus:outline-none transition-all duration-200" required>
                                </div>
                            </div>
                            <div>
                                <label for="edit_dihadiri" class="block text-sm font-semibold text-gray-800 mb-2">Dihadiri</label>
                                <textarea name="dihadiri" id="edit_dihadiri" rows="5" x-model="editAgenda.dihadiri" class="block w-full rounded-md border-2 border-gray-200 bg-white/60 backdrop-blur-sm px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-gray-300 focus:outline-none transition-all duration-200 resize-none" placeholder="Masukkan daftar yang hadir dalam agenda ini" required></textarea>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Kontainer Tombol Simpan (Di luar kotak putih) --}}
                    <div class="flex justify-center">
                        <button type="button" @click="confirmEditAgenda()" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-base font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-red-500/20">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    
    {{-- Modal Konfirmasi Edit --}}
    <x-confirm 
        message="Apakah Anda yakin ingin menyimpan perubahan agenda ini?" 
        confirm-text="Yakin" 
        cancel-text="Tidak" 
        x-show="showEditConfirm" 
        @confirm="submitEditForm(); showEditConfirm = false" 
        @cancel="showEditConfirm = false" />
    
    {{-- Modal Konfirmasi Hapus --}}
    <x-confirm 
        message="Apakah Anda yakin ingin menghapus agenda ini? Tindakan ini tidak dapat dibatalkan." 
        confirm-text="Yakin" 
        cancel-text="Tidak" 
        x-show="showDeleteConfirm" 
        @confirm="deleteAgenda(); showDeleteConfirm = false" 
        @cancel="showDeleteConfirm = false" />
        </div>
    
    {{-- JavaScript untuk Alert --}}
    <script>
        function hideAlert() {
            const alert = document.getElementById('popup-alert');
            if (alert) {
                alert.style.animation = 'fade-out-up 0.3s ease-in forwards';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }
        }
        
        function hideErrorAlert() {
            const alert = document.getElementById('popup-alert-error');
            if (alert) {
                alert.style.animation = 'fade-out-up 0.3s ease-in forwards';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            hideAlert();
            hideErrorAlert();
        }, 5000);
    </script>
</x-layout>
