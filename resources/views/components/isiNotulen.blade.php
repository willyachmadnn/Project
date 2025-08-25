{{-- resources/views/components/isiNotulen.blade.php --}}
@props(['agenda'])

<style>
    /* CSS spesifik untuk komponen ini */
    .notulen-container {
        background: transparent;
    }
    .notulen-form-group .note-editor.note-frame {
        border: 1px solid #ccc;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
</style>

<div class="notulen-container">
    {{-- Petunjuk Penggunaan --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 rounded-r-md" role="alert">
        <p class="font-bold text-base">Petunjuk Penggunaan:</p>
        <ul class="list-disc pl-5 mt-2 space-y-1 text-base">
            <li>Isi nama pimpinan rapat pada kolom di bawah untuk dicantumkan di bagian tanda tangan PDF.</li>
            <li>Tulis isi notulen pada editor.</li>
            <li>Klik <strong>"Simpan Notulen"</strong> untuk menyimpan perubahan.</li>
        </ul>
    </div>

    <!-- Form untuk menyimpan atau memperbarui notulen -->
    <form id="notulenForm" 
          action="{{ $agenda->notulen ? route('agenda.notulen.update', [$agenda->agenda_id, $agenda->notulen->id]) : route('agenda.notulen.store', $agenda->agenda_id) }}" 
          method="POST">
        @csrf
        @if($agenda->notulen)
            @method('PUT')
        @endif

        {{-- Input Teks untuk Nama Pimpinan Rapat di TTD PDF --}}
        <div class="mb-6">
            <label for="pimpinan_rapat_ttd" class="block text-base font-medium text-gray-700 mb-1">Nama Pimpinan Rapat (untuk TTD di PDF)</label>
            <input type="text" 
                   id="pimpinan_rapat_ttd" 
                   name="pimpinan_rapat_ttd" 
                   value="{{''}}" 
                   class="mt-1 block w-full rounded-md border-black border-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 text-base" 
                   placeholder="Masukkan nama lengkap pimpinan rapat">
            <p class="mt-2 text-sm text-gray-500">Nama yang diisi di sini akan muncul di bagian tanda tangan pada dokumen PDF yang diunduh.</p>
        </div>

        <div class="notulen-form-group bg-white rounded-lg shadow-sm p-3 border border-gray-200">
            <label for="summernote-editor" class="block text-base font-medium text-gray-700 mb-2">Isi Notulen</label>
            <textarea id="summernote-editor" name="isi_notulen" class="summernote">
                {!! old('isi_notulen', $agenda->notulen->isi_notulen ?? '') !!}
            </textarea>
        </div>
        
        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center mt-6">
            <button type="button" id="downloadPdfBtn"
                class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-md flex items-center">
                <i class="fas fa-file-pdf mr-2"></i>Download PDF
            </button>
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg flex items-center text-lg font-bold transform hover:scale-105">
                <i class="fas fa-save mr-2"></i>Simpan Notulen
            </button>
        </div>
    </form>
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
        <tr><td>Waktu</td><td>:</td><td>{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - Selesai</td></tr>
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
                    pdf.save('notulen-rapat-{{ Str::slug($agenda->nama_agenda) }}_{{ Str::slug($agenda->tanggal) }}.pdf');
                    
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