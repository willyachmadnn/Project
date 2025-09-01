<x-layout title="Agenda Selesai">
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Warning Icon -->
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-orange-500 rounded-full flex items-center justify-center mb-6">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Agenda Telah Selesai</h1>
            <p class="text-gray-600">Registrasi untuk agenda ini sudah ditutup</p>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center space-y-4">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-orange-800 mb-2">{{ $agenda->judul }}</h3>
                    <p class="text-orange-700 text-sm">{{ $agenda->deskripsi }}</p>
                    <div class="mt-3 text-sm text-orange-600">
                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F Y') }}</p>
                        <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}</p>
                        <p><strong>Lokasi:</strong> {{ $agenda->lokasi }}</p>
                        <p><strong>Status:</strong> <span class="font-semibold text-orange-700">{{ ucfirst($agenda->status) }}</span></p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-gray-700">
                        <i class="fas fa-info-circle text-orange-600 mr-2"></i>
                        Agenda ini telah berakhir dan registrasi ditutup
                    </p>
                    <p class="text-gray-700">
                        <i class="fas fa-calendar-times text-red-600 mr-2"></i>
                        Tidak dapat melakukan registrasi kehadiran
                    </p>
                </div>

                <!-- Action Button -->
                <div class="pt-6">
                    <button 
                        onclick="closePage()" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Tutup Halaman
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                Hubungi penyelenggara untuk informasi lebih lanjut
            </p>
        </div>
    </div>
</div>

<script>
/**
 * Fungsi untuk menutup halaman dengan fallback yang aman
 * Mengikuti best practices untuk web accessibility dan user experience
 */
function closePage() {
    // Arahkan ke landing page umum/tamu (tanpa autentikasi admin)
    window.location.href = '/';
}



// Keyboard shortcuts untuk accessibility
document.addEventListener('keydown', function(event) {
    // ESC key untuk tutup halaman
    if (event.key === 'Escape') {
        closePage();
    }
});
</script>

</x-layout>