<!doctype html>
<html lang="id-ID" class="h-full overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="404 - Halaman Tidak Ditemukan">
    <title>404 - Halaman Tidak Ditemukan | SIMAP</title>
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
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07);
        }
        
        .btn-primary {
            background: #800000;
            color: white;
            border: 2px solid #800000;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: #E0E0E0;
            color: #333;
            border: 2px solid #D0D0D0;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Error Section -->
    <section class="error-section">
        <div class="text-center max-w-2xl mx-auto">
            <div class="theme-text-primary mb-6">
                <i class="fas fa-exclamation-triangle text-6xl md:text-8xl"></i>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-bold theme-text-primary mb-2">404</h1>
            <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Halaman Tidak Ditemukan</h2>
            
            <p class="text-gray-600 mb-4 text-sm md:text-base">Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau dihapus.</p>
            
            <div class="card p-4 mb-4 text-left">
                <p class="font-semibold mb-2 theme-text-primary text-sm md:text-base">Silakan coba beberapa hal berikut:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm md:text-base">
                    <li>Periksa kembali alamat URL yang Anda masukkan</li>
                    <li>Gunakan menu navigasi untuk menemukan halaman yang diinginkan</li>
                    <li>Kembali ke halaman utama dan mulai dari awal</li>
                    <li>Hubungi administrator jika masalah berlanjut</li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 justify-center items-center">
                <a href="javascript:history.back()" 
                   class="btn-secondary px-4 py-2 flex items-center justify-center rounded-lg text-sm md:text-base">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            
            <div class="mt-4 text-sm text-gray-500">
                <p>Kode Error: 404 | {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </section>
</body>
</html>