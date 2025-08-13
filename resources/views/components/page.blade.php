@props([
  'pendingAgendasCount' => 0,
  'ongoingAgendasCount' => 0,
  'finishedAgendasCount' => 0,
  'kpiActive' => null,
])

<section id="kpis" class="kpi-section">
  <style>
    /* ---- Layout ---- */
    .kpi-section{ position:relative; z-index:20; padding:1.5rem 1rem; margin-top:1.5rem; }
    @media (min-width:768px){ .kpi-section{ margin-top:-2.5rem; } }
    @media (min-width:1024px){ .kpi-section{ margin-top:-3.5rem; } }
    .kpi-grid{
      max-width:80rem; margin:0 auto; display:grid; gap:1rem;
      grid-template-columns:1fr;
    }
    @media (min-width:768px){ .kpi-grid{ grid-template-columns:repeat(3,1fr); gap:1.25rem; } }

    /* ---- Card non-klik (hanya efek hover) ---- */
    .kpi-card{
      --accent:#e5e7eb; --number:#111827;
      position:relative; display:flex; align-items:center; gap:1rem;
      background:#fff; color:#111827;
      border-radius:12px; border:1px solid rgba(2,6,23,.08);
      box-shadow:0 10px 24px rgba(0,0,0,.06);
      padding:18px 20px;
      transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
      cursor:default; user-select:none;
    }
    .kpi-card:hover{ transform:translateY(-2px); box-shadow:0 16px 30px rgba(0,0,0,.10); border-color:rgba(2,6,23,.12); }

    /* Aksen kiri */
    .kpi-side{ position:absolute; left:0; top:0; bottom:0; width:8px; background:var(--accent); border-radius:12px 0 0 12px; transition:width .15s ease; }

    .kpi-title{ font-size:.875rem; font-weight:600; color:#334155; margin:0; }
    .kpi-value{ font-size:1.875rem; font-weight:800; color:var(--number); margin:.25rem 0 0 0; line-height:1; }

    /* Tema warna */
    .is-pending  { --accent:#1992a1; --number:#0ea5a5; }
    .is-ongoing  { --accent:#166534; --number:#16a34a; }
    .is-finished { --accent:#7f1d1d; --number:#dc2626; }

    /* Active state (menu aktif) */
    .kpi-card.is-active{ outline:2px solid var(--accent); box-shadow:0 18px 32px rgba(0,0,0,.14); }
    .kpi-card.is-active .kpi-side{ width:12px; }
  </style>

  <div class="kpi-grid" role="list">
    <article class="kpi-card is-pending {{ $kpiActive==='pending' ? 'is-active' : '' }}" role="listitem" aria-selected="{{ $kpiActive==='pending' ? 'true' : 'false' }}">
      <div class="kpi-side"></div>
      <div class="kpi-body">
        <h3 class="kpi-title">Agenda Menunggu</h3>
        <p class="kpi-value">{{ $pendingAgendasCount ?? 0 }}</p>
      </div>
    </article>

    <article class="kpi-card is-ongoing {{ $kpiActive==='ongoing' ? 'is-active' : '' }}" role="listitem" aria-selected="{{ $kpiActive==='ongoing' ? 'true' : 'false' }}">
      <div class="kpi-side"></div>
      <div class="kpi-body">
        <h3 class="kpi-title">Agenda Berlangsung</h3>
        <p class="kpi-value">{{ $ongoingAgendasCount ?? 0 }}</p>
      </div>
    </article>

    <article class="kpi-card is-finished {{ $kpiActive==='finished' ? 'is-active' : '' }}" role="listitem" aria-selected="{{ $kpiActive==='finished' ? 'true' : 'false' }}">
      <div class="kpi-side"></div>
      <div class="kpi-body">
        <h3 class="kpi-title">Agenda Berakhir</h3>
        <p class="kpi-value">{{ $finishedAgendasCount ?? 0 }}</p>
      </div>
    </article>
  </div>
</section>
