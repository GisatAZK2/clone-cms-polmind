import { Core } from '../core/core.js';

export const ContentPendaftaranForm = {
    init() {
        const wrap   = document.getElementById('itemsContainer');
        const addBtn = document.getElementById('addItem');
        const tpl    = document.getElementById('itemTemplate');

        if (!wrap || !addBtn || !tpl) return;

        let idx = this.getNextIndex(wrap);

        const syncRows = () => {
            const rows = [...wrap.querySelectorAll('.item-row')];

            rows.forEach((row, index) => {
                const number = row.querySelector('.item-number');
                const removeBtn = row.querySelector('.remove-item');

                if (number) number.textContent = index + 1;

                // Rule: block pertama tidak boleh punya tombol remove.
                if (removeBtn) {
                    removeBtn.classList.toggle('d-none', index === 0);
                    removeBtn.disabled = index === 0;
                }

                this.bindPreview(row);
            });
        };

        addBtn.addEventListener('click', () => {
            const nextNumber = wrap.querySelectorAll('.item-row').length + 1;

            const html = tpl.innerHTML
                .replaceAll('__INDEX__', idx)
                .replaceAll('__NUM__', nextNumber);

            const div = document.createElement('div');
            div.innerHTML = html.trim();

            const el = div.firstElementChild;
            if (!el) return;

            wrap.appendChild(el);

            Core.translateNode(el);
            this.bindPreview(el);

            idx++;
            wrap.dataset.nextIndex = String(idx);

            syncRows();
        });

        wrap.addEventListener('click', e => {
            const removeBtn = e.target.closest('.remove-item');
            if (!removeBtn) return;

            const row = removeBtn.closest('.item-row');
            if (!row) return;

            const rows = [...wrap.querySelectorAll('.item-row')];
            const rowIndex = rows.indexOf(row);

            // Guard tambahan: block pertama tidak boleh dihapus.
            if (rowIndex === 0) return;

            row.remove();
            syncRows();
        });

        syncRows();
    },

    getNextIndex(wrap) {
        const datasetIndex = parseInt(wrap.dataset.nextIndex || '0', 10);

        const maxDomIndex = [...wrap.querySelectorAll('.item-row')]
            .map(row => parseInt(row.dataset.index || '0', 10))
            .filter(Number.isFinite)
            .reduce((max, value) => Math.max(max, value), -1);

        return Math.max(datasetIndex, maxDomIndex + 1, 0);
    },

    bindPreview(row) {
        if (!row || row.dataset.previewBound === 'true') return;

        const input    = row.querySelector('.url-images-input');
        const wrap     = row.querySelector('.item-image-preview-wrap');
        const img      = row.querySelector('.item-image-preview');
        const altInput = row.querySelector('input[name$="[alt]"]');

        if (!input || !wrap || !img) return;

        row.dataset.previewBound = 'true';

        const syncAlt = () => {
            const altValue = altInput?.value?.trim() || 'Preview gambar';

            img.alt = altValue;
            img.title = altValue;
        };

        if (altInput) {
            altInput.addEventListener('input', syncAlt);
        }

        input.addEventListener('change', async () => {
            const file = input.files?.[0];

            if (!file) {
                wrap.classList.add('d-none');
                img.removeAttribute('src');
                return;
            }

            if (!file.type.startsWith('image/')) {
                input.value = '';
                wrap.classList.add('d-none');
                img.removeAttribute('src');
                alert('File harus berupa gambar.');
                return;
            }

            const url = await Core.readFileAsDataURL(file);

            img.src = url;

            syncAlt();

            img.classList.remove('d-none');
            img.style.display = 'block';

            wrap.classList.remove('d-none');
            wrap.style.display = 'block';

        });

        syncAlt();
    },
};