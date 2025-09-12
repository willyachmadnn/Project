<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Selesai - {{ $agenda->nama_agenda }}</title>
    <meta name="description" content="Agenda {{ $agenda->nama_agenda }} telah selesai. Registrasi kehadiran untuk agenda ini sudah ditutup.">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:title" content="Agenda Selesai - {{ $agenda->nama_agenda }}">
    <meta property="og:description" content="Agenda {{ $agenda->nama_agenda }} telah selesai. Registrasi kehadiran sudah ditutup.">
    <meta property="og:type" content="website">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-white">
<main class="min-h-screen flex items-center justify-center p-4 py-8" role="main">
    <div class="w-full max-w-lg">
        <!-- Agenda Information Card -->
        <section class="glass-effect rounded-2xl shadow-2xl p-4 sm:p-6 mb-8" aria-labelledby="agenda-info">
            <div class="text-center space-y-6">
                <!-- Warning Icon -->
                <header class="text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-full flex items-center justify-center mb-3 shadow-xl" aria-hidden="true">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">Agenda Telah Selesai</h1>
                    <p class="text-gray-500 text-sm sm:text-base mb-3">Registrasi untuk agenda ini sudah ditutup</p>
                </header>
                
                <!-- Divider -->
                 <div class="border-t border-gray-200 my-4"></div>
                 
                 <article>
                     <h2 id="agenda-info" class="text-lg sm:text-xl font-bold text-gray-800 mb-4 text-center break-words">{{ $agenda->nama_agenda }}</h2>
                    @if($agenda->deskripsi)
                    <p class="text-gray-600 mb-4 text-center italic break-words px-2 text-sm sm:text-base">{{ $agenda->deskripsi }}</p>
                @endif

                <dl class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-3 border-b border-gray-100 gap-2">
                        <dt class="text-sm font-semibold text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Tanggal
                        </dt>
                        <dd class="text-sm font-medium text-gray-800 break-words">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F Y') }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-3 border-b border-gray-100 gap-2">
                        <dt class="text-sm font-semibold text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Waktu
                        </dt>
                        <dd class="text-sm font-medium text-gray-800 break-words">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-3 border-b border-gray-100 gap-2">
                        <dt class="text-sm font-semibold text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lokasi
                        </dt>
                        <dd class="text-sm font-medium text-gray-800 break-words">{{ $agenda->tempat }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-3 gap-2">
                        <dt class="text-sm font-semibold text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status
                        </dt>
                        <dd class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full break-words">{{ $agenda->status }}</dd>
                    </div>
                </dl>
                </article>
            </div>
        </section>

        <!-- Close Button -->
        <div class="text-center mt-8" role="region" aria-live="polite">
            <button type="button" 
                    onclick="closePage()" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300 shadow-md"
                    aria-label="Tutup halaman agenda">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Tutup Halaman
            </button>
        </div>

        <!-- Footer -->
        <footer class="text-center text-sm text-gray-400 mt-8">
            <p class="italic">Hubungi penyelenggara untuk informasi lebih lanjut</p>
        </footer>
    </div>
</main>

<script>
    // Simple page interactions without animations
    document.addEventListener('DOMContentLoaded', function() {
        // Preload landing page for better performance
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = '{{ route("landing") }}';
        document.head.appendChild(link);
        
        // Handle keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePage();
            }
        });
    });

    function closePage() {
        // Direct redirect without animation
        window.location.href = '{{ route("landing") }}';
    }
</script>

</body>
</html>