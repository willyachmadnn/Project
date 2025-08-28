{{-- resources/views/components/isiNotulen.blade.php --}}
@props(['agenda'])

<style>
    /* CSS spesifik untuk komponen ini */
    .notulen-container {
        background: transparent;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .notulen-form-group .note-editor.note-frame {
        border: 1px solid #ccc;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
    
    /* Optimasi untuk Summernote responsif */
    .note-editor {
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    
    .note-toolbar {
        max-width: 100% !important;
        overflow-x: auto !important;
        white-space: nowrap;
    }
    
    .note-editing-area {
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    
    /* Responsive toolbar untuk mobile */
    @media (max-width: 768px) {
        .note-toolbar .btn-group {
            margin-bottom: 5px;
        }
        
        .note-toolbar {
            flex-wrap: wrap;
            height: auto !important;
        }
    }
    
    /* Prevent horizontal overflow */
    * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Smooth resize behavior */
    .notulen-container,
    .note-editor,
    .note-editing-area {
        transition: all 0.3s ease;
        resize: vertical;
    }
</style>

<div class="notulen-container bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/50 rounded-3xl shadow-2xl border border-gray-200/50 p-4 sm:p-6 lg:p-8 mb-4 sm:mb-6 lg:mb-8 backdrop-blur-sm max-w-full overflow-hidden">
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-blue-700">Notulen Rapat</h3>
                <p class="text-gray-600 mt-2 text-lg">Dokumentasi lengkap hasil rapat dan keputusan penting</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-100 to-indigo-100 rounded-2xl p-4 sm:p-6 border border-blue-200/50">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-2">Panduan Pengisian Notulen</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Isi nama pimpinan rapat pada kolom di bawah untuk dicantumkan di bagian tanda tangan PDF</li>
                        <li>• Tulis isi notulen pada editor dengan lengkap dan jelas</li>
                        <li>• Klik <strong>"Simpan Notulen"</strong> untuk menyimpan perubahan</li>
                        <li>• Gunakan tombol <strong>"Download PDF"</strong> untuk mengunduh dokumen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form untuk menyimpan atau memperbarui notulen -->
    <form id="notulenForm" 
          action="{{ $agenda->notulen ? route('agenda.notulen.update', [$agenda->agenda_id, $agenda->notulen->id]) : route('agenda.notulen.store', $agenda->agenda_id) }}" 
          method="POST" class="space-y-8">
        @csrf
        @if($agenda->notulen)
            @method('PUT')
        @endif

        {{-- Input Teks untuk Nama Pimpinan Rapat di TTD PDF --}}
        <div class="space-y-4">
            <label for="pimpinan_rapat_ttd" class="block text-sm font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Nama Pimpinan Rapat (untuk TTD di PDF)
                <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative">
                <input type="text" 
                       id="pimpinan_rapat_ttd" 
                       name="pimpinan_rapat_ttd" 
                       value="{{''}}" 
                       class="w-full px-4 py-4 pl-12 bg-white border-2 border-purple-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 hover:border-purple-300 text-gray-800 font-medium shadow-sm" 
                       placeholder="Masukkan nama lengkap pimpinan rapat">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 bg-purple-50/50 p-4 rounded-xl border border-purple-200/50 flex items-start">
                <svg class="w-4 h-4 mr-2 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Nama yang diisi di sini akan muncul di bagian tanda tangan pada dokumen PDF yang diunduh.
            </p>
        </div>

        {{-- Editor untuk isi notulen --}}
        <div class="space-y-4">
            <label for="summernote-editor" class="block text-sm font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Isi Notulen
                <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="bg-white border-2 border-blue-200 rounded-xl shadow-sm overflow-hidden w-full">
                <textarea id="summernote-editor" name="isi_notulen" class="summernote">
                    {!! old('isi_notulen', $agenda->notulen->isi_notulen ?? '') !!}
                </textarea>
            </div>
        </div>
            
            {{-- Tombol Aksi --}}
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 sm:p-6 border border-gray-200/50">
                <div class="flex flex-col lg:flex-row gap-6 justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <p class="font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pastikan semua informasi sudah lengkap sebelum menyimpan
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <button type="button" id="downloadPdfBtn"
                            class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-200 flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-red-500/50">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                        <button type="submit"
                            class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-200 flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-green-500/50">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Notulen
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Template PDF yang disempurnakan (disembunyikan dari tampilan) -->
<div id="pdf-template" style="position: absolute; left: -9999px; width: 210mm; background: white; color: black; font-family: 'Times New Roman', Times, serif; padding: 15mm;">
    {{-- PERUBAHAN: Menyesuaikan CSS dan HTML Kop Surat --}}
    <style>
        #pdf-template .header { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 10px; }
        #pdf-template .header img { width: 80px; height: auto; margin-right: 20px; }
        #pdf-template .header .title-container { text-align: center; flex-grow: 1; line-height: 1.3; }
        #pdf-template .title-container p { margin: 0; }
        #pdf-template .line1 { font-size: 16px; font-weight: bold; }
        #pdf-template .line2 { font-size: 20px; font-weight: bold; }
        #pdf-template .line3 { font-size: 12px; }
        #pdf-template .notula-title { text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline; margin: 20px 0; }
        #pdf-template .details-table { font-size: 12px; width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        #pdf-template .details-table td { padding: 4px 0; vertical-align: top; }
        #pdf-template .details-table td:nth-child(1) { width: 150px; }
        #pdf-template .details-table td:nth-child(2) { width: 10px; text-align: center; }
        #pdf-template .content-body { margin-top: 10px; font-size: 12px; min-height: 100px; }
        #pdf-template .signature-section { margin-top: 40px; text-align: right; }
    </style>
    
    <div class="header">
        <img src='/img/mojokerto_kab.png' alt="Logo Kabupaten Mojokerto">
        <div class="title-container">
            <p class="line1">PEMERINTAH KABUPATEN MOJOKERTO</p>
            <p class="line2">DINAS KOMUNIKASI DAN INFORMATIKA</p>
            <p class="line3">KH. HASYIM ASHARI No 12 Mojokerto, Kode Pos 61318 Jawa Timur</p>
            <p class="line3">Telp.(0321) 391268 Fax. (0321) 391268</p>
            <p class="line3"><b>Website : http://www.diskominfo.mojokertokab.go.id</b></p>
        </div>
    </div>
    <h3 class="notula-title">NOTULA</h3>
    
    <table class="details-table">
        <tr><td>Sidang / Rapat</td><td>:</td><td>{{ $agenda->nama_agenda }}</td></tr>
        <tr><td>Hari / Tanggal</td><td>:</td><td>{{ $agenda->tanggal->translatedFormat('l, d F Y') }}</td></tr>
        <tr><td>Waktu</td><td>:</td><td>{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</td></tr>
        <tr><td>Tempat</td><td>:</td><td>{{ $agenda->tempat }}</td></tr>
        <tr><td>Pimpinan Rapat</td><td>:</td><td id="pdf_pimpinan_name_detail">{{ $agenda->admin->nama_admin ?? 'Pimpinan Rapat' }}</td></tr>
        <tr><td>Peserta</td><td>:</td><td>{{ $agenda->dihadiri }}</td></tr>
        <tr><td>Pembuat Notulen</td><td>:</td><td>{{ Auth::user()->nama_admin ?? 'Notulis' }}</td></tr>
    </table>
    
    <p style="font-size: 12px; font-weight: bold; margin-top: 20px; text-decoration: underline;">Isi Agenda:</p>
    <div id="pdf-content-body" class="content-body"></div>

    <div class="signature-section">
        <p style="margin-bottom: 70px;">Pimpinan Sidang / Rapat,</p>
        <p id="pdf_pimpinan_name_signature" style="font-weight: bold; text-decoration: underline;">
            {{ $agenda->admin->nama_admin ?? '(_____________________)' }}
        </p>
    </div>
</div>

<!-- Skrip untuk fungsionalitas Download PDF -->
<script>
    $(document).ready(function() {
        $(document).on('click', '#downloadPdfBtn', function() {
            const button = $(this);
            const originalText = button.html();
            button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Mempersiapkan...').prop('disabled', true);

            try {
                const pimpinanName = $('#pimpinan_rapat_ttd').val();
                
                $('#pdf_pimpinan_name_detail').text(pimpinanName || 'Pimpinan Rapat');
                $('#pdf_pimpinan_name_signature').text(pimpinanName || '(_____________________)');

                const editorContent = $('#summernote-editor').summernote('code');
                $('#pdf-content-body').html(editorContent);
                
                html2canvas(document.getElementById('pdf-template'), { scale: 2, useCORS: true }).then(canvas => {
                    const imgData = canvas.toDataURL('/img/mojokerto_kab.png');
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                    
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const imgHeight = canvas.height * pdfWidth / canvas.width;
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, imgHeight);
                    pdf.save('notulen-rapat-{{ Str::slug($agenda->nama_agenda) }}_{{ Str::slug($agenda->tanggal->format('d-m-Y')) }}.pdf');
                    
                    button.html(originalText).prop('disabled', false);
                }).catch(error => {
                    console.error('Gagal membuat PDF:', error);
                    alert('Gagal membuat PDF. Silakan cek konsol untuk detail.');
                    button.html(originalText).prop('disabled', false);
                });
            } catch (error) {
                console.error('Error saat proses pembuatan PDF:', error);
                alert('Terjadi kesalahan. Silakan cek konsol untuk detail.');
                button.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
