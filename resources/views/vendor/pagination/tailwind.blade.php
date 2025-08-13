@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();
    $WIN     = 5; // jumlah nomor yang ditampilkan

    // Susun blok angka (dengan ellipsis)
    if ($last <= $WIN) {
        $blocks = [range(1, $last)];
    } elseif ($current <= 3) {
        $blocks = [range(1, 3), 'gap', [$last-1, $last]];
    } elseif ($current >= $last - 2) {
        $blocks = [[1, 2], 'gap', [$last-2, $last-1, $last]];
    } else {
        $blocks = [range($current - 2, $current + 2)];
    }
@endphp

<nav class="flex items-center justify-center gap-2">

    {{-- «« (ke awal) --}}
    @if ($paginator->onFirstPage())
        <span class="p-2 rounded-md opacity-40">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 19l-7-7 7-7" />
            </svg>
        </span>
    @else
        <a href="{{ $paginator->url(1) }}" class="p-2 rounded-md hover:bg-gray-100" aria-label="First page">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 19l-7-7 7-7" />
            </svg>
        </a>
    @endif

    {{-- ‹ (mundur 1) --}}
    @if ($paginator->onFirstPage())
        <span class="p-2 rounded-md opacity-40">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="p-2 rounded-md hover:bg-gray-100" aria-label="Previous page">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    @endif

    {{-- Nomor + ellipsis (5 angka) --}}
    <ul class="flex items-center gap-1">
        @foreach ($blocks as $blk)
            @if ($blk === 'gap')
                <li class="px-1 select-none">…</li>
            @else
                @foreach ($blk as $page)
                    <li>
                        @if ($page == $current)
                            <span class="px-3 py-1 rounded-md bg-red-700 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $paginator->url($page) }}" class="px-3 py-1 rounded-md hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach
    </ul>

    {{-- › (maju 1) --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="p-2 rounded-md hover:bg-gray-100" aria-label="Next page">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    @else
        <span class="p-2 rounded-md opacity-40">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>
    @endif

    {{-- »» (ke akhir) --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->url($last) }}" class="p-2 rounded-md hover:bg-gray-100" aria-label="Last page">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l7 7-7 7" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
            </svg>
        </a>
    @else
        <span class="p-2 rounded-md opacity-40">
            <svg class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l7 7-7 7" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
            </svg>
        </span>
    @endif

</nav>
@endif
