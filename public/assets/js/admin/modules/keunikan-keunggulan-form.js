import { Core } from '../core/core.js';

export const KeunikanKeunggulanForm = {
        init() {
            const form     = document.getElementById('mainForm');
            const container = document.getElementById('blockContainer');
            const addPara  = document.getElementById('addKeunikanParagraphBlock');
            const addImg   = document.getElementById('addKeunikanImageBlock');
            if (!form || !container || !addPara || !addImg) return;

            const storageBase = container.dataset.storageBase || '/storage';
            let paraIdx = 0, imgIdx = 0;
            let editBlocks = [];
            try { editBlocks = JSON.parse(container.dataset.editBlocks || '[]'); } catch (_) {}

            const tinyConfig = id => ({
                selector: '#' + id,
                license_key: 'gpl',
                height: 400,
                menubar: 'file edit view insert format tools table help',
                plugins: ['advlist','autolink','lists','link','charmap','preview','anchor',
                    'searchreplace','visualblocks','code','fullscreen','insertdatetime',
                    'media','table','help','wordcount'],
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
                    'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | help',
                content_style: 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;font-size:16px}',
                promotion: false,
                setup: ed => ed.on('change', () => ed.save()),
            });

            const refreshButtons = () => {
                [...container.querySelectorAll(':scope>.block-card')].forEach((card, i, arr) => {
                    card.querySelector('.btn-move-up')  && (card.querySelector('.btn-move-up').disabled  = i === 0);
                    card.querySelector('.btn-move-down') && (card.querySelector('.btn-move-down').disabled = i === arr.length - 1);
                });
            };

            const renderBlock = block => {
                const wrap = document.createElement('div');
                wrap.className = 'block-card';

                if (block.type === 'paragraph') {
                    const li = paraIdx++;
                    const eid = `editor-paragraphs_${li}`;
                    wrap.innerHTML = `
                      <div class="block-header">
                        <span class="fw-semibold small"><i class="bi bi-paragraph block-badge-paragraph me-1"></i><span data-translate="keunikanKeunggulan.blockParagraph">Paragraf</span></span>
                        <div class="d-flex gap-1 align-items-center">
                          <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 btn-move-up" data-action="move-up">↑</button>
                          <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 btn-move-down" data-action="move-down">↓</button>
                          <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" data-action="remove-block"><i class="bi bi-x-lg"></i></button>
                        </div>
                      </div>
                      <div class="block-body">
                        <input type="hidden" name="content_keys[]" value="paragraph">
                        <div class="form-group">
                          <label class="form-label">Paragraf</label>
                          <textarea id="${eid}" name="paragraphs[]" class="tinymce-editor" aria-required="true" data-required="true">${Core.escapeAttr(block.value ?? '')}</textarea>
                        </div>
                      </div>`;
                    container.appendChild(wrap);
                    Core.translateNode(wrap);
                    if (typeof tinymce !== 'undefined') {
                        tinymce.get(eid) && tinymce.remove('#' + eid);
                        tinymce.init(tinyConfig(eid));
                    }
                }

                if (block.type === 'image') {
                    const li = imgIdx++;
                    const hasImg = block.path?.length > 0;
                    const pid = `img_preview_${li}`;
                    const fid = `img_file_${li}`;
                    wrap.innerHTML = `
                      <div class="block-header">
                        <span class="fw-semibold small"><i class="bi bi-image block-badge-image me-1"></i><span data-translate="keunikanKeunggulan.blockImage">Gambar</span></span>
                        <div class="d-flex gap-1 align-items-center">
                          <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 btn-move-up" data-action="move-up">↑</button>
                          <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 btn-move-down" data-action="move-down">↓</button>
                          <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" data-action="remove-block"><i class="bi bi-x-lg"></i></button>
                        </div>
                      </div>
                      <div class="block-body">
                        <input type="hidden" name="content_keys[]" value="image">
                        <input type="hidden" name="image_keeps[]" value="${Core.escapeAttr(block.path ?? '')}">
                        <input type="hidden" name="image_deletes[]" value="0" class="img-delete-flag">
                        ${hasImg ? `<div class="mb-2" id="existing_wrap_${li}">
                          <p class="small text-muted mb-1" data-translate="keunikanKeunggulan.currentImage">Gambar saat ini:</p>
                          <img src="${storageBase}/${block.path}" alt="${Core.escapeAttr(block.alt ?? '')}" class="img-thumbnail mb-2" style="max-width:160px;max-height:130px;object-fit:cover">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="del_chk_${li}" data-action="toggle-delete-image">
                            <label class="form-check-label text-danger small" for="del_chk_${li}"><span data-translate="keunikanKeunggulan.deleteImage">Hapus gambar ini</span></label>
                          </div>
                        </div>` : ''}
                        <div class="mb-2">
                          <label class="form-label small fw-semibold" data-translate="${hasImg ? 'keunikanKeunggulan.replaceImageOptional' : 'keunikanKeunggulan.uploadImage'}">${hasImg ? 'Ganti Gambar (opsional)' : 'Upload Gambar'}</label>
                          <input type="file" id="${fid}" name="images[${li}]" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,image/gif" data-preview-target="${pid}">
                          <small class="text-muted"><span data-translate="common.maxSize">Maksimal 2MB</span> &bull; JPG, PNG, GIF</small>
                        </div>
                        <img id="${pid}" src="#" alt="Preview" class="img-thumbnail d-none mb-2" style="max-width:160px;max-height:130px;object-fit:cover">
                        <div>
                          <label class="form-label small fw-semibold" data-translate="keunikanKeunggulan.altText">Alt Text</label>
                          <input type="text" name="image_alts[]" class="form-control form-control-sm" value="${Core.escapeAttr(block.alt ?? '')}" placeholder="Deskripsi gambar" data-translate-placeholder="keunikanKeunggulan.altPlaceholder">
                        </div>
                      </div>`;
                    container.appendChild(wrap);
                    Core.translateNode(wrap);
                }

                refreshButtons();
            };

            container.addEventListener('click', e => {
                const action = e.target.closest('[data-action]')?.dataset.action;
                if (!action) return;
                if (action === 'remove-block') {
                    const card = e.target.closest('.block-card');
                    const ta   = card.querySelector('textarea');
                    if (ta && typeof tinymce !== 'undefined') tinymce.get(ta.id)?.remove();
                    card.remove(); refreshButtons();
                }
                if (action === 'move-up' || action === 'move-down') {
                    const card  = e.target.closest('.block-card');
                    const cards = [...container.querySelectorAll(':scope>.block-card')];
                    const ci    = cards.indexOf(card);
                    const ti    = ci + (action === 'move-up' ? -1 : 1);
                    if (ti < 0 || ti >= cards.length) return;
                    Core.tinymceSave();
                    [card, cards[ti]].forEach(c => {
                        const ta = c.querySelector('textarea');
                        if (ta && typeof tinymce !== 'undefined') tinymce.remove('#' + ta.id);
                    });
                    action === 'move-up' ? container.insertBefore(card, cards[ti]) : container.insertBefore(cards[ti], card);
                    refreshButtons();
                    container.querySelectorAll('textarea').forEach(ta => {
                        if (typeof tinymce !== 'undefined' && !tinymce.get(ta.id)) tinymce.init(tinyConfig(ta.id));
                    });
                }
            });

            container.addEventListener('change', e => {
                if (e.target.matches('input[type="file"][data-preview-target]')) {
                    const file = e.target.files?.[0];
                    const prev = document.getElementById(e.target.dataset.previewTarget);
                    if (file && prev) { prev.src = URL.createObjectURL(file); prev.classList.remove('d-none'); }
                }
                if (e.target.matches('[data-action="toggle-delete-image"]')) {
                    const card    = e.target.closest('.block-card');
                    const flag    = card.querySelector('.img-delete-flag');
                    const fileIn  = card.querySelector('input[type="file"]');
                    if (flag)   flag.value = e.target.checked ? '1' : '0';
                    if (fileIn) fileIn.disabled = e.target.checked;
                }
            });

            addPara.addEventListener('click', () => renderBlock({ type: 'paragraph' }));
            addImg.addEventListener('click',  () => renderBlock({ type: 'image' }));
            form.addEventListener('submit', Core.tinymceSave);
            editBlocks.forEach(renderBlock);
        },
    };