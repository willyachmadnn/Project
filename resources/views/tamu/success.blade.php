<!DOCTYPE html>
<html lang="id-ID" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Registrasi Berhasil - Sistem Agenda Pemerintah Kabupaten Mojokerto">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Berhasil</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Prevent scrolling and ensure full viewport usage */
        html, body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Responsive design for different screen sizes */
        @media (max-height: 600px) {
            .success-container {
                padding: 1rem;
            }
            .success-card {
                padding: 1.5rem;
            }
            .success-icon {
                width: 3rem;
                height: 3rem;
                margin-bottom: 1rem;
            }
            .success-title {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-height: 500px) {
            .success-container {
                padding: 0.5rem;
            }
            .success-card {
                padding: 1rem;
            }
            .success-icon {
                width: 2.5rem;
                height: 2.5rem;
                margin-bottom: 0.5rem;
            }
            .success-title {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }
            .agenda-info {
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body class="h-full bg-gradient-to-br from-green-50 to-emerald-100">
    <div class="h-full flex items-center justify-center success-container px-4">
        <div class="w-full max-w-md">
            <!-- Success Icon -->
            <div class="text-center mb-6">
                <div class="mx-auto success-icon h-16 w-16 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="success-title text-2xl font-bold text-gray-900 mb-2">Registrasi Berhasil!</h1>
                <p class="text-gray-600 text-sm">Kehadiran Anda telah tercatat</p>
            </div>

            <!-- Success Card -->
            <div class="bg-white rounded-lg shadow-xl success-card p-6">
                <div class="text-center space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <h3 class="text-base font-semibold text-green-800 mb-2">{{ $tamu->agenda->nama_agenda }}</h3>
                        <div class="agenda-info mt-2 text-xs text-green-600 space-y-1">
                            <p><strong>Nama:</strong> {{ $tamu->nama_tamu }}</p>
                            <p><strong>NIP:</strong> {{ $tamu->NIP }}</p>
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tamu->agenda->tanggal)->format('d F Y') }}</p>
                            <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($tamu->agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($tamu->agenda->jam_selesai)->format('H:i') }}</p>
                            <p><strong>Tempat:</strong> {{ $tamu->agenda->tempat }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-gray-700 text-sm">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            Data tersimpan
                        </p>
                        <p class="text-gray-700 text-sm">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>
                            Hadir sesuai jadwal
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 space-y-2">
                        <button 
                            onclick="closePage()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] text-sm"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Tutup Halaman
                        </button>
                        
                        <button 
                            onclick="registerAgain()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] text-sm"
                        >
                            <i class="fas fa-redo mr-2"></i>
                            Daftar Lagi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="text-gray-500 text-xs">
                    Terima kasih atas partisipasi Anda
                </p>
            </div>
        </div>
    </div>

    <script>
    /**
     * Fungsi untuk menutup halaman dengan fallback yang aman
     * Mengikuti best practices untuk web accessibility dan user experience
     */
    function closePage() {
        // Arahkan ke landing page umum/tamu (tanpa autentikasi admin)
        window.location.href = '/';
    }

    /**
     * Fungsi untuk mendaftar lagi ke agenda yang sama
     * Mengarahkan user kembali ke form registrasi dengan agenda ID yang sama
     */
    function registerAgain() {
        // Deteksi jenis registrasi berdasarkan NIP
        const nip = '{{ $tamu->NIP }}';
        const agendaId = '{{ $tamu->agenda_id }}';
        
        // Jika NIP dimulai dengan 'NP', maka non-pegawai
        if (nip.startsWith('NP')) {
            window.location.href = `/tamu/non-pegawai?agenda_id=${agendaId}&type=non-pegawai`;
        } else {
            // Jika tidak, maka pegawai
            window.location.href = `/tamu/pegawai?agenda_id=${agendaId}&type=pegawai`;
        }
    }

    // Keyboard shortcuts untuk accessibility
    document.addEventListener('keydown', function(event) {
        // ESC key untuk tutup halaman
        if (event.key === 'Escape') {
            closePage();
        }
        
        // Ctrl/Cmd + R untuk daftar lagi
        if ((event.ctrlKey || event.metaKey) && event.key === 'r') {
            event.preventDefault();
            registerAgain();
        }
    });
    </script>
</body>
</html>