import { Core } from '../core/core.js';

export const DynamicBlocks = {
        init() {
            const container  = document.getElementById('blocksContainer');
            const emptyDiv   = document.getElementById('emptyBlocks');
            const addTextBtn = document.getElementById('addTextBlock');
            const addImgBtn  = document.getElementById('addImageBlock');
            const titleInput = document.getElementById('title');
            const newsForm   = document.getElementById('newsForm');
            if (!container) return;

            let counter = 0;
            const imageCache = {};

            // Hitung ulang counter dari blok yang sudah ada
            document.querySelectorAll('.block-item').forEach(b => {
                const idx = parseInt(b.dataset.blockIndex, 10);
                if (idx >= counter) counter = idx + 1;
            });

            // Cache gambar existing
            document.querySelectorAll('.block-item').forEach(b => {
                const existing = b.querySelector('input[name*="existing_image"]');
                if (existing?.value) imageCache[b.dataset.blockIndex] = { type: 'storage', src: existing.value };
            });

            const updateCount = () => {
                const el = document.getElementById('blockCount');
                if (el) el.textContent = container.querySelectorAll('.block-item').length;
            };

            const updatePreview = () => {
                Core.tinymceSave();
                const title = titleInput?.value.trim() || Core.t('news.previewUntitled', 'Untitled');
                document.getElementById('previewTitle')?.replaceChildren(document.createTextNode(title));
                document.getElementById('fullPreviewTitle')?.replaceChildren(document.createTextNode(title));

                let mini = '', full = '';
                container.querySelectorAll('.block-item').forEach(b => {
                    const idx  = b.dataset.blockIndex;
                    const type = b.querySelector('input[name*="[type]"]')?.value;
                    if (type === 'text') {
                        const ed = typeof tinymce !== 'undefined' ? tinymce.get(`editor-block-${idx}`) : null;
                        const c  = ed ? ed.getContent() : '';
                        mini += `<div style="margin-bottom:1rem;font-size:.85rem;color:#666;line-height:1.5">${c}</div>`;
                        full += `<div style="margin-bottom:1.5rem;line-height:1.8;color:#333;font-size:1rem">${c}</div>`;
                    } else if (type === 'image') {
                        const alt    = b.querySelector('textarea[name*="[alt]"]')?.value || Core.t('common.image', 'Gambar');
                        const cached = imageCache[idx];
                        if (cached) {
                            const src = cached.type === 'dataurl' ? cached.src : `/storage/${cached.src}`;
                            mini += `<div style="margin-bottom:1rem"><img src="${src}" style="max-width:100%;max-height:120px;border-radius:4px;object-fit:cover" alt="${Core.escapeAttr(alt)}"></div>`;
                            full += `<figure style="margin:1.5rem 0"><img src="${src}" alt="${Core.escapeAttr(alt)}" style="max-width:100%;height:auto;border-radius:8px"></figure>`;
                        }
                    }
                });

                const empty = `<em class="text-muted">${Core.t('news.previewNoContent', 'Belum ada konten')}</em>`;
                const pc = document.getElementById('previewContent');
                const fp = document.getElementById('fullPreviewBlocks');
                if (pc) pc.innerHTML = mini || empty;
                if (fp) fp.innerHTML = full || empty;
            };

            const initEditor = idx => {
                if (typeof tinymce === 'undefined') return;
                tinymce.init({
                    selector: `textarea[id^="editor-block-"]`,
                    license_key: 'gpl',
                    height: 400,
                    menubar: 'file edit view insert format tools table help',
                    plugins: ['advlist','autolink','lists','link','charmap','preview','anchor',
                        'searchreplace','visualblocks','code','fullscreen','insertdatetime',
                        'media','table','help','wordcount'],
                    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | link | help',
                    content_style: 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;font-size:16px}',
                    promotion: false,
                });
            };



            // Compatibility untuk inline handler lama di Blade: onchange="previewImageBlock(event, index)".
            // Event utama tetap dibind lewat delegation di module ini.
            window.previewImageBlock = async (event, idx) => {
                const input = event?.target;
                const block = input?.closest?.('.block-item') || container.querySelector(`[data-block-index="${idx}"]`);
                const file = input?.files?.[0];
                if (!block || !file) return;
                const url = await Core.readFileAsDataURL(file);
                imageCache[String(idx)] = { type: 'dataurl', src: url };
                const prev = block.querySelector('.image-preview');
                if (prev) {
                    prev.style.display = 'block';
                    prev.innerHTML = `<img src="${url}" style="max-width:100%;max-height:200px;border-radius:6px" alt="Preview">`;
                }
                updatePreview();
            };

            const addBlock = type => {
                const idx = counter++;
                const html = type === 'text'
                    ? `<div class="block-item" data-block-index="${idx}">
                        <div class="block-header">
                          <span class="block-badge bg-info" data-translate="news.blockParagraph">Paragraf</span>
                          <button type="button" class="btn btn-sm btn-link text-danger delete-block" title="Hapus" data-translate-title="common.delete"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="block-content">
                          <textarea id="editor-block-${idx}" name="blocks[${idx}][content]"></textarea>
                          <input type="hidden" name="blocks[${idx}][type]" value="text">
                        </div>
                      </div>`
                    : `<div class="block-item" data-block-index="${idx}">
                        <div class="block-header">
                          <span class="block-badge bg-success" data-translate="news.blockImage">Gambar</span>
                          <button type="button" class="btn btn-sm btn-link text-danger delete-block" title="Hapus" data-translate-title="common.delete"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="block-content">
                          <div class="row g-2 g-md-3">
                            <div class="col-12 col-md-6">
                              <label class="form-label" data-translate="common.image">Gambar</label>
                              <input type="file" class="form-control form-control-sm image-input" name="blocks[${idx}][image]" accept="image/jpeg,image/png,image/webp" required>
                              <small class="text-muted d-block mt-2" data-translate="news.imageFormat">JPG, PNG, WEBP. Maks 2MB</small>
                              <div class="image-preview mt-3"></div>
                            </div>
                            <div class="col-12 col-md-6">
                              <label class="form-label"><span data-translate="common.altText">Alt Text</span> <span class="text-danger">*</span></label>
                              <textarea class="form-control form-control-sm" name="blocks[${idx}][alt]" rows="3" placeholder="Deskripsi gambar" data-translate-placeholder="news.altPlaceholder" required></textarea>
                            </div>
                          </div>
                          <input type="hidden" name="blocks[${idx}][type]" value="image">
                        </div>
                      </div>`;

                const wrap = document.createElement('div');
                wrap.innerHTML = html.trim();
                const block = wrap.firstElementChild;
                container.appendChild(block);
                Core.translateNode(block);
                if (emptyDiv) emptyDiv.style.display = 'none';
                if (type === 'text') initEditor(idx);
                updateCount();
                updatePreview();
            };

            addTextBtn?.addEventListener('click', e => { e.preventDefault(); addBlock('text'); });
            addImgBtn?.addEventListener('click', e => { e.preventDefault(); addBlock('image'); });

            container.addEventListener('click', e => {
                const btn = e.target.closest('.delete-block');
                if (!btn) return;
                e.preventDefault();
                const block = btn.closest('.block-item');
                const idx   = block.dataset.blockIndex;
                if (typeof tinymce !== 'undefined') tinymce.get(`editor-block-${idx}`)?.remove();
                delete imageCache[idx];
                block.remove();
                updateCount();
                updatePreview();
                if (!container.children.length && emptyDiv) emptyDiv.style.display = 'block';
            });

            container.addEventListener('change', async e => {
                if (!e.target.classList.contains('image-input')) return;
                const fi    = e.target;
                const block = fi.closest('.block-item');
                const idx   = block.dataset.blockIndex;
                const file  = fi.files?.[0];
                if (!file) return;
                const url = await Core.readFileAsDataURL(file);
                imageCache[idx] = { type: 'dataurl', src: url };
                const prev = block.querySelector('.image-preview');
                if (prev) { prev.style.display = 'block'; prev.innerHTML = `<img src="${url}" style="max-width:100%;max-height:200px;border-radius:6px" alt="Preview">`; }
                updatePreview();
            });

            document.addEventListener('input', e => {
                if (e.target.name?.includes('[alt]')) updatePreview();
            });

            titleInput?.addEventListener('input', updatePreview);

            document.getElementById('previewModal')?.addEventListener('show.bs.modal', () => {
                setTimeout(updatePreview, 50);
            });

            newsForm?.addEventListener('submit', e => {
                if (!container.children.length) {
                    e.preventDefault();
                    alert(Core.t('news.minBlockRequired', 'Minimal tambahkan 1 blok konten!'));
                    return;
                }
                Core.tinymceSave();
            });

            container.querySelectorAll('[id^="editor-block-"]').forEach(el => initEditor(el.id));
            updateCount();
            updatePreview();
        },
    };