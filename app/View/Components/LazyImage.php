<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LazyImage extends Component
{
    public string $src;
    public string $alt;
    public ?string $class;
    public ?string $sizes;
    public ?string $srcset;
    public bool $lazy;
    public ?string $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $src,
        string $alt = '',
        ?string $class = null,
        ?string $sizes = null,
        ?string $srcset = null,
        bool $lazy = true,
        ?string $placeholder = null
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->sizes = $sizes;
        $this->srcset = $srcset;
        $this->lazy = $lazy;
        $this->placeholder = $placeholder ?? 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af">Loading...</text></svg>'
        );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.lazy-image');
    }

    /**
     * Generate responsive srcset for different screen sizes
     */
    public function generateSrcset(string $basePath): string
    {
        $sizes = [320, 640, 768, 1024, 1280, 1536];
        $srcsetArray = [];
        
        foreach ($sizes as $size) {
            $srcsetArray[] = $this->generateResponsiveUrl($basePath, $size) . " {$size}w";
        }
        
        return implode(', ', $srcsetArray);
    }
    
    /**
     * Generate responsive image URL (placeholder for actual image processing)
     */
    private function generateResponsiveUrl(string $basePath, int $width): string
    {
        // In a real implementation, this would integrate with image processing service
        // For now, return original image
        return $basePath;
    }
}