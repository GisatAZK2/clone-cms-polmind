import { Core } from '../core/core.js';

export const ConfirmDelete = {
        init() {
            document.querySelectorAll('.confirm-delete-form').forEach(f => {
                f.addEventListener('submit', e => {
                    const msg = Core.t(f.dataset.confirmKey, f.dataset.confirmFallback ?? 'Yakin ingin menghapus?');
                    if (!confirm(msg)) e.preventDefault();
                });
            });
        },
    };

    // =========================================================================
    // MODULE: Reorder List — dipakai oleh HeadlineOrder & NewsOrder
    // Blade menggunakan ID/class yang berbeda → dikonfigurasi via objek opts
    // =========================================================================
