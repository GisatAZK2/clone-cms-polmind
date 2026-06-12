import { Core } from '../core/core.js';

export const SimpleImagePreview = {
    init() {
        this.bindDefaultPreview();
        this.bindPopupPreview();
    },

    bindDefaultPreview() {
        const input = document.getElementById('urlImagesInput');
        const wrap  = document.getElementById('imgPreviewWrap');
        const img   = document.getElementById('imgPreview');

        if (!input || !wrap || !img) return;

        Core.bindImagePreview(input, img, null, wrap);
    },

    bindPopupPreview() {
        const input = document.getElementById('url_image');
        const wrap  = document.getElementById('imagePreview');
        const img   = document.getElementById('previewImg');
        const label = document.getElementById('previewLabel');

        if (!input || !wrap || !img) return;

        input.addEventListener('change', async () => {
            const file = input.files?.[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                input.value = '';
                wrap.style.display = 'none';
                img.removeAttribute('src');

                alert('File harus berupa gambar.');
                return;
            }

            const url = await Core.readFileAsDataURL(file);

            img.src = url;
            img.classList.remove('d-none');
            img.style.display = 'block';

            wrap.classList.remove('d-none');
            wrap.style.display = 'block';

            if (label) {
                label.textContent = label.dataset.newText || 'Preview Gambar Baru';
            }
        });
    },
};