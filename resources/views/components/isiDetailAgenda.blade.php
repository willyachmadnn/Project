{{-- resources/views/components/isiDetailAgenda.blade.php --}}
@props(['agenda'])

<div class="glass-effect rounded-2xl p-8 h-full flex flex-col">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-800 rounded-full flex items-center justify-center mr-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Detail Agenda</h3>
        </div>
        <div class="flex space-x-3">
            <button @click="showQrModal = true; openQrModal()" class="flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12v4m6-4h.01M12 8h.01M12 8h4.01M12 8H7.99M12 8V4m0 0H7.99M12 4h4.01"></path></svg>
                QR
            </button>
            <button @click="openEditModal()" class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002 2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                EDIT
            </button>
            {{-- PERBAIKAN FINAL: Menggunakan $dispatch untuk mengirim sinyal --}}
            <button @click="$dispatch('open-delete-modal')" class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                HAPUS
            </button>
        </div>
    </div>
    <div class="text-sm border border-rose-200 rounded-md overflow-hidden bg-white flex-1">
        <div class="flex bg-rose-100">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tempat</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tempat }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Tanggal</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->tanggal->format('d F Y') }}</div>
        </div>
        <div class="flex bg-rose-100 border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Waktu</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Nama Admin</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->admin->nama_admin ?? 'Data belum diisi' }}</div>
        </div>
        <div class="flex bg-rose-100 border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">OPD Admin</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->admin->opd_admin ?? 'Data belum diisi' }}</div>
        </div>
        <div class="flex bg-white border-t border-rose-200">
            <div class="w-40 flex-shrink-0 px-4 py-3 font-bold text-gray-800 border-r border-rose-200">Dihadiri</div>
            <div class="flex-1 px-4 py-3 text-gray-700 break-words">{{ $agenda->dihadiri ?? 'Data belum diisi' }}</div>
        </div>
    </div>
    <div class="mt-4 flex justify-center">
        @if($agenda->status === 'Menunggu')
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Agenda Menunggu
            </span>
        @elseif($agenda->status === 'Berlangsung')
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg animate-pulse">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                Sedang Berlangsung
            </span>
        @else
            <span class="inline-flex items-center px-6 py-3 rounded-md text-sm font-semibold bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Agenda Selesai
            </span>
        @endif
    </div>

    <!-- Modal QR Code -->
<template x-teleport="body">
    <div x-cloak x-show="showQrModal" @keydown.escape.window="showQrModal = false" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showQrModal = false"></div>
        <div x-show="showQrModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-[1000] bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-xl font-semibold text-gray-900">QR Code - {{ $agenda->nama_agenda }}</h3>
                <button type="button" @click="showQrModal = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-full">&times;</button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6 space-y-4 overflow-y-auto">

                        <!-- QR Codes Container -->
                        <div class="flex flex-col sm:flex-row justify-around items-center gap-8 mb-6">
                            <!-- QR Code Pegawai -->
                            <div class="flex flex-col items-center">
                                <h4 class="text-xl font-semibold text-gray-700 mb-3">Untuk Pegawai</h4>
                                <div id="qr-pegawai" class="p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg shadow-inner"></div>
                                <p class="text-sm text-gray-500 mt-2 text-center">Scan untuk mengisi form kehadiran pegawai</p>
                            </div>
                            
                            <!-- QR Code Non Pegawai -->
                            <div class="flex flex-col items-center">
                                <h4 class="text-xl font-semibold text-gray-700 mb-3">Untuk Non Pegawai</h4>
                                <div id="qr-non-pegawai" class="p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg shadow-inner"></div>
                                <p class="text-sm text-gray-500 mt-2 text-center">Scan untuk mengisi form kehadiran tamu</p>
                            </div>
                        </div>

            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end p-5 border-t space-x-3">
                <button @click="downloadQrPdf()" :disabled="isDownloading" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span x-show="!isDownloading">Download PDF</span>
                    <span x-show="isDownloading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating...
                    </span>
                </button>
                <button @click="showQrModal = false" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>

<!-- QR Code Scripts -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    function openQrModal() {
        // Clear previous QR codes
        document.getElementById('qr-pegawai').innerHTML = '';
        document.getElementById('qr-non-pegawai').innerHTML = '';
        
        // Generate QR codes
        setTimeout(() => {
            // QR Code untuk Pegawai
            new QRCode(document.getElementById('qr-pegawai'), {
                text: `${window.location.origin}/agenda/{{ $agenda->id }}/form/pegawai`,
                width: 180,
                height: 180,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
            
            // QR Code untuk Non Pegawai
            new QRCode(document.getElementById('qr-non-pegawai'), {
                text: `${window.location.origin}/agenda/{{ $agenda->id }}/form/non-pegawai`,
                width: 180,
                height: 180,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        }, 100);
    }
    
    async function downloadQrPdf() {
        const { jsPDF } = window.jspdf;
        const modal = document.querySelector('.inline-block.align-bottom.bg-white');
        const downloadBtn = event.target;
        
        // Show loading state
        downloadBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
        downloadBtn.disabled = true;
        
        try {
            // Capture the QR codes area
            const qrContainer = document.querySelector('.flex.flex-col.sm\\:flex-row.justify-around');
            const canvas = await html2canvas(qrContainer, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true
            });
            
            const imgData = canvas.toDataURL('image/png');
            
            // Create PDF
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });
            
            // Add title
            pdf.setFontSize(16);
            pdf.setFont('helvetica', 'bold');
            pdf.text('QR Code - {{ $agenda->nama_agenda }}', 105, 20, { align: 'center' });
            
            // Add QR codes image
            const pdfWidth = 210; // A4 width in mm
            const pdfHeight = 297; // A4 height in mm
            const imgWidth = canvas.width;
            const imgHeight = canvas.height;
            const ratio = Math.min((pdfWidth - 40) / imgWidth, (pdfHeight - 60) / imgHeight);
            
            const scaledWidth = imgWidth * ratio;
            const scaledHeight = imgHeight * ratio;
            const x = (pdfWidth - scaledWidth) / 2;
            const y = 40;
            
            pdf.addImage(imgData, 'PNG', x, y, scaledWidth, scaledHeight);
            
            // Add footer
            pdf.setFontSize(10);
            pdf.setFont('helvetica', 'normal');
            pdf.text('Generated on: ' + new Date().toLocaleString('id-ID'), 105, pdfHeight - 10, { align: 'center' });
            
            // Save PDF
            const fileName = `QR-Code-${"{{ Str::slug($agenda->nama_agenda) }}".replace(/[^a-zA-Z0-9-]/g, '')}.pdf`;
            pdf.save(fileName);
            
        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Terjadi kesalahan saat membuat PDF. Silakan coba lagi.');
        } finally {
            // Reset button
            downloadBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>Download PDF';
            downloadBtn.disabled = false;
        }
    }
</script>
