import { Core } from '../core/core.js';

export const ProdiForm = {
        init() {
            const form       = document.getElementById('mainForm');
            const deskripsiSection = document.getElementById('deskripsiSection');
            const addBtn     = document.getElementById('addDeskripsi');
            const container  = document.getElementById('deskripsiContainer');
            if (!form || !deskripsiSection || !container) return;

            let idx = container.querySelectorAll('.deskripsi-row').length;

            const initEditor = ta => {
                if (!ta || typeof tinymce === 'undefined') return;
                if (!ta.id) ta.id = 'prodi-deskripsi-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
                if (!tinymce.get(ta.id)) Core.initTinyMCE('#' + ta.id, { height: 400, menubar: 'file edit view insert format tools table help', setup: ed => ed.on('change keyup', () => ed.save()) });
            };

            const getType = () => {
                return (form.querySelector('input[name="type"]:checked') || form.querySelector('input[name="existing_type"]'))?.value || '';
            };

            const toggle = () => {
                const isCard = getType() === 'card';
                deskripsiSection.style.display = isCard ? '' : 'none';
                deskripsiSection.querySelectorAll('input,textarea,select,button').forEach(el => el.disabled = !isCard);
                document.getElementById('typeWarning')?.classList.remove('d-none');
            };

            form.querySelector('#typeImage')?.addEventListener('change', toggle);
            form.querySelector('#typeCard')?.addEventListener('change', toggle);

            addBtn?.addEventListener('click', () => {
                document.getElementById('emptyDeskripsi')?.remove();
                const eid = `prodi-deskripsi-${idx}`;
                const row = document.createElement('div');
                row.className = 'deskripsi-row mb-3';
                row.innerHTML = `
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold">Deskripsi ${idx + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-deskripsi flex-shrink-0"><i class="bi bi-trash"></i></button>
                  </div>
                  <textarea name="deskripsi[${idx}]" id="${eid}" class="tinymce-editor form-control" rows="6"></textarea>`;
                container.appendChild(row);
                Core.translateNode(row);
                initEditor(row.querySelector('textarea'));
                idx++;
            });

            container.addEventListener('click', e => {
                const btn = e.target.closest('.remove-deskripsi');
                if (!btn) return;
                const row = btn.closest('.deskripsi-row');
                const ta  = row?.querySelector('textarea');
                if (ta && typeof tinymce !== 'undefined') tinymce.get(ta.id)?.remove();
                row?.remove();
                if (!container.querySelectorAll('.deskripsi-row').length) {
                    const empty = document.createElement('div');
                    empty.id = 'emptyDeskripsi'; empty.className = 'alert alert-info';
                    empty.innerHTML = `<i class="bi bi-info-circle"></i> <span data-translate="prodi.emptyDeskripsi">Belum ada deskripsi. Klik "Tambah Baris".</span>`;
                    container.appendChild(empty); Core.translateNode(empty);
                }
            });

            form.addEventListener('submit', Core.tinymceSave);
            container.querySelectorAll('textarea').forEach(initEditor);
            toggle();
        },
    };