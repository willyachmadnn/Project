<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Notulen: {{ $agenda->nama_agenda }}</title>

    {{-- Aset yang Diperlukan untuk Editor Berfungsi --}}
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
    
    <!-- CSS khusus untuk modal Summernote -->
    <style>
        /* Perbaikan z-index untuk modal Summernote */
        .note-modal {
            z-index: 9999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        /* Pastikan input dalam modal dapat diklik dan diketik */
        .note-modal input,
        .note-modal textarea,
        .note-modal select {
            pointer-events: auto !important;
            z-index: 10000 !important;
        }
        
        /* Perbaikan untuk dialog link dan picture */
        .note-link-dialog input[type="text"],
        .note-image-dialog input[type="text"],
        .note-image-dialog input[type="file"] {
            background-color: white !important;
            border: 1px solid #ccc !important;
            padding: 8px !important;
            width: 100% !important;
        }
        
        /* Pastikan tombol dalam modal dapat diklik */
        .note-modal .btn {
            pointer-events: auto !important;
            z-index: 10001 !important;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 sm:p-8 z-index: 3000;">
        {{-- Tombol Kembali ke Halaman Detail --}}
        <div class="mb-6 ml-6">
            <a href="{{ route('agenda.show', $agenda->agenda_id) }}#notulen" onclick="sessionStorage.setItem('activeTab', 'notulen');"
                class="group inline-flex items-center px-5 py-2.5 bg-[#ac1616] hover:bg-red-700 text-white hover:text-amber-50 font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#ac1616] focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Agenda
            </a>
        </div>

        {{-- Memanggil komponen isiNotulen --}}
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <x-isiNotulen :agenda="$agenda" />
        </div>
    </div>

    {{-- Skrip untuk inisialisasi Summernote di halaman ini --}}
    <script>
        $(document).ready(function() {
            const editor = $('#summernote-editor');
            if (editor.length > 0 && !editor.hasClass('note-editor')) {
                editor.summernote({
                    placeholder: 'Tulis isi notulen di sini...',
                    tabsize: 2,
                    height: 500,
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
                    dialogsInBody: true,
                    dialogsFade: true,
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote berhasil diinisialisasi di halaman edit.');
                            
                            // Perbaikan z-index untuk modal Summernote
                            $('.note-modal').css('z-index', '9999');
                            $('.modal-backdrop').css('z-index', '9998');
                        },
                        onDialogShown: function() {
                            // Pastikan modal muncul di atas elemen lain
                            $('.note-modal').css('z-index', '9999');
                            $('.modal-backdrop').css('z-index', '9998');
                            
                            // Perbaikan khusus untuk input dalam modal
                            $('.note-modal input, .note-modal textarea, .note-modal select').each(function() {
                                $(this).css({
                                    'pointer-events': 'auto',
                                    'z-index': '10000',
                                    'position': 'relative'
                                });
                            });
                            
                            // Focus pada input pertama dalam modal
                            setTimeout(function() {
                                $('.note-modal input:visible:first').focus().click();
                            }, 150);
                        },
                        onImageUpload: function(files) {
                            // Handle image upload jika diperlukan
                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    $(editor).summernote('insertImage', e.target.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>