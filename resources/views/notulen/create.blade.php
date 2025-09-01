<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Notulen: {{ $agenda->nama_agenda }}</title>

    {{-- Aset yang Diperlukan agar Editor Berfungsi --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <!-- Pustaka untuk ekspor PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen overflow-x-hidden">
    <style>
        /* CSS untuk mengoptimalkan layout dan mencegah scrollbar tidak perlu */
        html, body {
            height: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        
        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .content-wrapper {
            flex: 1;
            max-width: 100%;
        }
        
        /* Optimasi layout untuk halaman create - menghindari duplikasi dengan isiNotulen.blade.php */
        .note-editor {
            max-width: 100% !important;
        }
        
        /* Responsive height adjustment untuk create page */
        .note-editing-area {
            max-height: 60vh !important;
            overflow-y: auto !important;
        }
        
        @media (max-height: 600px) {
            .note-editing-area {
                max-height: 40vh !important;
            }
        }
        
        @media (min-height: 900px) {
            .note-editing-area {
                max-height: 50vh !important;
            }
        }
    </style>

    <div class="main-container">
        <div class="content-wrapper container mx-auto p-4 sm:p-8">
        {{-- Tombol Kembali ke Halaman Detail Agenda --}}
        <div class="mb-2 ml-6">
            <a href="{{ route('agenda.show', $agenda->agenda_id) }}#notulen" onclick="sessionStorage.setItem('activeTab', 'notulen');"
                class="group inline-flex items-center px-5 py-2.5 bg-[#ac1616] hover:bg-red-700 text-white hover:text-amber-50 font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#ac1616] focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Agenda
            </a>
        </div>

        {{-- Memanggil komponen isiNotulen yang berisi editor dan fungsionalitasnya --}}
        <div class="bg-transparent p-6 rounded-lg">
            <x-isiNotulen :agenda="$agenda" />
        </div>
        </div>
    </div>

    {{-- Skrip untuk inisialisasi Summernote pada halaman ini --}}
    <script>
        $(document).ready(function() {
            const editor = $('#summernote-editor');
            if (editor.length > 0 && !editor.hasClass('note-editor')) {
                editor.summernote({
                    placeholder: 'Tulis isi notulen di sini...',
                    tabsize: 2,
                    height: Math.min(400, Math.max(300, window.innerHeight * 0.4)),
                    focus: true,
                    lang: 'id-ID',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'hr']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote berhasil diinisialisasi di halaman create.');
                        }
                    }
                });
                
                // Simplified height management - menghindari konflik dengan CSS resize
                function setInitialHeight() {
                    const initialHeight = Math.min(400, Math.max(300, window.innerHeight * 0.4));
                    if (editor.hasClass('note-editor')) {
                        editor.summernote('option', 'height', initialHeight);
                    }
                }
                
                // Set initial height only once
                setTimeout(setInitialHeight, 100);
                
                // Optimasi scrollbar logic
                function optimizeScrollBehavior() {
                    const body = document.body;
                    const html = document.documentElement;
                    const windowHeight = window.innerHeight;
                    const documentHeight = Math.max(
                        body.scrollHeight, body.offsetHeight,
                        html.clientHeight, html.scrollHeight, html.offsetHeight
                    );
                    
                    // Hanya tampilkan scrollbar vertikal jika konten melebihi viewport
                    if (documentHeight <= windowHeight) {
                        body.style.overflowY = 'hidden';
                    } else {
                        body.style.overflowY = 'auto';
                    }
                }
                
                // Jalankan optimasi saat load dan resize
                optimizeScrollBehavior();
                $(window).on('resize', function() {
                    clearTimeout(window.scrollTimer);
                    window.scrollTimer = setTimeout(optimizeScrollBehavior, 100);
                });
                
                // Monitor perubahan konten
                const observer = new MutationObserver(function() {
                    clearTimeout(window.contentTimer);
                    window.contentTimer = setTimeout(optimizeScrollBehavior, 200);
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }
        });
    </script>

</body>
</html>
