import { Core } from '../core/core.js';

export const HeadlineForm = {
        init() {
            const form       = document.getElementById('headlineForm');
            const uploadZone = document.getElementById('uploadZone');
            const fileInput  = document.getElementById('url_image');
            if (!form || !uploadZone || !fileInput) return;

            const uploadImg  = document.getElementById('uploadPreviewImg');
            const uploadHold = document.getElementById('uploadPlaceholder');
            const overlay    = document.getElementById('uploadOverlay');
            const prevImg    = document.getElementById('previewImage');
            const prevHolder = document.getElementById('previewBannerPlaceholder');
            const prevTitle  = document.getElementById('previewTitle');

            const applyImage = async file => {
                if (!file?.type.startsWith('image/')) return;
                const url = await Core.readFileAsDataURL(file);
                if (uploadImg)  { uploadImg.src = url; uploadImg.style.display = 'block'; }
                if (uploadHold) uploadHold.style.display = 'none';
                if (overlay)    overlay.style.display = 'flex';
                if (prevImg)    { prevImg.src = url; prevImg.style.display = 'block'; }
                if (prevHolder) prevHolder.style.display = 'none';
            };

            uploadZone.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => applyImage(fileInput.files?.[0]));

            uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
            uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
            uploadZone.addEventListener('drop', e => {
                e.preventDefault(); uploadZone.classList.remove('dragover');
                const file = e.dataTransfer.files?.[0];
                if (!file?.type.startsWith('image/')) return;
                const dt = new DataTransfer(); dt.items.add(file);
                fileInput.files = dt.files;
                applyImage(file);
            });

            document.querySelectorAll('.status-toggle-option input[type="radio"]').forEach(r => {
                r.addEventListener('change', () => {
                    document.querySelectorAll('.status-toggle-option').forEach(o => o.classList.remove('active'));
                    r.closest('.status-toggle-option')?.classList.add('active');
                });
            });

            // TinyMCE live preview judul
            const tid = setInterval(() => {
                if (typeof tinymce === 'undefined' || !tinymce.get('editor-title')) return;
                clearInterval(tid);
                const editor = tinymce.get('editor-title');
                const refresh = () => {
                    if (!prevTitle) return;
                    const html = editor.getContent().trim();
                    const placeholder = Core.t('headline.previewEmpty', 'Judul headline akan muncul di sini');
                    prevTitle.innerHTML = html || `<span class="preview-placeholder-text">${placeholder}</span>`;
                };
                editor.on('input change keyup SetContent', refresh);
                refresh();
            }, 300);
        },
    };