{{-- resources/views/components/layout.blade.php --}}
@props(['title' => 'Agenda Pemerintah Kabupaten Mojokerto'])

<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

  <!-- NAVBAR: logo saja -->
 {{-- NAVBAR --}}
@php
    $isLogin   = request()->routeIs('login');
    $admin     = Auth::guard('admin');
    $adminUser = $admin->user();

    // Nama profil: utamakan 'nama_admin', fallback ke 'name'/'username'
    $adminName = $admin->check()
        ? ($adminUser->nama_admin ?? $adminUser->name ?? $adminUser->username ?? 'User')
        : null;

    // OPD profil: langsung dari kolom 'opd_admin' (tabel admins)
    $adminOpd  = $admin->check() ? ($adminUser->opd_admin ?? null) : null;
@endphp


<header id="siteHeader"
        class="fixed inset-x-0 top-0 z-70 bg-transparent transition-all duration-500 will-change-auto">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <nav class="flex h-16 items-center justify-between">
      {{-- LOGO --}}
      <a href="{{ route('landing') }}" class="flex items-center gap-3 shrink-0" aria-label="Mojokerto">
        <img
          src="https://data.mojokertokab.go.id/onedata/global_assets/tagline_kabupaten_mojokerto.png"
          alt="E-Agenda Kabupaten Mojokerto"
          class="h-10 w-auto object-contain"
          loading="eager" fetchpriority="high">
        <span class="sr-only">E-Agenda Kab. Mojokerto</span>
      </a>

      {{-- KANAN: Login (guest) / Profil (auth) --}}
      @php
        $isLogin   = request()->routeIs('login');
        $admin     = Auth::guard('admin');
        $adminName = $admin->check()
            ? ($admin->user()->nama_admin ?? $admin->user()->name ?? 'User')
            : null;
      @endphp

      @if ($admin->check())
        {{-- === PROFIL DROPDOWN === --}}
        <details class="relative group">
          <summary class="list-none flex items-center gap-3 cursor-pointer select-none">
            <span class="hidden sm:inline text-sm">Profil</span>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full ring-1 ring-black/10">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" />
              </svg>
            </span>
          </summary>

          <div class="absolute right-0 mt-2 w-56 rounded-md border border-gray-200 bg-white shadow-lg">
            <div class="px-4 py-3 text-sm">
              <p class="font-medium text-gray-900">{{ $adminName }}</p>
              @if($adminOpd)
                <p class="text-gray-500">OPD {{ $adminOpd }}</p>
              @else
                <p class="text-gray-500">Admin</p>
              @endif
            </div>
            <div class="border-t border-gray-200">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">
                  Sign out
                </button>
              </form>
            </div>
          </div>
        </details>
      @else
        {{-- Guest: sembunyikan tombol Login kalau sedang di halaman login --}}
        @unless($isLogin)
          <a href="{{ route('login') }}"
             class="inline-flex items-center rounded-md px-5 py-2 text-sm font-semibold text-white
                    bg-red-800 hover:bg-red-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2">
            Login
          </a>
        @endunless
      @endif
    </nav>
  </div>
</header>

  <!-- SLOT KONTEN -->
  <main id="app" class="min-h-[calc(100vh-4rem)]">
    {{ $slot }}
  </main>

  <footer class="border-t mt-12">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 text-sm text-slate-900 flex items-center gap-2">
    &copy; {{ now()->year }}
    <a href="https://diskominfo.mojokertokab.go.id/"
       class="flex items-center gap-1 hover:underline"
       target="_blank" rel="noopener noreferrer">
      <span>by</span>
      <img src="https://mojokertokab.go.id/storage/tenantdiskominfo/app/gambar/config/site-logo-1686395812.png"
           alt="Kominfo"
           class="h-6 w-auto object-contain">
      <span>DISKOMINFO Kab. Mojokerto</span>
    </a>
  </div>
</footer>


  <!-- BEHAVIOR: > threshold = maroon; <= threshold = transparan -->
<script>
  (function () {
    const header = document.getElementById('siteHeader');
    if (!header) return;

    const setTransparent = () => {
      header.classList.remove('bg-[#ffffff]','shadow-sm','backdrop-blur');
      header.classList.add('bg-transparent');
    };
    const setMaroon = () => {
      header.classList.add('bg-[#ffffff]','shadow-sm','backdrop-blur');
      header.classList.remove('bg-transparent');
    };

    const threshold = 10;
    const onScroll = () => {
      const y = window.scrollY || 0;
      if (y <= threshold) setTransparent();
      else setMaroon(); // scroll turun & naik tetap maroon, hanya top yg transparan
    };

    document.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('load', onScroll, { once: true });
  })();
</script>


  @stack('scripts')
</body>
</html>
