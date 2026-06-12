import { Core } from '../core/core.js';

export const DokumentasiForm = {
        init() {
            const blocks  = document.getElementById('imageBlocks');
            const addBtn  = document.getElementById('addImageBlock');
            const form    = document.getElementById('mainForm');
            if (!blocks || !addBtn || !form) return;

            const storageBase   = blocks.dataset.storageBase || '/storage';
            const existingItems = JSON.parse(blocks.dataset.existingItems || '[]');
            let nextIdx = 0;

            const refreshOrder = () => {
                const visible = [...blocks.children].filter(b => !b.classList.contains('d-none'));
                visible.forEach((b, pos) => {
                    const orderIn = b.querySelector('.block-order-input');
                    const orderLb = b.querySelector('.block-order-label');
                    if (orderIn) orderIn.value = pos + 1;
                    if (orderLb) {
                        orderLb.innerHTML = `<span data-translate="dokumentasi.orderLabel">Urutan</span>: ${pos + 1}`;
                        Core.translateNode(orderLb);
                    }
                    b.querySelector('.move-up-btn')  && (b.querySelector('.move-up-btn').disabled   = pos === 0);
                    b.querySelector('.move-down-btn') && (b.querySelector('.move-down-btn').disabled = pos === visible.length - 1);
                });
            };

            const moveBlock = (block, dir) => {
                const visible = [...blocks.children].filter(b => !b.classList.contains('d-none'));
                const i = visible.indexOf(block);
                if (dir === 'up' && i > 0) blocks.insertBefore(block, visible[i - 1]);
                if (dir === 'down' && i < visible.length - 1) blocks.insertBefore(visible[i + 1], block);
                refreshOrder();
            };

            const createBlock = ({ path = '', alt = '', isExisting = false, existingIndex = null }) => {
                const wrap  = document.createElement('div');
                wrap.className = 'image-block mb-3';
                const idx = existingIndex !== null ? existingIndex : nextIdx++;
                const sp  = Core.escapeAttr(path);
                const sa  = Core.escapeAttr(alt);

                wrap.innerHTML = `
                  <div class="block-header">
                    <span class="block-order-label"><span data-translate="dokumentasi.orderLabel">Urutan</span>: 0</span>
                    <div class="move-buttons">
                      <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Pindah ke atas"><i class="bi bi-arrow-up"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn" title="Pindah ke bawah"><i class="bi bi-arrow-down"></i></button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle" title="Seret"><i class="bi bi-grip-vertical"></i></button>
                  </div>
                  <button type="button" class="btn btn-sm btn-danger remove-block position-absolute" style="top:.75rem;right:.75rem" title="Hapus gambar"><i class="bi bi-trash"></i></button>
                  ${isExisting ? `<input type="hidden" name="existing_images[${idx}][path]" value="${sp}">` : ''}
                  ${isExisting ? `<input type="hidden" name="existing_images[${idx}][remove]" value="0" class="remove-flag">` : ''}
                  ${isExisting
                    ? `<input type="hidden" name="existing_images[${idx}][urutan]" value="0" class="block-order-input">`
                    : `<input type="hidden" name="new_orders[]" value="0" class="block-order-input">`}
                  <div class="row g-3">
                    <div class="col-12 col-md-6">
                      <label class="form-label">${isExisting ? '<span data-translate="dokumentasi.replaceImage">Ganti Gambar</span>' : '<span data-translate="dokumentasi.uploadImage">Upload Gambar</span>'}</label>
                      <input type="file" name="${isExisting ? `existing_images[${idx}][replace]` : 'new_images[]'}" class="form-control form-control-sm image-input" accept="image/*">
                      <div class="image-preview mt-2">
                        ${path ? `<p class="small text-muted mb-2" data-translate="dokumentasi.currentPreview">Preview saat ini:</p>
                          <img src="${storageBase}/${sp}" class="img-thumbnail image-preview-img" style="max-width:100%;max-height:160px;object-fit:cover" alt="${sa}">` : ''}
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label class="form-label" data-translate="common.altText">Alt Text</label>
                      <input type="text" name="${isExisting ? `existing_images[${idx}][alt]` : 'new_alts[]'}" class="form-control form-control-sm" placeholder="Alt text" value="${sa}">
                    </div>
                  </div>`;

                // Hapus
                wrap.querySelector('.remove-block').addEventListener('click', e => {
                    e.preventDefault();
                    if (isExisting) {
                        wrap.querySelector('.remove-flag').value = '1';
                        wrap.classList.add('d-none');
                    } else {
                        wrap.remove();
                    }
                    refreshOrder();
                });

                wrap.querySelector('.move-up-btn').addEventListener('click', e => { e.preventDefault(); moveBlock(wrap, 'up'); });
                wrap.querySelector('.move-down-btn').addEventListener('click', e => { e.preventDefault(); moveBlock(wrap, 'down'); });

                // Preview gambar baru
                wrap.querySelector('.image-input').addEventListener('change', async function () {
                    const file = this.files?.[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    const prevContainer = wrap.querySelector('.image-preview');
                    let img = prevContainer.querySelector('.image-preview-img');
                    if (!img) {
                        prevContainer.innerHTML = `<p class="small text-muted mb-2" data-translate="dokumentasi.newPreview">Preview gambar:</p>`;
                        img = document.createElement('img');
                        img.className = 'img-thumbnail image-preview-img';
                        img.style.cssText = 'max-width:100%;max-height:160px;object-fit:cover';
                        prevContainer.appendChild(img);
                        Core.translateNode(prevContainer);
                    }
                    img.src = url;
                });

                // Drag desktop
                wrap.draggable = window.innerWidth >= 768;
                wrap.addEventListener('dragstart', e => { wrap.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; });
                wrap.addEventListener('dragend', () => wrap.classList.remove('dragging'));

                // Swipe mobile
                Core.bindSwipeReorder(wrap, dir => moveBlock(wrap, dir));

                blocks.appendChild(wrap);
                Core.translateNode(wrap);
                refreshOrder();
            };

            // Drag-over pada container
            blocks.addEventListener('dragover', e => {
                e.preventDefault();
                const dragging = blocks.querySelector('.dragging');
                if (!dragging) return;
                const after = [...blocks.querySelectorAll('.image-block:not(.dragging):not(.d-none)')]
                    .reduce((cl, ch) => {
                        const box = ch.getBoundingClientRect();
                        const off = e.clientY - box.top - box.height / 2;
                        return off < 0 && off > cl.offset ? { offset: off, element: ch } : cl;
                    }, { offset: Number.NEGATIVE_INFINITY }).element;
                after ? blocks.insertBefore(dragging, after) : blocks.appendChild(dragging);
                refreshOrder();
            });

            addBtn.addEventListener('click', () => createBlock({}));
            form.addEventListener('submit', refreshOrder);
            existingItems.forEach((item, i) => createBlock({ path: item.gambar || '', alt: item.alt || '', isExisting: true, existingIndex: i }));
        },
    };