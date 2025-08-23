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
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 sm:p-8">
        {{-- Tombol Kembali ke Halaman Detail --}}
        <div class="mb-6">
            <a href="{{ route('agenda.show', $agenda->agenda_id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
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
                    callbacks: {
                        onInit: function() {
                            console.log('Summernote berhasil diinisialisasi di halaman edit.');
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>