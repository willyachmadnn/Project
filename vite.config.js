import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/responsive-mobile.css',
                'resources/js/app.js',
                'resources/js/alert-handler.js',
                'resources/js/landing.js',
                'resources/css/landing.css',
                'resources/js/edge-time-fix.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Optimasi untuk production
        minify: 'terser',
        cssMinify: true,
        rollupOptions: {
            output: {
                // Code splitting untuk chunk yang lebih kecil
                manualChunks: {
                    vendor: ['axios'],
                    utils: ['resources/js/alert-handler.js']
                },
                // Optimasi nama file untuk caching
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]'
            }
        },
        // Kompresi untuk file yang lebih kecil
        reportCompressedSize: true,
        chunkSizeWarningLimit: 1000
    },
    // Optimasi untuk development
    server: {
        hmr: {
            overlay: false
        }
    }
});
