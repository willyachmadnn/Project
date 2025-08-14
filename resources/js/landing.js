// File: resources/js/landing.js

import '../css/landing.css';

document.addEventListener('DOMContentLoaded', () => {
    
    // ==================================================================
    // BAGIAN 1: LOGIKA ANIMASI DUA ARAH
    // ==================================================================
    
    // --- PENGATURAN ANIMASI (Bisa Anda sesuaikan) ---
    const REFLOW_DELAY = 100;      // Jeda untuk browser (biarkan 50ms)
    const STAGGER_DELAY = 200;    // Jeda antar teks (dalam milidetik)

    const animatedItems = document.querySelectorAll('.animate-item');
    const heroSpacer = document.querySelector('.hero-spacer');
    let isInitialLoad = true;

    const runAnimation = (direction) => {
        animatedItems.forEach(item => {
            item.classList.remove('is-visible', 'slide-from-top', 'slide-from-bottom');
            item.classList.add(direction);
        });

        // Gunakan variabel yang sudah didefinisikan di atas
        setTimeout(() => {
            animatedItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('is-visible');
                }, index * STAGGER_DELAY); // Menggunakan STAGGER_DELAY
            });
        }, REFLOW_DELAY); // Menggunakan REFLOW_DELAY
    };

    const resetAnimation = () => {
        animatedItems.forEach(item => {
            item.classList.remove('is-visible');
        });
    };

    // Jalankan animasi dari ATAS saat halaman pertama kali dimuat
    runAnimation('slide-from-top');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (!isInitialLoad) {
                    runAnimation('slide-from-bottom');
                }
            } else {
                resetAnimation();
                isInitialLoad = false;
            }
        });
    }, { root: null, threshold: 0.1 });

    if (heroSpacer) {
        observer.observe(heroSpacer);
    }

    // ==================================================================
    // BAGIAN 2: LOGIKA FILTER TABEL DENGAN AJAX (TIDAK BERUBAH)
    // ==================================================================
    const form = document.getElementById('filterForm');
    if (!form) return;

    const debounce = (func, delay) => {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const fetchContent = async (url) => {
        const tableWrapper = document.getElementById('tableWrap');
        if (!tableWrapper) return;
        tableWrapper.style.opacity = '0.5';
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableWrapper = doc.getElementById('tableWrap');
            if (newTableWrapper) {
                tableWrapper.innerHTML = newTableWrapper.innerHTML;
                window.history.pushState({}, '', url);
            }
        } catch (error) {
            console.error('Gagal mengambil konten:', error);
        } finally {
            tableWrapper.style.opacity = '1';
        }
    };

    const handleFilterChange = () => {
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) pageInput.value = '1';
        const params = new URLSearchParams(new FormData(form));
        const url = `${form.action}?${params.toString()}`;
        fetchContent(url);
    };

    const debouncedFilter = debounce(handleFilterChange, 400);

// PERUBAHAN 3: Mengganti event listener agar lebih robust
form.addEventListener('change', handleFilterChange);
form.querySelector('#searchInput')?.addEventListener('input', debouncedFilter);
    form.querySelector('#searchInput')?.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
    
    document.body.addEventListener('click', e => {
        const link = e.target.closest('#tableWrap .pagination a');
        if (link) { 
            e.preventDefault(); 
            fetchContent(link.href); 
        }
    });
});
