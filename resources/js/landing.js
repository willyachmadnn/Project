// resources/js/landing.js
(function () {
  const FORM_SEL = '#filterForm';
  const WRAP_SEL = '#tableWrap';
  const BODY_SEL = '#agendaBody';

  let ctrl = null;
  let typingTimer = null;

  const form  = () => document.querySelector(FORM_SEL);
  const wrap  = () => document.querySelector(WRAP_SEL);
  const tbody = () => document.querySelector(BODY_SEL);

  // Hapus ringkasan kecil bawaan pagination dari links()
  function removeLinksSummary(ctx){
    const root = ctx || document;
    try {
      root.querySelectorAll('#tableWrap .shrink-0 p.text-sm').forEach(el => el.remove());
    } catch (_) {}
  }

  // Helpers
  function setPage(n){
    const f = form(); if (!f) return;
    let p = f.querySelector('input[name="page"]');
    if (!p) { p = document.createElement('input'); p.type='hidden'; p.name='page'; f.appendChild(p); }
    p.value = String(n || 1);
  }

  function normalizeStatus(val){
    val = (val || '').toString().toLowerCase().trim();
    if (['selesai','berakhir','finished','done'].includes(val)) return 'berakhir';
    if (['berlangsung','ongoing'].includes(val))                 return 'berlangsung';
    if (['menunggu','pending'].includes(val))                    return 'menunggu';
    return '';
  }

  // ✅ MISSING sebelumnya — dipakai urlForServer()
  function buildServerParams(){
    const f = form();
    const fd = new FormData(f || undefined);

    // default perPage
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp);
    fd.delete('per_page');

    // search client-side only (jangan dikirim ke server di event ketik)
    fd.delete('q'); fd.delete('search');

    // kosong? hapus
    if (!fd.get('status')) fd.delete('status');

    return fd;
  }

  function urlForServer(){
    const f = form(); if (!f) return location.href;
    const fd = buildServerParams();
    const qs = new URLSearchParams(fd).toString();
    return f.action + (qs ? '?' + qs : '');
  }

  function urlForState(){
    const f = form(); if (!f) return location.href;
    const fd = new FormData(f);
    const pp = fd.get('per_page') || fd.get('perPage') || '10';
    fd.set('perPage', pp); fd.delete('per_page');

    const qVal = f.querySelector('#qInput')?.value || '';
    if (qVal.trim() !== '') { fd.set('q', qVal); fd.set('search', qVal); }
    else { fd.delete('q'); fd.delete('search'); }

    if (!fd.get('status')) fd.delete('status');

    const qs = new URLSearchParams(fd).toString();
    return f.action + (qs ? '?' + qs : '');
  }

  function updateUrlState(){
    const f = form(); if (!f) return;
    history.replaceState({}, '', urlForState());
  }

  // Client-side live filter
  function liveFilter(term, statusVal){
    const body = tbody(); if (!body) return;
    const q = (term || '').toString().trim().toLowerCase();
    const parts = q ? q.split(/\s+/) : [];
    const sNorm = normalizeStatus(statusVal);

    [...body.querySelectorAll('tr')].forEach(tr => {
      const idx = (tr.dataset.index || '').toLowerCase();
      const rowStatus = normalizeStatus(tr.dataset.status || '');
      const matchSearch = parts.length === 0 ? true : parts.every(p => idx.includes(p));
      const matchStatus = !sNorm ? true : rowStatus === sNorm;
      tr.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
  }

  // AJAX replace table+pagination — gunakan URL asli & update address bar
  function ajaxLoad(fetchUrl){
    const container = wrap(); if (!container) return;

    if (ctrl) ctrl.abort();
    ctrl = new AbortController();

    container.classList.add('opacity-60');

    fetch(fetchUrl, {
      signal: ctrl.signal,
      credentials: 'same-origin',
      cache: 'no-store',
      headers: { 'Accept': 'text/html' }
    })
    .then(r => r.text())
    .then(html => {
      if (ctrl.signal.aborted) return;

      const doc = new DOMParser().parseFromString(html, 'text/html');
      const nextWrap = doc.querySelector(WRAP_SEL);
      if (!nextWrap) return;

      container.replaceWith(nextWrap);

      removeLinksSummary(document);
      bindInsideWrap(); // re-bind link pagination yang baru

      // Re-apply filter lokal (kalau ada nilai)
      const qVal = form()?.querySelector('#qInput')?.value || '';
      const sVal = form()?.querySelector('#statusSelect')?.value || '';
      if (qVal || sVal) liveFilter(qVal, sVal);

      // Sinkronkan URL address bar
      history.replaceState({}, '', fetchUrl);
    })
    .catch(e => { if (e.name !== 'AbortError') console.error(e); })
    .finally(() => {
      const nw = wrap();
      if (nw) nw.classList.remove('opacity-60');
    });
  }

function bindInsideWrap(){
  const container = document.querySelector('#tableWrap');
  if (!container) return;

  container.querySelectorAll('a[href*="page="]').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const url = new URL(a.getAttribute('href'), window.location.href);
      ajaxLoad(url.toString());
    }, { once: true });
  });
}


  // Bind controls (tetap seperti punyamu)
  function bind(){
    const f = form(); if (!f) return;

    removeLinksSummary(document);

    // SHOW
    const perSel = f.querySelector('#perPageSelect');
    if (perSel){
      perSel.addEventListener('change', () => {
        const perMirror = f.querySelector('input[name="perPage"]');
        if (perMirror) perMirror.value = perSel.value;
        setPage(1);

        const q = f.querySelector('#qInput')?.value || '';
        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(q, s);

        ajaxLoad(urlForServer());
      });
    }

    // STATUS
    const statusSel = f.querySelector('#statusSelect');
    if (statusSel){
      statusSel.addEventListener('change', () => {
        setPage(1);
        const q = f.querySelector('#qInput')?.value || '';
        liveFilter(q, statusSel.value);
        ajaxLoad(urlForServer());
      });
    }

    // SEARCH — client-side only
    const qInput  = f.querySelector('#qInput');
    const qMirror = f.querySelector('#qMirror'); // hanya untuk keep state
    if (qInput){
      qInput.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

      qInput.addEventListener('input', () => {
        if (qMirror) qMirror.value = qInput.value;
        setPage(1);

        const s = f.querySelector('#statusSelect')?.value || '';
        liveFilter(qInput.value, s);

        updateUrlState();
        // debounce ke server (opsional):
        // clearTimeout(typingTimer);
        // typingTimer = setTimeout(() => ajaxLoad(urlForServer()), 400);
      });
    }

    // Pagination awal
    bindInsideWrap();

    // Apply filter lokal sekali saat halaman diload (jika ada q/status di URL)
    const initQ = f.querySelector('#qInput')?.value || '';
    const initS = f.querySelector('#statusSelect')?.value || '';
    if (initQ || initS) liveFilter(initQ, initS);

    updateUrlState();
  }

  document.addEventListener('DOMContentLoaded', bind);
})();


// --- Tambahan layout & hero text (ringan, non-intrusif) ---
(function(){
  // Fallback svh (device lama)
  function setSVH(){ document.documentElement.style.setProperty('--svh', window.innerHeight + 'px'); }
  setSVH(); window.addEventListener('resize', setSVH);

  // Opsional: efek "Selamat datang" -> teks portal (aktif jika ?welcome=1)
  const params = new URLSearchParams(location.search);
  if (params.get('welcome') === '1') {
    const h = document.getElementById('heroTitle');
    const d = document.getElementById('heroDesc');
    if (h) { h.dataset.final = h.textContent; h.textContent = 'Selamat datang'; h.classList.add('hero-swap'); }
    if (d) { d.dataset.final = d.textContent; d.textContent = ''; }

    setTimeout(() => {
      if (h && h.dataset.final){ h.textContent = h.dataset.final; h.classList.add('hero-swap'); }
      if (d && d.dataset.final){ d.textContent = d.dataset.final; d.classList.add('hero-swap'); }
    }, 900);
  }
})();
