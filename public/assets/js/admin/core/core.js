/** Core utilities shared by admin modules. */
export const Core = {

        /** Terjemahkan node DOM yang baru ditambahkan secara dinamis */
        translateNode(node) {
            if (!node) return;
            const AT = window.AdminTranslate;
            if (AT?.translateNode) { AT.translateNode(node); return; }
            if (AT?.translatePage) AT.translatePage();
        },

        /** Ambil teks terjemahan (dengan fallback) */
        t(key, fallback = '') {
            const AT = window.AdminTranslate;
            if (AT?.t) return AT.t(key) || fallback;
            const lang = localStorage.getItem('admin_language') || 'id';
            return AT?.translations?.[lang]?.[key] ?? fallback;
        },

        /** Baca file sebagai DataURL, kembalikan Promise<string> */
        readFileAsDataURL(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = e => resolve(e.target.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        },

        /**
         * Pasang preview gambar ke <img> ketika file input berubah.
         * @param {HTMLInputElement} input
         * @param {HTMLImageElement} img
         * @param {HTMLElement|null} placeholder  — elemen yang disembunyikan saat ada gambar
         * @param {HTMLElement|null} wrap         — wrapper yang ditampilkan saat ada gambar
         */
        bindImagePreview(input, img, placeholder = null, wrap = null) {
            if (!input || !img) return;
            input.addEventListener('change', async () => {
                const file = input.files?.[0];
                if (!file?.type.startsWith('image/')) return;
                const url = await Core.readFileAsDataURL(file);
                img.src = url;
                img.classList.remove('d-none');
                img.style.display = 'block';

                if (placeholder) {
                    placeholder.classList.add('d-none');
                    placeholder.style.display = 'none';
                }

                if (wrap) {
                    wrap.classList.remove('d-none');
                }
            });
        },

        /**
         * Pasang swipe gesture vertikal untuk mengubah urutan (mobile).
         * @param {HTMLElement} el      — elemen yang digeser
         * @param {Function} onSwipe   — callback(direction: 'up'|'down')
         */
        bindSwipeReorder(el, onSwipe) {
            const THRESHOLD = 55;
            let startY = 0, currentY = 0, active = false;

            el.addEventListener('touchstart', e => {
                if (e.target.closest('button,input,textarea,select,label,img,a')) return;
                startY = currentY = e.touches[0].clientY;
                active = true;
                el.classList.add('is-touching');
            }, { passive: true });

            el.addEventListener('touchmove', e => {
                if (!active) return;
                currentY = e.touches[0].clientY;
                const d = currentY - startY;
                if (Math.abs(d) > 8) {
                    el.style.transform = `translateY(${d * 0.35}px) scale(0.98)`;
                    el.style.opacity = '0.85';
                }
            }, { passive: true });

            el.addEventListener('touchend', () => {
                if (!active) return;
                const d = currentY - startY;
                el.style.transform = '';
                el.style.opacity = '';
                el.classList.remove('is-touching');
                if (Math.abs(d) >= THRESHOLD) {
                    const dir = d < 0 ? 'up' : 'down';
                    onSwipe(dir);
                    el.classList.add('order-changed');
                    setTimeout(() => el.classList.remove('order-changed'), 250);
                }
                active = false; startY = 0; currentY = 0;
            });
        },

        /**
         * Pindahkan elemen dalam parent DOM.
         * @param {HTMLElement} el
         * @param {'up'|'down'} dir
         * @param {string} [selector]  — selector saudara yang valid (default: semua saudara)
         */
        moveElement(el, dir, selector = null) {
            const siblings = selector
                ? [...el.parentNode.querySelectorAll(selector)]
                : [...el.parentNode.children];
            const i = siblings.indexOf(el);
            if (dir === 'up' && i > 0) el.parentNode.insertBefore(el, siblings[i - 1]);
            if (dir === 'down' && i < siblings.length - 1) el.parentNode.insertBefore(siblings[i + 1], el);
        },

        /** Escape HTML untuk nilai di dalam atribut */
        escapeAttr(v) {
            return String(v ?? '')
                .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        },

        /** Trigger tinymce.save() jika TinyMCE tersedia */
        tinymceSave() {
            if (typeof tinymce !== 'undefined') tinymce.triggerSave();
        },

        /** Inisialisasi TinyMCE pada <textarea> dengan opsi default */
        initTinyMCE(selector, extraOpts = {}) {
            if (typeof tinymce === 'undefined') return;
            const defaults = {
                license_key: 'gpl',
                height: 360,
                menubar: false,
                branding: false,
                promotion: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap',
                    'preview', 'anchor', 'searchreplace', 'visualblocks',
                    'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'help', 'wordcount',
                ],
                toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link table | code fullscreen',
                content_style: 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;font-size:16px;line-height:1.7}',
                setup: editor => editor.on('change keyup', () => editor.save()),
            };
            tinymce.init({ selector, ...defaults, ...extraOpts });
        },
    };