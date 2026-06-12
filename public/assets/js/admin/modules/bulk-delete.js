import { Core } from '../core/core.js';

export const BulkDelete = {
        init() {
            const form   = document.getElementById('bulkDeleteForm');
            const selAll = document.getElementById('selectAllItems');
            const delBtn = document.getElementById('bulkDeleteBtn');
            const boxes  = document.querySelectorAll('.bulk-item-checkbox');
            if (!form || !delBtn || !boxes.length) return;

            const sync = () => {
                const n = document.querySelectorAll('.bulk-item-checkbox:checked').length;
                delBtn.disabled = n === 0;
                if (selAll) {
                    selAll.checked       = n > 0 && n === boxes.length;
                    selAll.indeterminate = n > 0 && n < boxes.length;
                }
            };

            selAll?.addEventListener('change', () => {
                boxes.forEach(b => b.checked = selAll.checked);
                sync();
            });
            boxes.forEach(b => b.addEventListener('change', sync));

            form.addEventListener('submit', e => {
                const n = document.querySelectorAll('.bulk-item-checkbox:checked').length;
                if (!n) { e.preventDefault(); return; }
                const msg = Core.t(form.dataset.confirmKey, form.dataset.confirmFallback ?? 'Yakin ingin menghapus?');
                if (!confirm(msg)) e.preventDefault();
            });

            sync();
        },
    };

    // =========================================================================
    // MODULE: Konfirmasi delete untuk form single-item
    // =========================================================================
