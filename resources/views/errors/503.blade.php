<!doctype html>
<html lang="id-ID" class="h-full overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="503 - Service Unavailable">
    <title>503 - Service Unavailable | SIMAP</title>
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
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07);
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
        
        .maintenance-icon {
            color: #f59e0b;
        }
    </style>

    <!-- Error Section -->
    <section class="error-section">
        <div class="text-center max-w-2xl mx-auto">
            <div class="maintenance-icon mb-6">
                <i class="fas fa-tools text-6xl md:text-8xl"></i>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-bold theme-text-primary mb-2">503</h1>
            <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Layanan Tidak Tersedia</h2>
            
            <p class="text-gray-600 mb-3 text-sm">Sistem sedang dalam pemeliharaan untuk meningkatkan kualitas layanan. Mohon maaf atas ketidaknyamanannya.</p>
            
            <div class="card p-4 mb-3 text-left max-w-lg mx-auto">
                <p class="font-semibold mb-2 theme-text-primary text-sm">Informasi Pemeliharaan:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm">
                    <li>Sistem sedang dalam pemeliharaan terjadwal</li>
                    <li>Layanan akan kembali normal dalam waktu singkat</li>
                    <li>Data Anda tetap aman dan terlindungi</li>
                    <li>Silakan coba kembali dalam beberapa saat</li>
                    <li>Untuk informasi lebih lanjut, hubungi administrator</li>
                </ul>
            </div>
            
            <div class="card p-3 mb-3 bg-amber-50 border-amber-200 max-w-lg mx-auto">
                <div class="flex items-center mb-1">
                    <i class="fas fa-clock text-amber-600 mr-2 text-sm"></i>
                    <span class="font-semibold text-amber-800 text-sm">Estimasi Waktu Pemulihan</span>
                </div>
                <p class="text-amber-700 text-sm">Layanan diperkirakan akan kembali normal dalam 15-30 menit. Terima kasih atas kesabaran Anda.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 justify-center items-center"> 
                <button onclick="window.location.reload()" 
                        class="btn-secondary px-4 py-2 flex items-center justify-center rounded-lg text-sm">
                    <i class="fas fa-redo mr-1"></i> Periksa Status
                </button>
                
                @auth('admin')
                <a href="{{ route('agenda.index') }}" 
                   class="btn-secondary px-4 py-2 flex items-center justify-center rounded-lg text-sm">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
                @endauth
            </div>
            
            <div class="mt-2 text-sm text-gray-500">
                <p>Kode Status: 503 | {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </section>
</body>
</html>