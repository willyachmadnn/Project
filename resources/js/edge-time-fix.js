/**
 * Edge Time Format Fix
 * Memaksa format 24 jam hanya pada Microsoft Edge browser
 * untuk mengatasi masalah AM/PM yang muncul di Edge
 */

// Deteksi apakah browser adalah Microsoft Edge
function isEdgeBrowser() {
    const userAgent = navigator.userAgent;
    return userAgent.includes('Edg/') || userAgent.includes('Edge/');
}

// Fungsi untuk memaksa format 24 jam pada Edge
function forceEdge24HourFormat() {
    if (!isEdgeBrowser()) {
        return; // Keluar jika bukan Edge
    }

    console.log('Edge detected - applying basic time format settings');

    // Override Date.prototype.toLocaleTimeString untuk Edge
    const originalToLocaleTimeString = Date.prototype.toLocaleTimeString;
    Date.prototype.toLocaleTimeString = function(locales, options) {
        // Paksa format 24 jam untuk semua panggilan toLocaleTimeString
        const modifiedOptions = {
            ...options,
            hour12: false
        };
        return originalToLocaleTimeString.call(this, locales || 'default', modifiedOptions);
    };
    
    // Override Intl.DateTimeFormat untuk Edge dengan pengaturan dasar
    const originalDateTimeFormat = Intl.DateTimeFormat;
    Intl.DateTimeFormat = function(locales, options) {
        if (options && (options.hour !== undefined || options.timeStyle !== undefined)) {
            const modifiedOptions = {
                ...options,
                hour12: false
            };
            return new originalDateTimeFormat(locales || 'default', modifiedOptions);
        }
        return new originalDateTimeFormat(locales, options);
    };

    // Konfigurasi sederhana untuk input time di Edge
    function configureTimeInputsForEdge() {
        document.querySelectorAll('input[type="time"]').forEach(input => {
            // Set atribut dasar untuk format 24 jam
            input.setAttribute('data-time-format', '24');
            
            // Event listener sederhana untuk menghapus AM/PM saat picker dibuka
            input.addEventListener('focus', function() {
                setTimeout(removeAMPMElements, 100);
            });

            input.addEventListener('click', function() {
                setTimeout(removeAMPMElements, 100);
            });
        });
    }

    // CSS sederhana untuk menyembunyikan AM/PM di Edge
    function injectEdgeTimeCSS() {
        const style = document.createElement('style');
        style.id = 'edge-time-fix-css';
        style.textContent = `
            /* Sembunyikan elemen AM/PM di Edge */
            input[type="time"]::-webkit-datetime-edit-meridiem-field {
                display: none !important;
            }
        `;
        
        const existingStyle = document.getElementById('edge-time-fix-css');
        if (existingStyle) {
            existingStyle.remove();
        }
        
        document.head.appendChild(style);
    }

    // Fungsi sederhana untuk menghapus elemen AM/PM
    function removeAMPMElements() {
        try {
            const ampmElements = document.querySelectorAll('[aria-label*="AM"], [aria-label*="PM"]');
            ampmElements.forEach(element => {
                element.style.display = 'none';
            });
        } catch (error) {
            console.warn('Error removing AM/PM elements:', error);
        }
    }





    // Inisialisasi sederhana
    function initialize() {
        // Injeksi CSS
        injectEdgeTimeCSS();
        
        // Konfigurasi input time yang ada
        configureTimeInputsForEdge();
        
        // Observer untuk input time yang ditambahkan secara dinamis
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        const timeInputs = node.querySelectorAll && node.querySelectorAll('input[type="time"]');
                        if (timeInputs && timeInputs.length > 0) {
                            configureTimeInputsForEdge();
                        }
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Jalankan inisialisasi
    initialize();
}

// Inisialisasi ketika DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', forceEdge24HourFormat);
} else {
    forceEdge24HourFormat();
}

// Export untuk penggunaan sebagai module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { forceEdge24HourFormat, isEdgeBrowser };
}