/**
 * Admin Script Loader
 *
 * File ini tetap dipanggil dari Blade sebagai script biasa:
 * <script src="{{ asset('assets/js/admin-script.js') }}"></script>
 *
 */
(function () {
    'use strict';

    if (window.__ADMIN_MODULE_ENTRYPOINT_LOADED__) return;
    window.__ADMIN_MODULE_ENTRYPOINT_LOADED__ = true;

    const currentScript = document.currentScript;
    const entrySrc = currentScript
        ? new URL('admin/entrypoints/admin.js', currentScript.src).toString()
        : '/assets/js/admin/entrypoints/admin.js';

    const script = document.createElement('script');
    script.type = 'module';
    script.src = entrySrc;
    script.dataset.adminEntrypoint = 'true';

    script.onerror = function () {
        console.error('[AdminScript] Gagal memuat module entrypoint:', entrySrc);
    };

    document.head.appendChild(script);
})();
