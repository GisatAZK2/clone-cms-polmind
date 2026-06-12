import { Core } from '../core/core.js';

export const DosenForm = {
        init() {
            const form        = document.getElementById('multiDosenForm');
            const formsWrap   = document.getElementById('formsContainer');
            const prevsWrap   = document.getElementById('previewsContainer');
            const addBtn      = document.getElementById('btnAddForm');
            const formTpl     = document.getElementById('formTemplate');
            const prevTpl     = document.getElementById('previewTemplate');
            if (!form || !formsWrap || !prevsWrap || !addBtn || !formTpl || !prevTpl) return;

            let counter = 1;

            const initEditor = name => {
                if (typeof tinymce === 'undefined') return;
                const ta = document.querySelector(`textarea[name="${name}"]`);
                if (!ta) return;
                if (!ta.id) ta.id = 'editor-' + name.replace(/[^a-zA-Z0-9_-]/g, '_');
                if (!tinymce.get(ta.id)) Core.initTinyMCE('#' + ta.id, { height: 300, menubar: false });
            };

            const bindLive = (formEl, idx) => {
                const nameIn  = formEl.querySelector(`input[name="dosen[${idx}][name]"]`);
                const photoIn = formEl.querySelector(`input[name="dosen[${idx}][url_image]"]`);
                const prevNm  = document.getElementById(`previewName_${idx}`);
                const prevPh  = document.getElementById(`previewPhoto_${idx}`);
                const prevHld = document.getElementById(`previewPlaceholder_${idx}`);
                nameIn?.addEventListener('input', () => {
                    if (prevNm) { prevNm.removeAttribute('data-translate'); prevNm.textContent = nameIn.value.trim() || 'Nama Dosen'; }
                });
                Core.bindImagePreview(photoIn, prevPh, prevHld);
            };

            // Preview awal untuk dosen ke-0
            const w0 = document.createElement('div');
            w0.innerHTML = prevTpl.innerHTML.replace(/__INDEX__/g, '0').trim();
            const p0 = w0.firstElementChild;
            prevsWrap.appendChild(p0);
            Core.translateNode(p0);
            initEditor('dosen[0][deskripsi]');
            bindLive(document.querySelector('.dosen-form'), 0);

            // Existing data
            const exName  = prevsWrap.dataset.existingName  || '';
            const exImage = prevsWrap.dataset.existingImage || '';
            if (exName) { const el = document.getElementById('previewName_0'); if (el) { el.removeAttribute('data-translate'); el.textContent = exName; } }
            if (exImage) {
                const ph = document.getElementById('previewPhoto_0');
                const hl = document.getElementById('previewPlaceholder_0');
                if (ph && hl) { ph.src = exImage; ph.classList.remove('d-none'); hl.classList.add('d-none'); }
            }

            addBtn.addEventListener('click', () => {
                const i = counter++;
                const fw = document.createElement('div');
                fw.innerHTML = formTpl.innerHTML.replace(/__INDEX__/g, i).trim();
                const nf = fw.firstElementChild;
                formsWrap.appendChild(nf);
                Core.translateNode(nf);

                const pw = document.createElement('div');
                pw.innerHTML = prevTpl.innerHTML.replace(/__INDEX__/g, i).trim();
                const np = pw.firstElementChild;
                prevsWrap.appendChild(np);
                Core.translateNode(np);

                bindLive(nf, i);
                initEditor(`dosen[${i}][deskripsi]`);
            });

            form.addEventListener('submit', Core.tinymceSave);
        },
    };