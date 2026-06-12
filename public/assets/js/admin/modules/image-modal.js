
export const ImageModal = (() => {
        let el = null, img = null, cap = null, open = false;

        const createModal = () => {
            if (document.getElementById('imageModalContainer')) return;
            document.body.insertAdjacentHTML('beforeend', `
              <div id="imageModalContainer" class="image-modal" style="display:none">
                <div class="image-modal-overlay"></div>
                <div class="image-modal-content">
                  <div class="image-modal-header">
                    <h5 class="image-modal-title" data-translate="imageModal.title">Preview Foto</h5>
                    <button type="button" class="image-modal-close" aria-label="Tutup" data-translate-aria-label="imageModal.close">
                  </div>
                  <div class="image-modal-body">
                   <img id="imageModalFull" class="image-modal-img" src="" alt="Preview" data-translate-alt="imageModal.defaultAlt">
                    <div class="image-modal-caption" id="imageModalCaption" data-translate="imageModal.defaultCaption">Foto</div>
                  </div>
                </div>
              </div>`);
            if (!document.getElementById('imageModalStyles')) {
                document.head.insertAdjacentHTML('beforeend', `<style id="imageModalStyles">
                  .image-modal{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:all .3s}
                  .image-modal.show{opacity:1;visibility:visible}
                  .image-modal-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.85);backdrop-filter:blur(5px)}
                  .image-modal-content{position:relative;max-width:90%;max-height:90%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.3);animation:modalZoomIn .3s}
                  @keyframes modalZoomIn{from{transform:scale(.8);opacity:0}to{transform:scale(1);opacity:1}}
                  .image-modal-header{display:flex;justify-content:space-between;align-items:center;padding:12px 20px;background:#f8f9fa;border-bottom:1px solid #dee2e6}
                  .image-modal-title{margin:0;font-size:1rem;font-weight:600;color:#333}
                  .image-modal-close{background:none;border:none;font-size:1.2rem;cursor:pointer;padding:5px;color:#666;transition:all .2s;display:flex;align-items:center;justify-content:center;border-radius:4px}
                  .image-modal-close:hover{color:#000;background:rgba(0,0,0,.1)}
                  .image-modal-body{padding:20px;text-align:center;background:#fff}
                  .image-modal-img{max-width:100%;max-height:70vh;object-fit:contain;border-radius:8px}
                  .image-modal-caption{margin-top:15px;padding:10px;color:#666;font-size:.9rem;border-top:1px solid #eee}
                  [data-theme="dark"] .image-modal-content{background:#2d2d2d}
                  [data-theme="dark"] .image-modal-header{background:#3a3a3a;border-bottom-color:#444}
                  [data-theme="dark"] .image-modal-title{color:#f0f0f0}
                  [data-theme="dark"] .image-modal-close{color:#aaa}
                  [data-theme="dark"] .image-modal-body{background:#2d2d2d}
                  [data-theme="dark"] .image-modal-caption{color:#aaa;border-top-color:#444}
                </style>`);
            }
        };

        const close = () => {
            if (!el) return;
            el.classList.remove('show');
            setTimeout(() => { el.style.display = 'none'; if (img) img.src = ''; open = false; document.body.style.overflow = ''; }, 300);
        };

        return {
            init() {
                createModal();
                el  = document.getElementById('imageModalContainer');
                img = document.getElementById('imageModalFull');
                cap = document.getElementById('imageModalCaption');
                el?.querySelector('.image-modal-close')?.addEventListener('click', close);
                el?.querySelector('.image-modal-overlay')?.addEventListener('click', close);
                el?.querySelector('.image-modal-content')?.addEventListener('click', e => e.stopPropagation());
                document.addEventListener('keydown', e => { if (e.key === 'Escape' && open) close(); });
            },
            open(src, caption = '') {
                if (!el) { createModal(); this.init(); }
                if (!src) return;
                img.src = src; img.alt = caption || 'Preview';
                if (cap) cap.textContent = caption || 'Foto';
                el.style.display = 'flex';
                el.offsetHeight; // force reflow
                el.classList.add('show');
                open = true;
                document.body.style.overflow = 'hidden';
            },
            close,
            isOpen: () => open,
        };
    })();