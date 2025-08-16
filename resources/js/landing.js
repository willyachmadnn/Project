// File: resources/js/landing.js
import '../css/landing.css';

document.addEventListener('DOMContentLoaded', () => {
  // ==================================================================
  // BAGIAN 1: LOGIKA ANIMASI DUA ARAH (dipertahankan)
  // ==================================================================
  const REFLOW_DELAY = 100;
  const STAGGER_DELAY = 200;

  const animatedItems = document.querySelectorAll('.animate-item');
  const heroSpacer = document.querySelector('.hero-spacer');
  let isInitialLoad = true;

  const runAnimation = (direction) => {
    animatedItems.forEach(item => {
      item.classList.remove('is-visible', 'slide-from-top', 'slide-from-bottom');
      item.classList.add(direction);
    });

    setTimeout(() => {
      animatedItems.forEach((item, index) => {
        setTimeout(() => { item.classList.add('is-visible'); }, index * STAGGER_DELAY);
      });
    }, REFLOW_DELAY);
  };

  const resetAnimation = () => {
    animatedItems.forEach(item => item.classList.remove('is-visible'));
  };

  runAnimation('slide-from-top');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        if (!isInitialLoad) runAnimation('slide-from-bottom');
      } else {
        resetAnimation();
        isInitialLoad = false;
      }
    });
  }, { root: null, threshold: 0.1 });

  if (heroSpacer) observer.observe(heroSpacer);

  // ==================================================================
  // BAGIAN 2: AJAX FILTER & PAGINATION (diperbaiki)
  // ==================================================================
  const form = document.getElementById('filterForm');

  const debounce = (fn, delay) => {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn.apply(null, args), delay); };
  };

  const fetchContent = async (url) => {
    const tableWrapper = document.getElementById('tableWrap');
    if (!tableWrapper) return;

    // Simpan posisi scroll & kunci tinggi agar tidak melompat
    const keepX = window.scrollX;
    const keepY = window.scrollY;
    const minH = tableWrapper.offsetHeight;
    tableWrapper.style.minHeight = minH + 'px';
    
    // Hanya ubah opacity pada tabel, bukan pagination
    const tableElement = tableWrapper.querySelector('table');
    if (tableElement) {
      tableElement.style.opacity = '0.5';
      tableElement.style.transition = 'opacity 0.2s ease-in-out';
    }

    try {
      const resp = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
      });
      const html = await resp.text();
      const doc  = new DOMParser().parseFromString(html, 'text/html');
      const newWrap = doc.getElementById('tableWrap');

      if (newWrap) {
        // Perbarui konten dengan animasi yang lebih halus
        tableWrapper.innerHTML = newWrap.innerHTML;
        
        // Tambahkan kelas untuk transisi halus pada tabel baru
        const newTable = tableWrapper.querySelector('table');
        if (newTable) {
          newTable.style.transition = 'opacity 0.2s ease-in-out';
        }
      } else {
        // fallback bila server kirim partial
        tableWrapper.innerHTML = html;
      }

      // penting: URL tetap di halaman sumber (landing vs admin)
      window.history.pushState({}, '', url);
      window.scrollTo(keepX, keepY);
    } catch (e) {
      console.error('AJAX error:', e);
    } finally {
      // Kembalikan opacity tabel
      const newTable = tableWrapper.querySelector('table');
      if (newTable) {
        newTable.style.opacity = '1';
      }
      
      // Pertahankan minHeight untuk beberapa saat sebelum menghapusnya
      setTimeout(() => {
        tableWrapper.style.minHeight = '';
      }, 300);
    }
  };

  // Handler filter (jalan hanya jika form ada)
  const handleFilterChange = () => {
    if (!form) return;
    const pageInput = form.querySelector('input[name="page"]');
    if (pageInput) pageInput.value = '1'; // reset ke page 1 saat cari/filter
    const params = new URLSearchParams(new FormData(form));
    // PENTING: gunakan action dari form → admin akan ke /agenda, tamu ke /
    fetchContent(`${form.action}?${params.toString()}`);
  };
  const debouncedFilter = debounce(handleFilterChange, 400);

  if (form) {
    form.addEventListener('change', handleFilterChange);
    const search = form.querySelector('#searchInput');
    if (search) {
      search.addEventListener('input', debouncedFilter);
      search.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
    }
  }

  // Intercept klik pagination — lebih toleran; tak butuh class .pagination
  document.body.addEventListener('click', (e) => {
    const link = e.target.closest('#tableWrap a[href*="page="]');
    if (link) {
      e.preventDefault();
      fetchContent(link.href);
    }
  });

  // Back/Forward browser tetap AJAX & posisi scroll tetap
  window.addEventListener('popstate', () => {
    const keepX = window.scrollX, keepY = window.scrollY;
    fetchContent(location.href).then(() => window.scrollTo(keepX, keepY));
  });
});
