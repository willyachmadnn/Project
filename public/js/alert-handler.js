/**
 * Alert Handler - Menangani animasi dan perilaku alert
 * Versi yang dioptimalkan untuk animasi langsung masuk setelah login
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menyembunyikan alert dengan animasi yang smooth
    function hideAlert(alert) {
        alert.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        alert.classList.remove('opacity-100');
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        alert.style.pointerEvents = 'none';
        
        setTimeout(() => {
            alert.style.height = alert.offsetHeight + 'px';
            alert.style.overflow = 'hidden';
            
            setTimeout(() => {
                alert.style.height = '0px';
                alert.style.marginBottom = '0';
                
                setTimeout(() => {
                    alert.remove();
                }, 200);
            }, 100);
        }, 300);
    }
    
    // Tangani alert yang ada
    const alertSection = document.getElementById('alert-section');
    const alerts = document.querySelectorAll('[role="alert"]');
    
    // Dengan posisi fixed, kita tidak memerlukan placeholder lagi
    // karena alert tidak mempengaruhi tata letak dokumen
    
    if (alerts.length > 0) {
        // Pastikan alert section memiliki transisi yang tepat
        if (alertSection) {
            // Dengan posisi fixed, kita tidak perlu mengelola placeholder
            
            // Posisikan alert di atas card dengan posisi fixed
            // Ini akan memastikan alert muncul di atas card dan tidak mempengaruhi tata letak
            alertSection.style.position = 'fixed';
            alertSection.style.top = '20px';
            alertSection.style.left = '50%';
            alertSection.style.transform = 'translateX(-50%)';
            alertSection.style.zIndex = '9999';
            alertSection.style.marginBottom = '0';
            
            // Sesuaikan lebar alert agar tidak terlalu lebar
            alertSection.style.width = 'auto';
            alertSection.style.maxWidth = '90%';
            alertSection.style.minWidth = '300px';
            
            // Tambahkan padding untuk tampilan yang lebih baik
            alertSection.style.padding = '0';
            alertSection.style.borderRadius = '8px';
            alertSection.style.backgroundColor = 'white';
            
            // Pastikan alert memiliki transisi yang tepat
            alertSection.style.transition = 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            
            // Aktifkan pointer events untuk interaksi dengan alert
            alertSection.style.pointerEvents = 'auto';
            
            // Tambahkan efek backdrop untuk alert section
            alertSection.style.backdropFilter = 'blur(5px)';
            alertSection.style.webkitBackdropFilter = 'blur(5px)';
        }
        
        // Tambahkan class untuk animasi langsung muncul
        alerts.forEach((alert, index) => {
            // Pastikan alert memiliki style yang tepat
            alert.classList.add('relative', 'transition-all', 'duration-300', 'ease-in-out', 'transform');
            // Aktifkan pointer-events pada alert untuk interaksi
            alert.style.pointerEvents = 'auto';
            
            // Tombol tutup akan ditambahkan nanti
            
            // Animasi slide-in dari atas yang lebih smooth
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-30px)';
            alert.style.transformOrigin = 'top center';
            
            // Tambahkan margin dan styling untuk tampilan yang lebih baik
            alert.style.margin = '0 0 12px 0';
            alert.style.backgroundColor = 'white';
            alert.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            
            // Tambahkan efek hover yang halus
            alert.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });
            
            alert.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
            });
            
            // Animasi masuk yang lebih sederhana dan smooth
            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateY(0)';
                alert.classList.add('opacity-100');
            }, index * 100); // Delay bertahap untuk setiap alert
            
            // Auto-hide alert setelah 6 detik
            setTimeout(() => {
                hideAlert(alert);
            }, 6000); // Waktu yang lebih optimal untuk membaca
            
            // Tambahkan event listener untuk menghilangkan alert saat scrolling
            let scrollHandler = function() {
                if (window.scrollY > 50) { // Threshold scroll 50px
                    hideAlert(alert);
                    window.removeEventListener('scroll', throttledScrollHandler);
                }
            };
            
            // Gunakan throttling untuk mencegah terlalu banyak event firing saat scroll
            let lastScrollTime = 0;
            let throttledScrollHandler = function() {
                const now = Date.now();
                if (now - lastScrollTime > 100) { // Throttle ke 100ms
                    lastScrollTime = now;
                    scrollHandler();
                }
            };
            
            window.addEventListener('scroll', throttledScrollHandler);
            
            // Tambahkan tombol close dengan styling yang lebih modern
            const closeBtn = document.createElement('button');
            closeBtn.className = 'close-btn absolute top-2 right-2 text-gray-400 p-1.5 rounded-full transition-all duration-300 flex items-center justify-center';
            
            // Gunakan SVG untuk tombol close yang lebih modern
            closeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            `;
            
            closeBtn.style.width = '24px';
            closeBtn.style.height = '24px';
            closeBtn.style.opacity = '0.7';
            closeBtn.style.zIndex = '10';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.pointerEvents = 'auto'; // Pastikan tombol close dapat diklik
            closeBtn.style.backgroundColor = 'rgba(0, 0, 0, 0.05)';
            closeBtn.style.transform = 'scale(1)';
            
            // Tambahkan efek hover yang lebih menarik
            closeBtn.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
                this.style.opacity = '1';
                this.style.transform = 'scale(1.1) rotate(90deg)';
            });
            
            closeBtn.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'rgba(0, 0, 0, 0.05)';
                this.style.opacity = '0.7';
                this.style.transform = 'scale(1) rotate(0deg)';
            });
            
            // Event listener untuk tombol close dengan animasi yang lebih menarik
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Animasi untuk tombol close saat diklik
                this.style.transform = 'scale(1.1) rotate(90deg)';
                this.style.transition = 'all 0.15s ease-out';
                
                setTimeout(() => {
                    hideAlert(alert);
                    window.removeEventListener('scroll', throttledScrollHandler);
                }, 100);
            });
            
            alert.appendChild(closeBtn);
        });
    }
});