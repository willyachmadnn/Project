@props(['title' => 'Agenda Pemerintah Kabupaten Mojokerto'])

<!doctype html>
<html lang="id-ID" class="h-full overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Manajemen Agenda Pemerintah Kabupaten Mojokerto">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    

    
    <title>{{ $title }}</title>

    {{-- Preload criticazl resources --}}
    <link rel="preconnect" href="https://rsms.me">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://rsms.me">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    {{-- Preload critical assets --}}
    <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style">
    <link rel="preload" href="{{ Vite::asset('resources/js/app.js') }}" as="script">
    <link rel="preload" href="{{ asset('img/hero-bg.jpg') }}" as="image" fetchpriority="high">
    
    {{-- Critical CSS first --}}
    @vite(['resources/css/app.css', 'resources/css/responsive-mobile.css', 'resources/js/app.js'])
    

    
    {{-- Non-critical CSS with preload --}}
    <link rel="preload" href="https://rsms.me/inter/inter.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://rsms.me/inter/inter.css"></noscript>
    
    {{-- Alpine.js with defer for better performance --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- jQuery for PDF functionality --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    {{-- SweetAlert2 for beautiful alerts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Animate.css for smooth animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    {{-- QR Code and PDF Libraries --}}
     <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
     
     {{-- Font Awesome for icons --}}
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    {{-- Performance optimizations --}}
    <meta name="theme-color" content="#ac1616">
    <link rel="manifest" href="/manifest.json">
</head>

@php
    $isLogin = request()->routeIs('login');
    $isLanding = request()->routeIs('landing'); // sticky & footer hanya di landing
    $admin = Auth::guard('admin');
    $adminUser = $admin->user();
    $adminName = $admin->check()
        ? $adminUser->nama_admin ?? ($adminUser->name ?? ($adminUser->username ?? 'User'))
        : null;
    $adminOpd = $admin->check() ? $adminUser->opd->nama_opd ?? null : null;
@endphp

<body class="min-h-screen overflow-x-hidden bg-white text-slate-900 antialiased">

    {{-- ===== HEADER / NAV ===== --}}
    <header id="siteHeader" data-scrolled="0"
        class="{{ $isLanding ? 'fixed inset-x-0 top-0 z-50 transition-all duration-300' : 'fixed inset-x-0 top-0 z-50 bg-[#ac1616] shadow-sm' }}">
        <style>
            /* ===== Warna navbar ===== */
            html {
                overflow-y: scroll;
                scrollbar-gutter: stable;
            }

            @if ($isLanding)
                /* Transparan di top, #ac1616 saat discroll */
                #siteHeader[data-scrolled="0"] {
                    background: transparent;
                }

                #siteHeader[data-scrolled="1"] {
                    background: #ac1616;
                    backdrop-filter: saturate(180%) blur(10px);
                    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
                }
            @else
                /* Non-landing: selalu #ac1616 */
                #siteHeader {
                    background: #ac1616;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
                }
            @endif

            /* ===== Login button states ===== */
            @if ($isLanding)
                /* TOP (header transparan): putih semi-transparan tipis, tanpa blur berat */
                #siteHeader[data-scrolled="0"] .login-btn {
                    color: #fff;
                    background: rgba(255, 255, 255, .22);
                    border: 1px solid rgba(255, 255, 255, .35);
                    box-shadow: 0 1px 2px rgba(0, 0, 0, .10);
                }

                /* SCROLL: putih solid + teks merah */
                #siteHeader[data-scrolled="1"] .login-btn {
                    color: #ac1616;
                    background: #fff;
                    border: 1px solid transparent;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
                }
            @else
                /* Non-landing: selalu solid */
                #siteHeader .login-btn {
                    color: #ac1616;
                    background: #fff;
                    border: 1px solid transparent;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
                }
            @endif

            /* ===== Profile icon (SVG sebagai logo, BUKAN background) ===== */
            @if ($isLanding)
                /* TOP: bubble putih semi-transparan, ikon putih (merah “hilang”) */
                #siteHeader[data-scrolled="0"] .profile-btn {
                    background: rgba(255, 255, 255, .22);
                    backdrop-filter: saturate(180%) blur(3px);
                    -webkit-backdrop-filter: saturate(180%) blur(3px);
                    border-color: rgba(255, 255, 255, .40);
                    /* karena pakai class `border` */
                    color: #fff;
                    /* SVG ikut currentColor */
                }

                /* SCROLL: bubble putih solid, ikon marun */
                #siteHeader[data-scrolled="1"] .profile-btn {
                    background: #fff;
                    border-color: rgba(0, 0, 0, .10);
                    color: #ac1616;
                }
            @else
                /* Non-landing: selalu bubble putih + ikon marun */
                #siteHeader .profile-btn {
                    background: #fff;
                    border-color: rgba(0, 0, 0, .10);
                    color: #ac1616;
                }
            @endif

            /* efek kecil bersama */
            #siteHeader .login-btn,
            #siteHeader .profile-btn {
                transition: all .15s ease;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
            }

            #siteHeader .login-btn:hover,
            #siteHeader .profile-btn:hover {
                transform: translateY(-1px);
            }

            #siteHeader .login-btn:active,
            #siteHeader .profile-btn:active {
                transform: translateY(0);
            }
        </style>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="flex h-16 items-center justify-between">
                {{-- Logo --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-3 shrink-0" aria-label="LogoMokad">
                    <img src="https://mojokertokab.go.id/assets/img/logo/mokercitybrandingputih-min.png"
                        alt="E-Agenda Kabupaten Mojokerto" class="h-10 w-auto object-contain" loading="eager"
                        fetchpriority="high">
                    <span class="sr-only">E-Agenda Kab. Mojokerto</span>
                </a>

                {{-- Kanan --}}
                @if ($admin->check())
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="profile-btn flex h-10 w-10 items-center justify-center rounded-full border shadow-sm" aria-label="Buka menu profil">
                             <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor"
                                  stroke-width="1.5" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>
                         </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-md border border-gray-200 bg-white shadow-lg z-50"
                             style="display: none;">
                            <div class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $adminName }}</p>
                                <p class="text-gray-500">{{ $adminOpd ? "OPD $adminOpd" : 'Admin' }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 hover:border-l-4 hover:border-blue-500 transition-all duration-200">Dashboard</a>
                            </div>

                            <div class="border-t border-gray-200"></div>

                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 hover:border-l-4 hover:border-red-500 transition-all duration-200">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
                (function() {
                    const header = document.getElementById('siteHeader');
                    if (!header) return;
                    const t = 10;
                    const onScroll = () => {
                        header.dataset.scrolled = (window.scrollY || 0) > t ? '1' : '0';
                    };
                    window.addEventListener('scroll', onScroll, {
                        passive: true
                    });
                    window.addEventListener('load', onScroll, {
                        once: true
                    });
                })();
            </script>
        @endif
    </header>

    {{-- ===== KONTEN ===== --}}
    <main id="app" class="{{ $isLanding ? 'min-h-[calc(100vh-4rem)]' : 'min-h-screen pt-16' }} w-full">
        {{ $slot }}
    </main>

    {{-- ===== FOOTER: hanya di landing ===== --}}
    @if ($isLanding)
        <footer class=" border-t border-white/10 bg-gradient-to-r from-[#ac1616] to-[#7e0f0f] text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-sm text-white flex items-center gap-2">
                &copy; {{ now()->year }}
                <div class="flex items-center gap-1">
                    <img src="https://mojokertokab.go.id/storage/tenantdiskominfo/app/gambar/config/site-logo-1686395812.png"
                        alt="KOMINFOMJK" class="h-6 w-auto object-contain">
                    <a href="https://diskominfo.mojokertokab.go.id/" class="hover:underline font-bold" target="_blank"
                        rel="noopener noreferrer">
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
