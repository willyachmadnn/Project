// File: resources/js/landing.js
import '../css/landing.css';

document.addEventListener('DOMContentLoaded', () => {
  // ==================================================================
  // ==================================================================
  
  const heroTitle = document.getElementById('hero-title');
  const heroDescription = document.getElementById('hero-description');
  const typewriterLines = document.querySelectorAll('.typewriter-line');
  const heroSpacer = document.querySelector('.hero-spacer');
  
  let isInitialLoad = true;
  let animationInProgress = false;
  let typewriterTimeout;
  
  // Typewriter animation controller
  const startTypewriterAnimation = (delay = 2000) => {
    if (animationInProgress) return;
    animationInProgress = true;
    
    // Clear any existing timeouts
    if (typewriterTimeout) {
      clearTimeout(typewriterTimeout);
    }
    
    // Reset all animations
    resetTypewriterAnimations();
    
    typewriterTimeout = setTimeout(() => {
      // Start title typewriter animation
      if (heroTitle) {
        heroTitle.style.opacity = '1';
        heroTitle.classList.add('typing');
        
        // Start description animation simultaneously with title
        if (heroDescription) {
          heroDescription.classList.add('show');
          
          // Animate all lines simultaneously
          typewriterLines.forEach((line, index) => {
            line.classList.add('typing');
          });
          
          // Mark animation as complete after animation duration
          setTimeout(() => {
            animationInProgress = false;
          }, 1000); // Match CSS animation duration
        }
      }
    }, delay);
  };
  
  // Reset all typewriter animations
  const resetTypewriterAnimations = () => {
    if (heroTitle) {
      heroTitle.style.opacity = '0';
      heroTitle.classList.remove('typing');
    }
    
    if (heroDescription) {
      heroDescription.classList.remove('show');
    }
    
    typewriterLines.forEach(line => {
      line.classList.remove('typing');
    });
  };
  
  // Restart animation function for click interactions
  const restartTypewriterAnimation = () => {
    if (typewriterTimeout) {
      clearTimeout(typewriterTimeout);
    }
    animationInProgress = false;
    startTypewriterAnimation(500); // Shorter delay for restarts
  };
  
  // Initial animation with 2-second delay
  startTypewriterAnimation(2000);
  
  // Scroll Animation Observer
  const scrollAnimationObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in');
        // Unobserve after animation to prevent re-triggering
        scrollAnimationObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });
  
  // Function to initialize scroll animations
  const initScrollAnimations = () => {
    // Add scroll-animate class to main sections
    const elementsToAnimate = [
      '#kpi-cards',
      '.content-body .table-container',
      '.content-body .card',
      '.content-body .btn',
      '.content-body .agenda-item'
    ];
    
    elementsToAnimate.forEach((selector, index) => {
      const elements = document.querySelectorAll(selector);
      elements.forEach((element, elementIndex) => {
        element.classList.add('scroll-animate');
        if (elementIndex > 0) {
          element.classList.add(`scroll-animate-delay-${Math.min(elementIndex, 3)}`);
        }
        scrollAnimationObserver.observe(element);
      });
    });
  };
  
  // Initialize scroll animations on page load
  initScrollAnimations();
  
  // Intersection Observer for scroll-based animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        if (!isInitialLoad) {
          startTypewriterAnimation(500);
        }
      } else {
        if (!isInitialLoad) {
          resetTypewriterAnimations();
          animationInProgress = false;
        }
        isInitialLoad = false;
      }
    });
  }, { root: null, threshold: 0.1, rootMargin: '100px' });
  
  if (heroSpacer) observer.observe(heroSpacer);
  
  // Click interaction handlers for navigation
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href*="#"], a[href="/"], a[href*="landing"]');
    if (link) {
      // Restart animation after navigation
      setTimeout(() => {
        if (heroSpacer && heroSpacer.getBoundingClientRect().top < window.innerHeight) {
          restartTypewriterAnimation();
        }
      }, 300);
    }
  });
  
  // Handle page visibility changes (when user returns to tab)
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden && heroSpacer && heroSpacer.getBoundingClientRect().top < window.innerHeight) {
      setTimeout(() => {
        restartTypewriterAnimation();
      }, 500);
    }
  });

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
