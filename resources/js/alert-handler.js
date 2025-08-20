/**
 * Alert Handler - Menangani animasi dan perilaku alert
 * Versi yang dioptimalkan untuk ruang yang transisi yang lebih halus
 */

document.addEventListener('DOMContentLoaded', function() {
    // Tangani alert yang ada
    const alertSection = document.getElementById('alert-section');
    const alerts = document.querySelectorAll('[role="alert"]');
    
    // Buat placeholder untuk mempertahankan ruang
    let alertPlaceholder = null;
    
    if (alerts.length > 0) {
        // Pastikan alert section memiliki transisi yang tepat
        if (alertSection) {
            // Ukur tinggi alert section untuk placeholder
            const alertHeight = alertSection.offsetHeight;
            
            // Buat placeholder dengan tinggi yang sama plus margin untuk jarak konsisten
            alertPlaceholder = document.createElement('div');
            alertPlaceholder.style.height = (alertHeight + 48) + 'px'; // Tambahkan 48px (3rem) untuk jarak konsisten
            alertPlaceholder.style.transition = 'height 0.3s ease-out';
            alertPlaceholder.id = 'alert-placeholder';
            alertSection.parentNode.insertBefore(alertPlaceholder, alertSection.nextSibling);
            
            // Gunakan posisi fixed untuk alert section agar tidak menyebabkan pergeseran
            alertSection.style.position = 'fixed';
            alertSection.style.top = '0';
            alertSection.style.left = '0';
            alertSection.style.right = '0';
            alertSection.style.zIndex = '50';
            alertSection.style.transition = 'opacity 0.4s ease-in-out, transform 0.5s ease-in-out';
            // Sesuaikan padding dengan container
            const containerPadding = window.getComputedStyle(document.querySelector('.container')).paddingLeft;
            alertSection.style.padding = `0 ${containerPadding}`;
            alertSection.style.pointerEvents = 'none'; // Memungkinkan interaksi dengan elemen di bawahnya
        }
        
        // Tambahkan class untuk animasi fade-in
        alerts.forEach((alert, index) => {
            // Pastikan alert memiliki style yang tepat
            alert.classList.add('opacity-0', 'relative', 'transition-all', 'duration-500', 'ease-in-out', 'transform');
            // Aktifkan pointer-events pada alert untuk interaksi
            alert.style.pointerEvents = 'auto';
            
            // Animasi fade-in dengan delay bertahap untuk multiple alerts
            setTimeout(() => {
                alert.classList.remove('opacity-0');
                alert.classList.add('opacity-100');
                // Tambahkan efek transform untuk animasi yang lebih halus
                alert.style.transform = 'translateY(0) scale(1)';
            }, 100 + (index * 150)); // Delay bertahap untuk setiap alert
            
            // Auto-hide alert setelah 8 detik (waktu lebih lama untuk membaca)
            setTimeout(() => {
                alert.classList.remove('opacity-100');
                alert.classList.add('opacity-0');
                alert.style.transform = 'translateY(-10px) scale(0.98)';
                setTimeout(() => {
                    alert.remove();
                    // Jika tidak ada alert lagi, kurangi tinggi alert section dan placeholder dengan transisi
                    if (alertSection && document.querySelectorAll('[role="alert"]').length === 0) {
                        // Berikan transisi untuk mengurangi tinggi
                        alertSection.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        alertSection.style.opacity = '0';
                        alertSection.style.transform = 'translateY(-20px)';
                        
                        // Kurangi tinggi placeholder tetapi pertahankan margin untuk jarak konsisten
                        if (alertPlaceholder) {
                            alertPlaceholder.style.height = '48px'; // Pertahankan 48px (3rem) untuk jarak konsisten
                        }
                    }
                }, 600); // Waktu yang lebih lama untuk transisi yang lebih halus
            }, 8000); // Waktu yang lebih lama agar user dapat membaca pesan
            
            // Tambahkan event listener untuk menghilangkan alert saat scrolling dengan throttling untuk performa lebih baik
            let scrollHandler = function() {
                if (window.scrollY > 30) { // Kurangi threshold scroll ke 30px agar lebih responsif
                    // Tambahkan transisi yang lebih mulus
                    alert.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    alert.classList.remove('opacity-100');
                    alert.classList.add('opacity-0');
                    alert.style.transform = 'translateY(-15px) scale(0.97)';
                    setTimeout(() => {
                        alert.remove();
                        // Jika tidak ada alert lagi, kurangi tinggi alert section dan placeholder
                        if (alertSection && document.querySelectorAll('[role="alert"]').length === 0) {
                            alertSection.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                            alertSection.style.opacity = '0';
                            alertSection.style.transform = 'translateY(-20px)';
                            
                            // Kurangi tinggi placeholder tetapi pertahankan margin untuk jarak konsisten
                            if (alertPlaceholder) {
                                alertPlaceholder.style.height = '48px'; // Pertahankan 48px (3rem) untuk jarak konsisten
                            }
                        }
                        // Hapus event listener setelah alert dihapus
                        window.removeEventListener('scroll', scrollHandler);
                    }, 300); // Waktu transisi yang lebih cepat untuk responsivitas
                }
            };
            // Gunakan throttling untuk mencegah terlalu banyak event firing saat scroll
            let lastScrollTime = 0;
            window.addEventListener('scroll', function() {
                const now = Date.now();
                if (now - lastScrollTime > 80) { // Throttle ke 80ms untuk responsivitas lebih baik
                    lastScrollTime = now;
                    scrollHandler();
                }
            });
            
            // Tambahkan tombol close hanya jika belum ada tombol close
            // Cek apakah sudah ada tombol close (baik dengan class .close-btn atau button dengan onclick)
            const existingCloseBtn = alert.querySelector('.close-btn') || alert.querySelector('button[onclick*="hideAlert"]') || alert.querySelector('button[onclick*="hideErrorAlert"]');
            
            if (!existingCloseBtn) {
                const closeBtn = document.createElement('button');
                closeBtn.className = 'close-btn absolute top-2 right-2 text-gray-500 hover:text-gray-700 p-1.5 rounded-full hover:bg-gray-200 transition-all duration-300 flex items-center justify-center';
                closeBtn.innerHTML = '&times;';
                closeBtn.style.width = '28px'; // Sedikit lebih besar
                closeBtn.style.height = '28px'; // Sedikit lebih besar
                closeBtn.style.fontSize = '20px'; // Sedikit lebih besar
                closeBtn.style.lineHeight = '1';
                
                closeBtn.addEventListener('click', () => {
                    // Animasi fade-out saat tombol close diklik
                    alert.classList.remove('opacity-100');
                    alert.classList.add('opacity-0');
                    setTimeout(() => {
                        alert.remove();
                        // Jika tidak ada alert lagi, kurangi tinggi alert section
                        if (alertSection && document.querySelectorAll('[role="alert"]').length === 0) {
                            // Berikan transisi untuk mengurangi tinggi alert section
                            alertSection.style.transition = 'height 0.4s ease-out';
                            alertSection.style.height = '0px';
                            alertSection.style.overflow = 'hidden';
                            alertSection.style.marginBottom = '0px';
                        }
                    }, 500);
                });
                alert.appendChild(closeBtn);
            }
        });
    } else if (alertSection) {
        // Jika tidak ada alert, sembunyikan alert section dengan transisi
        alertSection.style.transition = 'opacity 0.5s ease-in-out';
        alertSection.style.opacity = '0';
        alertSection.style.pointerEvents = 'none';
    }
    
    // Tambahkan listener untuk form submission untuk mencegah flicker
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            // Tambahkan class untuk menunjukkan loading state
            document.body.classList.add('form-submitting');
            
            // Jika ada alert section, pastikan terlihat
            if (alertSection) {
                alertSection.style.opacity = '1';
                alertSection.style.pointerEvents = 'auto';
            }
        });
    });
});