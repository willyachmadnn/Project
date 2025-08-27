{{-- resources/views/components/isiDetailAgenda.blade.php --}}
@props(['agenda'])

<div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 h-full overflow-y-auto shadow-lg border border-gray-200">
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
        <div x-cloak x-show="showQrModal" @keydown.escape.window="showQrModal = false" class="qr-modal fixed inset-0 z-[1001] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="showQrModal = false"></div>
            <div x-show="showQrModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-[1002] bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col border border-gray-200 shadow-2xl">
            <!-- Modal Header -->
            <div class="modal-content flex items-center justify-between p-5 border-b">
                <h3 class="text-xl font-semibold text-gray-900">QR Code - {{ $agenda->nama_agenda }}</h3>
                <button type="button" @click="showQrModal = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-full">&times;</button>
            </div>
            
            <!-- Modal Content -->
            <div class="modal-content p-6 space-y-4 overflow-y-auto">

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
            <div class="modal-content flex items-center justify-end p-5 border-t space-x-3">
                <button id="downloadQrPdfBtn" type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </button>
                <button @click="showQrModal = false" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </template>

<style class="ignore-pdf">
    /* Override any oklch colors with fallbacks for PDF generation */
    #qr-pegawai, #qr-non-pegawai {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    
    #qr-pegawai *, #qr-non-pegawai * {
        background-color: transparent !important;
        color: inherit !important;
    }
    
    #qr-pegawai canvas, #qr-non-pegawai canvas {
        background-color: #ffffff !important;
    }
    
    /* Ensure no modern CSS color functions are used */
    .qr-container {
        background: #ffffff !important;
        color: #000000 !important;
    }
</style>

<!-- All QR Code and PDF Scripts are loaded in layout.blade.php -->

