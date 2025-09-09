<!doctype html>
<html lang="id-ID" class="h-full overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="500 - Server Error">
    <title>500 - Server Error | SIMAP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen overflow-x-hidden bg-white text-slate-900 antialiased">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            background-color: #FDFBF6; /* Warna latar belakang krem hangat */
            color: #1F1F1F; /* Warna teks utama */
        }
        
        .error-section {
            height: 100vh;
            max-height: 100vh;
            padding: 0.25rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            box-sizing: border-box;
        }
        
        @media (max-width: 768px) {
            .error-section {
                padding: 0.25rem;
                height: 100vh;
                max-height: 100vh;
            }
        }
        
        /* Palet Warna dari Referensi */
        .theme-bg-primary { background-color: #800000; }
        .theme-bg-primary-hover { background-color: #6b0000; }
        .theme-text-primary { color: #800000; }
        .theme-border-primary { border-color: #800000; }
        
        .theme-bg-accent { background-color: #FFBF00; }
        .theme-text-accent { color: #FFBF00; }
        .theme-ring-accent:focus {
            outline: none;
            box-shadow: 0 0 0 2px #FDFBF6, 0 0 0 4px #FFBF00;
        }
        
        .card {
            background-color: white;
            border: 1px solid #D9D9D9;
            border-radius: 1rem;
            box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: #800000;
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.2);
        }
        
        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(71, 85, 105, 0.1);
        }
    </style>

    <!-- Error Section -->
    <section class="error-section">
        <div class="text-center max-w-2xl mx-auto">
            <div class="text-red-600 mb-6">
                <i class="fas fa-server text-6xl md:text-8xl"></i>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-bold theme-text-primary mb-2">500</h1>
            <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Kesalahan Server Internal</h2>
            
            <p class="text-gray-600 mb-4 text-sm">Terjadi kesalahan pada server. Tim teknis kami sedang menangani masalah ini. Silakan coba lagi dalam beberapa saat.</p>
            
            <div class="card text-left">
                <p class="font-semibold mb-2 theme-text-primary text-sm">Apa yang dapat Anda lakukan:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm">
                    <li>Tunggu beberapa saat dan coba lagi</li>
                    <li>Segarkan halaman (refresh) browser Anda</li>
                    <li>Periksa koneksi internet Anda</li>
                    <li>Hubungi administrator jika masalah berlanjut</li>
                    <li>Coba akses halaman lain terlebih dahulu</li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 justify-center items-center">
                <button onclick="window.location.reload()" 
                        class="btn-secondary px-4 py-2 flex items-center justify-center rounded-lg text-sm">
                    <i class="fas fa-redo mr-1"></i> Coba Lagi
                </button>
                
                @auth('admin')
                <a href="{{ route('agenda.index') }}" 
                   class="btn-secondary px-4 py-2 flex items-center justify-center rounded-lg text-sm">
                    <i class="fas fa-tachometer-alt mr-1"></i> Kembali ke Beranda
                </a>
                @endauth
            </div>
            
            <div class="mt-2 text-sm text-gray-500">
                <p>Kode Error: 500 | {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </section>
</body>
</html>