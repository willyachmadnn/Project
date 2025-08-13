@props(['title' => 'Agenda Pemerintah Kabupaten Mojokerto'])

<!doctype html>
<html lang="id" class="h-full overflow-x-hidden">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
</head>

@php
  $isLogin   = request()->routeIs('login');
  $isLanding = request()->routeIs('landing');   // sticky & footer hanya di landing
  $admin     = Auth::guard('admin');
  $adminUser = $admin->user();
  $adminName = $admin->check()
      ? ($adminUser->nama_admin ?? $adminUser->name ?? $adminUser->username ?? 'User')
      : null;
  $adminOpd  = $admin->check() ? ($adminUser->opd_admin ?? null) : null;
@endphp

<body class="min-h-screen overflow-x-hidden bg-white text-slate-900 antialiased">

  {{-- ===== HEADER / NAV ===== --}}
  <header id="siteHeader"
          data-scrolled="0"
          class="{{ $isLanding ? 'fixed inset-x-0 top-0 z-50 transition-all duration-300' : 'relative bg-[#ac1616] shadow-sm' }}">
    <style>
      /* ===== Warna navbar ===== */
      html { overflow-y: scroll; scrollbar-gutter: stable; }
      @if ($isLanding)
        /* Transparan di top, #ac1616 saat discroll */
        #siteHeader[data-scrolled="0"] { background: transparent; }
        #siteHeader[data-scrolled="1"]{
          background:#ac1616;
          backdrop-filter:saturate(180%) blur(10px);
          box-shadow:0 1px 2px rgba(0,0,0,.08);
        }
      @else
        /* Non-landing: selalu #ac1616 */
        #siteHeader{ background:#ac1616; box-shadow:0 1px 2px rgba(0,0,0,.08); }
      @endif

      /* ===== Login button states ===== */
      @if ($isLanding)
        /* TOP (header transparan): putih semi-transparan tipis, tanpa blur berat */
        #siteHeader[data-scrolled="0"] .login-btn{
          color:#fff;
          background:rgba(255,255,255,.22);
          border:1px solid rgba(255,255,255,.35);
          box-shadow:0 1px 2px rgba(0,0,0,.10);
        }
        /* SCROLL: putih solid + teks merah */
        #siteHeader[data-scrolled="1"] .login-btn{
          color:#ac1616; background:#fff; border:1px solid transparent;
          box-shadow:0 1px 2px rgba(0,0,0,.08);
        }
      @else
        /* Non-landing: selalu solid */
        #siteHeader .login-btn{
          color:#ac1616; background:#fff; border:1px solid transparent;
          box-shadow:0 1px 2px rgba(0,0,0,.08);
        }
      @endif

      /* ===== Profile icon (SVG sebagai logo, BUKAN background) ===== */
      @if ($isLanding)
        /* TOP: bubble putih semi-transparan, ikon putih (merah “hilang”) */
        #siteHeader[data-scrolled="0"] .profile-btn{
          background:rgba(255,255,255,.22);
          backdrop-filter:saturate(180%) blur(3px);
          -webkit-backdrop-filter:saturate(180%) blur(3px);
          border-color:rgba(255,255,255,.40);   /* karena pakai class `border` */
          color:#fff;                            /* SVG ikut currentColor */
        }
        /* SCROLL: bubble putih solid, ikon marun */
        #siteHeader[data-scrolled="1"] .profile-btn{
          background:#fff;
          border-color:rgba(0,0,0,.10);
          color:#ac1616;
        }
      @else
        /* Non-landing: selalu bubble putih + ikon marun */
        #siteHeader .profile-btn{
          background:#fff;
          border-color:rgba(0,0,0,.10);
          color:#ac1616;
        }
      @endif

      /* efek kecil bersama */
      #siteHeader .login-btn,
      #siteHeader .profile-btn{ transition:all .15s ease; box-shadow:0 1px 2px rgba(0,0,0,.08); }
      #siteHeader .login-btn:hover,
      #siteHeader .profile-btn:hover{ transform:translateY(-1px); }
      #siteHeader .login-btn:active,
      #siteHeader .profile-btn:active{ transform:translateY(0); }
    </style>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <nav class="flex h-16 items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('landing') }}" class="flex items-center gap-3 shrink-0" aria-label="LogoMokad">
          <img src="https://mojokertokab.go.id/assets/img/logo/mokercitybrandingputih-min.png"
               alt="E-Agenda Kabupaten Mojokerto" class="h-10 w-auto object-contain" loading="eager" fetchpriority="high">
          <span class="sr-only">E-Agenda Kab. Mojokerto</span>
        </a>

        {{-- Kanan --}}
        @if ($admin->check())
          <details class="relative group">
            <summary class="list-none cursor-pointer select-none" aria-label="Buka menu profil">
              {{-- gunakan `border` (bukan ring-1) agar warna border bisa dikontrol via CSS di atas --}}
              <span class="profile-btn flex h-10 w-10 items-center justify-center rounded-full border shadow-sm">
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"/>
                </svg>
              </span>
            </summary>

            <div class="absolute right-0 mt-2 w-56 rounded-md border border-gray-200 bg-white shadow-lg">
              <div class="px-4 py-3 text-sm">
                <p class="font-medium text-gray-900">{{ $adminName }}</p>
                <p class="text-gray-500">{{ $adminOpd ? "OPD $adminOpd" : 'Admin' }}</p>
              </div>

              <div class="py-1">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Dashboard</a>
              </div>

              <div class="border-t border-gray-200"></div>

              <div class="py-1">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Sign out</button>
                </form>
              </div>
            </div>
          </details>
        @else
          @if (Route::has('login') && !$isLogin)
            {{-- Login: semi transparan di top (landing), solid saat scroll/non-landing; teks tebal --}}
            <a href="{{ route('login') }}"
               class="login-btn relative z-[60] pointer-events-auto inline-flex items-center rounded-md px-5 py-2 text-sm font-bold
                      focus:outline-none focus-visible:ring-1 focus-visible:ring-white
                      focus-visible:ring-offset-1 focus-visible:ring-offset-[#ac1616]">
              Login
            </a>
          @endif
        @endif
      </nav>
    </div>

    {{-- Script sticky hanya di landing --}}
    @if ($isLanding)
      <script>
        (function(){
          const header = document.getElementById('siteHeader'); if(!header) return;
          const t = 10;
          const onScroll = () => { header.dataset.scrolled = (window.scrollY||0) > t ? '1' : '0'; };
          window.addEventListener('scroll', onScroll, { passive: true });
          window.addEventListener('load', onScroll, { once: true });
        })();
      </script>
    @endif
  </header>

  {{-- ===== KONTEN ===== --}}
  <main id="app" class="{{ $isLanding ? 'min-h-[calc(100vh-4rem)]' : 'min-h-screen' }} w-full">
    {{ $slot }}
  </main>

  {{-- ===== FOOTER: hanya di landing ===== --}}
  @if ($isLanding)
    <footer class="mt-12 border-t border-white/10 bg-gradient-to-r from-[#ac1616] to-[#7e0f0f] text-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-sm text-white flex items-center gap-2">
        &copy; {{ now()->year }}
        <div class="flex items-center gap-1">
          <img src="https://mojokertokab.go.id/storage/tenantdiskominfo/app/gambar/config/site-logo-1686395812.png"
               alt="KOMINFOMJK" class="h-6 w-auto object-contain">
          <a href="https://diskominfo.mojokertokab.go.id/"
             class="hover:underline font-bold"
             target="_blank" rel="noopener noreferrer">
            DISKOMINFO
          </a>
          <span>Kab. Mojokerto</span>
        </div>
      </div>
    </footer>
  @endif

  @stack('scripts')
</body>
</html>
