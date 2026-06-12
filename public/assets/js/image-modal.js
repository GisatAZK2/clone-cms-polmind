if (!window.ImageModalClass) {

    class ImageModal {
        constructor() {
            this.modal = null;
            this.init();
        }

        static init() {
            if (!window._globalImageModalInstance) {
                window._globalImageModalInstance = new ImageModal();
            }
            return window._globalImageModalInstance;
        }

        init() {
            this.createModal();
            this.bindImages();
            this.bindEvents();

            // auto detect image baru
            const observer = new MutationObserver(() => {
                this.bindImages();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        createModal() {
            const old = document.getElementById('globalImageModal');
            if (old) old.remove();

            const modal = document.createElement('div');
            modal.id = 'globalImageModal';

            modal.innerHTML = `
            <div class="im-overlay"></div>

            <div class="im-content">
                <button class="im-close">&times;</button>

                <img class="im-image" src="" alt="Preview">

                <div class="im-caption"></div>
            </div>
        `;

            document.body.appendChild(modal);

            const style = document.createElement('style');

            style.innerHTML = `
            #globalImageModal{
                position:fixed;
                inset:0;
                z-index:999999;
                display:none;
                align-items:center;
                justify-content:center;
            }

            #globalImageModal.show{
                display:flex;
            }

            .im-overlay{
                position:absolute;
                inset:0;
                background:rgba(0,0,0,.85);
                backdrop-filter:blur(4px);
            }

            .im-content{
                position:relative;
                max-width:90%;
                max-height:90%;
                z-index:2;
                animation:zoomIn .2s ease;
            }

            .im-image{
                max-width:100%;
                max-height:85vh;
                border-radius:12px;
                display:block;
                box-shadow:0 10px 40px rgba(0,0,0,.4);
            }

            .im-close{
                position:absolute;
                top:-15px;
                right:-15px;
                width:40px;
                height:40px;
                border:none;
                border-radius:50%;
                background:#fff;
                font-size:28px;
                cursor:pointer;
                z-index:3;
            }

            .im-caption{
                margin-top:10px;
                text-align:center;
                color:#fff;
                font-size:14px;
            }

            @keyframes zoomIn{
                from{
                    opacity:0;
                    transform:scale(.9);
                }
                to{
                    opacity:1;
                    transform:scale(1);
                }
            }
        `;

            document.head.appendChild(style);

            this.modal = modal;
        }

        bindImages() {
            const images = document.querySelectorAll('img.preview-image');
            images.forEach(img => {
                // Skip images explicitly opted-out from modal
                if (img.dataset.modalReady) {
                    if (console && console.debug) console.debug('ImageModal: already bound', img.src);
                    return;
                }
                if (img.dataset.modalSkip === 'true') {
                    if (console && console.debug) console.debug('ImageModal: skipped by dataset.modalSkip', img.src);
                    return;
                }

                if (img.closest && img.closest('.logo-link')) {
                    if (console && console.debug) console.debug('ImageModal: skipped inside .logo-link', img.src);
                    return;
                }

                img.dataset.modalReady = 'true';
                img.style.cursor = 'pointer';

                img.addEventListener('click', () => {
                    if (console && console.debug) console.debug('ImageModal: image clicked', img.src);
                    this.open(img.src, img.alt);
                });

                if (console && console.debug) console.debug('ImageModal: bound image', img.src);
            });
        }

        bindEvents() {
            this.modal.querySelector('.im-close')
                .addEventListener('click', () => this.close());

            this.modal.querySelector('.im-overlay')
                .addEventListener('click', () => this.close());

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    this.close();
                }
            });

            document.addEventListener('click', (e) => {
                try {
                    const item = e.target.closest && e.target.closest('.dok-item');
                    if (!item) return;
                    const img = item.querySelector && item.querySelector('img.preview-image');
                    if (img) {
                        if (console && console.debug) console.debug('ImageModal: delegated open from .dok-item', img.src);
                        this.open(img.src, img.alt || '');
                    }
                } catch (err) {
                    if (console && console.warn) console.warn('ImageModal delegated click error', err);
                }
            });
        }

        open(src, caption = '') {
            if (!src) return;

            const finalCaption = caption?.trim() || 'Preview gambar';

            this.modal.querySelector('.im-image').src = src;
            this.modal.querySelector('.im-image').alt = finalCaption;
            this.modal.querySelector('.im-caption').textContent = finalCaption;

            this.modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        close() {
            this.modal.classList.remove('show');

            document.body.style.overflow = '';
        }
    }

    // expose class to global scope
    window.ImageModalClass = ImageModal;

    function __ensureImageModalInit() {
        try {
            const instance = ImageModal.init();
            window.ImageModal = instance;
            if (instance && typeof instance.bindImages === 'function') {
                instance.bindImages();
            }
            if (console && console.debug) console.debug('ImageModal: initialized');
        } catch (err) {
            if (console && console.warn) console.warn('ImageModal init failed', err);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', __ensureImageModalInit);
    } else {
        __ensureImageModalInit();
    }

    window.addEventListener('load', __ensureImageModalInit);

}