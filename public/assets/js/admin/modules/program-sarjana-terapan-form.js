import { Core } from '../core/core.js';

export const ProgramSarjanaTerapanForm = {
        init() {
            const form      = document.getElementById('programForm');
            const fileInput = document.getElementById('gambar_prodi_input');
            const listWrap  = document.getElementById('image-preview-list');
            if (!form || !fileInput || !listWrap) return;

            const storageBase  = listWrap.dataset.storageBase || '/storage/';
            let existingImages = [], existingAlts = [];
            try {
                existingImages = JSON.parse(listWrap.dataset.existingImages || '[]');
                existingAlts   = JSON.parse(listWrap.dataset.existingAlts   || '[]');
            } catch (_) {}

            let newFiles = [], nextNewIdx = 0, dragSource = null, placeholder = null;

            const ensurePlaceholder = () => {
                if (!placeholder) {
                    placeholder = document.createElement('div');
                    placeholder.className = 'col-12 col-md-4 image-preview-item drop-placeholder';
                    placeholder.innerHTML = `<div class="card h-100"><div class="card-body p-0" style="min-height:150px"></div></div>`;
                }
            };

            const removePlaceholder = () => placeholder?.parentElement?.removeChild(placeholder);

            const syncInput = () => {
                const dt = new DataTransfer();
                [...listWrap.querySelectorAll('.image-preview-item:not(.drop-placeholder)')]
                    .map(c => c.dataset.newIndex).filter(Boolean)
                    .forEach(i => { const f = newFiles.find(x => String(x.index) === String(i)); if (f) dt.items.add(f.file); });
                fileInput.files = dt.files;
            };

            const updateOrderInputs = () => {
                listWrap.querySelectorAll('.image-preview-item:not(.drop-placeholder)')
                    .forEach(item => { const oi = item.querySelector('.image-order-input'); if (oi) oi.value = item.dataset.imageKey; });
            };

            const createCard = ({ key, src, isNew, label, altText }) => {
                const wrap = document.createElement('div');
                wrap.className = 'col-12 col-md-4 image-preview-item';
                wrap.draggable = window.innerWidth >= 768;
                wrap.dataset.imageKey = key;
                if (isNew) wrap.dataset.newIndex = key.replace('new-', '');

                wrap.innerHTML = `
                    <div class="card h-100 position-relative">
                        <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2 image-card-toolbar">
                            <span class="badge bg-secondary image-drag-badge" style="cursor:grab">
                            <i class="bi bi-grip-vertical"></i>
                            <span data-translate="program_sarjana_terapan.dragImage">Seret</span>
                            </span>

                            <div class="d-flex align-items-center gap-1">
                            <div class="image-mobile-order-actions d-md-none">
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary btn-image-move-up"
                                        title="Naikkan gambar"
                                        aria-label="Naikkan gambar">
                                <i class="bi bi-arrow-up"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary btn-image-move-down"
                                        title="Turunkan gambar"
                                        aria-label="Turunkan gambar">
                                <i class="bi bi-arrow-down"></i>
                                </button>
                            </div>

                            <button type="button"
                                    class="btn btn-sm btn-danger remove-image-btn"
                                    title="Hapus gambar">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            </div>
                        </div>

                        <img src="${src}"
                            class="img-fluid rounded mb-2 preview-image"
                            style="height:150px;width:100%;object-fit:cover;pointer-events:none"
                            alt="${label}">

                        <input type="text"
                                class="form-control form-control-sm mb-2 image-alt-input"
                                name="image_alt[]"
                                placeholder="Alt text"
                                value="${Core.escapeAttr(altText || '')}">

                        ${isNew
                            ? `<input type="hidden" class="image-order-input" name="image_order[]" value="${key}">`
                            : `<input type="hidden" name="existing_images[]" value="${key}">
                            <input type="hidden" class="image-order-input" name="image_order[]" value="${key}">`}
                        </div>
                    </div>`;

                wrap.querySelector('.remove-image-btn').addEventListener('click', () => {
                    if (isNew) { newFiles = newFiles.filter(f => f.index !== Number(wrap.dataset.newIndex)); syncInput(); }
                    wrap.remove(); updateOrderInputs();
                });

                wrap.querySelector('.btn-image-move-up')?.addEventListener('click', () => {
                    Core.moveElement(wrap, 'up', '.image-preview-item:not(.drop-placeholder)');
                    syncInput();
                    updateOrderInputs();

                    wrap.classList.add('order-changed');
                    setTimeout(() => wrap.classList.remove('order-changed'), 250);
                });

                wrap.querySelector('.btn-image-move-down')?.addEventListener('click', () => {
                    Core.moveElement(wrap, 'down', '.image-preview-item:not(.drop-placeholder)');
                    syncInput();
                    updateOrderInputs();

                    wrap.classList.add('order-changed');
                    setTimeout(() => wrap.classList.remove('order-changed'), 250);
                });

                // Drag
                wrap.addEventListener('dragstart', e => {
                    if (window.innerWidth < 768) { e.preventDefault(); return; }
                    dragSource = wrap; e.dataTransfer.effectAllowed = 'move';
                    requestAnimationFrame(() => wrap.classList.add('dragging'));
                });
                wrap.addEventListener('dragend', () => {
                    wrap.classList.remove('dragging'); removePlaceholder();
                    placeholder = null; dragSource = null; syncInput(); updateOrderInputs();
                });
                wrap.addEventListener('dragenter dragover', e => {
                    e.preventDefault(); e.dataTransfer.dropEffect = 'move';
                    if (!dragSource || dragSource === wrap) return;
                    ensurePlaceholder();
                    const rect = wrap.getBoundingClientRect();
                    e.clientX < rect.left + rect.width / 2
                        ? listWrap.insertBefore(placeholder, wrap)
                        : listWrap.insertBefore(placeholder, wrap.nextSibling);
                });

                // Swipe mobile
                Core.bindSwipeReorder(wrap, dir => {
                    Core.moveElement(wrap, dir, '.image-preview-item:not(.drop-placeholder)');
                    syncInput(); updateOrderInputs();
                });

                listWrap.appendChild(wrap);
                Core.translateNode(wrap);
                updateOrderInputs();
                return wrap;
            };

            listWrap.addEventListener('dragover', e => {
                e.preventDefault(); e.dataTransfer.dropEffect = 'move';
                if (!dragSource) return;
                const target = document.elementFromPoint(e.clientX, e.clientY)?.closest('.image-preview-item');
                if (!target || target === dragSource || target === placeholder) {
                    ensurePlaceholder();
                    if (placeholder.parentElement !== listWrap) listWrap.appendChild(placeholder);
                }
            });
            listWrap.addEventListener('drop', e => {
                e.preventDefault(); e.stopPropagation();
                if (!dragSource) return;
                placeholder?.parentElement ? listWrap.insertBefore(dragSource, placeholder) : listWrap.appendChild(dragSource);
                dragSource.classList.remove('dragging'); removePlaceholder();
                placeholder = null; dragSource = null; syncInput(); updateOrderInputs();
            });
            listWrap.addEventListener('dragleave', e => {
                if (!listWrap.contains(e.relatedTarget)) removePlaceholder();
            });

            existingImages.forEach((path, i) => createCard({ key: path, src: storageBase + path, isNew: false, label: path, altText: existingAlts[i] || '' }));
            if (existingImages.length) fileInput.removeAttribute('required');
            updateOrderInputs();

            fileInput.addEventListener('change', () => {
                if (!fileInput.files.length) return;
                [...fileInput.files].forEach(f => {
                    createCard({ key: `new-${nextNewIdx}`, src: URL.createObjectURL(f), isNew: true, label: f.name, altText: '' });
                    newFiles.push({ index: nextNewIdx++, file: f });
                });
                syncInput(); updateOrderInputs();
                fileInput.value = '';
            });

            form.addEventListener('submit', () => { Core.tinymceSave(); syncInput(); updateOrderInputs(); });
        },
    };