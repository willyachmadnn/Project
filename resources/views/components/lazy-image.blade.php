@props([
    'src',
    'alt' => '',
    'class' => '',
    'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
    'srcset' => null,
    'lazy' => true,
    'placeholder' => null
])

<img 
    {{ $attributes->merge(['class' => $class]) }}
    @if($lazy)
        loading="lazy"
        src="{{ $placeholder }}"
        data-src="{{ $src }}"
        @if($srcset)
            data-srcset="{{ $srcset }}"
        @endif
    @else
        src="{{ $src }}"
        @if($srcset)
            srcset="{{ $srcset }}"
        @endif
    @endif
    @if($sizes)
        sizes="{{ $sizes }}"
    @endif
    alt="{{ $alt }}"
    decoding="async"
    fetchpriority="{{ $lazy ? 'low' : 'high' }}"
>

@if($lazy)
<script>
    // Intersection Observer for lazy loading
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        lazyImages.forEach(img => {
            img.classList.add('lazy');
            imageObserver.observe(img);
        });
    } else {
        // Fallback for browsers without IntersectionObserver
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
        });
    }
</script>

<style>
    img.lazy {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    
    img.lazy:not([src*="data:image"]) {
        opacity: 1;
    }
</style>
@endif