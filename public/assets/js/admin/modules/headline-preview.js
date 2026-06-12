import { Core } from '../core/core.js';

export const HeadlinePreview = {
        init() {
            const titleInput = document.getElementById('title');
            const fileInput  = document.getElementById('url_image');
            const prevImg    = document.getElementById('previewImage');
            if (!fileInput && !prevImg) return;

            const prevTitle  = document.getElementById('previewTitle');
            const prevHolder = document.getElementById('previewPlaceholder');

            Core.bindImagePreview(fileInput, prevImg, prevHolder);
            titleInput?.addEventListener('input', () => {
                if (prevTitle) prevTitle.textContent = titleInput.value.trim() || 'Judul Headline';
            });

            const existingSrc = prevImg?.dataset.existingSrc;
            if (existingSrc && prevImg) {
                prevImg.src = existingSrc;
                prevImg.style.display = 'block';
                if (prevHolder) prevHolder.style.display = 'none';
            }
        },
    };