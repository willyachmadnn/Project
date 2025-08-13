{{-- resources/views/vendor/pagination/tailwind.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
         class="flex items-center justify-center gap-2 select-none">

        {{-- First + Previous --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-400">««</span>
            <span aria-disabled="true" class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-400">‹</span>
        @else
            <a href="{{ $paginator->url(1) }}"
               class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none">««</a>
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none">‹</a>
        @endif

@foreach ($elements as $element)
    @if (is_string($element))
        <span class="inline-flex items-center h-9 px-2 text-slate-500">…</span>
    @endif

    @if (is_array($element))
        @php
            $current = $paginator->currentPage();
            $last    = $paginator->lastPage();
            // Ambil daftar halaman dari blok ini
            $pages   = array_keys($element);

            if ($current <= 2) {
                // Force 1..3
                $pages = array_slice($pages, 0, 3);
            } elseif ($current >= $last - 1) {
                // Force last-2 .. last
                $pages = array_slice($pages, -3);
            } else {
                // Tengah: current-1, current, current+1
                $pages = [$current - 1, $current, $current + 1];
            }
        @endphp

        @foreach ($pages as $page)
            @if ($page == $current)
                <span aria-current="page"
                      class="inline-flex items-center justify-center h-9 min-w-[2.25rem] px-3 rounded-lg text-white bg-[#800000]">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $element[$page] }}"
                   class="inline-flex items-center justify-center h-9 min-w-[2.25rem] px-3 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none">
                    {{ $page }}
                </a>
            @endif
        @endforeach
    @endif
@endforeach


        {{-- Next + Last --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none">›</a>
            <a href="{{ $paginator->url($paginator->lastPage()) }}"
               class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none">»»</a>
        @else
            <span aria-disabled="true" class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-400">›</span>
            <span aria-disabled="true" class="inline-flex items-center justify-center h-9 min-w-[2.25rem] rounded-lg border border-slate-300 text-slate-400">»»</span>
        @endif
    </nav>
@endif