<script>
    function openQrModal() {
        console.log('Opening QR Modal...');
        
        // Force immediate generation without waiting
        generateQrCodes();
    }
    
    function generateQrCodes() {
        console.log('Generating QR Codes...');
        
        const baseUrl = window.location.origin;
        const agendaId = {{ $agenda->agenda_id }};
        
        const urlPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=pegawai`;
        const urlNonPegawai = `${baseUrl}/tamu/create?agenda_id=${agendaId}&type=non-pegawai`;
        
        console.log('QR URLs:', urlPegawai, urlNonPegawai);
        
        const pegawaiContainer = document.getElementById('qr-pegawai');
        const nonPegawaiContainer = document.getElementById('qr-non-pegawai');
        
        if (!pegawaiContainer || !nonPegawaiContainer) {
            console.error('Containers not found!');
            return;
        }
        
        // Clear containers completely
        pegawaiContainer.innerHTML = '';
        nonPegawaiContainer.innerHTML = '';
        
        try {
            // Create QR codes with simple approach
            if (typeof QRCode !== 'undefined') {
                // Clear any existing QR instances
                if (window.qrPegawai) {
                    window.qrPegawai.clear();
                    window.qrPegawai = null;
                }
                if (window.qrNonPegawai) {
                    window.qrNonPegawai.clear();
                    window.qrNonPegawai = null;
                }
                
                window.qrPegawai = new QRCode(pegawaiContainer, {
                    text: urlPegawai,
                    width: 180,
                    height: 180
                });
                
                window.qrNonPegawai = new QRCode(nonPegawaiContainer, {
                    text: urlNonPegawai,
                    width: 180,
                    height: 180
                });
                
                console.log('QR Codes generated successfully!');
            } else {
                console.error('QRCode library not available');
                // Fallback: create simple text
                pegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid #ccc;">QR Pegawai<br>' + urlPegawai + '</div>';
                nonPegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid #ccc;">QR Non-Pegawai<br>' + urlNonPegawai + '</div>';
            }
        } catch (error) {
            console.error('QR Generation Error:', error);
            pegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid red;">Error: ' + error.message + '</div>';
            nonPegawaiContainer.innerHTML = '<div style="padding:20px;border:1px solid red;">Error: ' + error.message + '</div>';
        }
    }
    
    // Simplified PDF download
    $(document).ready(function() {
        $(document).on('click', '#downloadQrPdfBtn', function() {
            const button = $(this);
            const originalText = button.html();
            button.html('Generating PDF...').prop('disabled', true);

            try {
                // Check if QR codes already exist, if not generate them
                const qrPegawai = document.getElementById('qr-pegawai');
                const qrNonPegawai = document.getElementById('qr-non-pegawai');
                
                if (!qrPegawai || !qrNonPegawai) {
                    throw new Error('QR containers not found');
                }
                
                // Check if QR codes are already generated
                const pegawaiHasContent = qrPegawai.innerHTML.trim() !== '';
                const nonPegawaiHasContent = qrNonPegawai.innerHTML.trim() !== '';
                
                if (!pegawaiHasContent || !nonPegawaiHasContent) {
                    console.log('QR codes not found, generating...');
                    generateQrCodes();
                    // Wait longer for generation to complete
                    setTimeout(() => {
                        processPdfGeneration();
                    }, 3000);
                } else {
                    console.log('QR codes already exist, proceeding with PDF generation...');
                    processPdfGeneration();
                }
                
                function processPdfGeneration() {
                    try {
                        // Ultra-simple approach: Generate PDF without html2canvas
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        });
                        
                        // Add title
                        pdf.setFontSize(20);
                        pdf.text('QR Code Agenda', 105, 30, { align: 'center' });
                        
                        pdf.setFontSize(16);
                        pdf.text('{{ $agenda->nama_agenda }}', 105, 45, { align: 'center' });
                        
                        // Add QR section headers
                        pdf.setFontSize(14);
                        pdf.text('QR Code Pegawai', 52.5, 70, { align: 'center' });
                        pdf.text('QR Code Non-Pegawai', 157.5, 70, { align: 'center' });
                        
                        // Get QR canvas elements
                        const qrPegawai = document.getElementById('qr-pegawai');
                        const qrNonPegawai = document.getElementById('qr-non-pegawai');
                        
                        let addedImages = 0;
                        const totalImages = 2;
                        
                        function checkComplete() {
                            addedImages++;
                            if (addedImages >= totalImages) {
                                pdf.save(`QR-Code-{{ Str::slug($agenda->nama_agenda) }}.pdf`);
                                button.html(originalText).prop('disabled', false);
                                console.log('PDF generated successfully!');
                            }
                        }
                        
                        // Add QR images directly from canvas
                        const pegawaiCanvas = qrPegawai ? qrPegawai.querySelector('canvas') : null;
                        if (pegawaiCanvas) {
                            try {
                                const imgData = pegawaiCanvas.toDataURL('image/png');
                                pdf.addImage(imgData, 'PNG', 20, 80, 65, 65);
                            } catch (e) {
                                console.warn('Failed to add pegawai QR:', e);
                                pdf.rect(20, 80, 65, 65);
                                pdf.text('QR Pegawai', 52.5, 115, { align: 'center' });
                            }
                        } else {
                            pdf.rect(20, 80, 65, 65);
                            pdf.text('QR Pegawai', 52.5, 115, { align: 'center' });
                        }
                        checkComplete();
                        
                        const nonPegawaiCanvas = qrNonPegawai ? qrNonPegawai.querySelector('canvas') : null;
                        if (nonPegawaiCanvas) {
                            try {
                                const imgData = nonPegawaiCanvas.toDataURL('image/png');
                                pdf.addImage(imgData, 'PNG', 125, 80, 65, 65);
                            } catch (e) {
                                console.warn('Failed to add non-pegawai QR:', e);
                                pdf.rect(125, 80, 65, 65);
                                pdf.text('QR Non-Pegawai', 157.5, 115, { align: 'center' });
                            }
                        } else {
                            pdf.rect(125, 80, 65, 65);
                            pdf.text('QR Non-Pegawai', 157.5, 115, { align: 'center' });
                        }
                        checkComplete();
                        
                    } catch (error) {
                        console.error('PDF generation error:', error);
                        alert('PDF generation failed: ' + (error.message || 'Unknown error'));
                        button.html(originalText).prop('disabled', false);
                    }
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                button.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
