import { Core } from '../core/core.js';

export const ReorderList = {
        /**
         * @param {Object} opts
         * @param {string} opts.formId
         * @param {string} opts.inputId
         * @param {string} opts.saveBtnId
         * @param {string|null} opts.tableBodySelector
         * @param {string|null} opts.mobileListId
         * @param {string} opts.rowClass        — class pada baris desktop
         * @param {string} opts.cardClass        — class pada card mobile
         * @param {string} opts.numberSelector   — selector untuk label nomor urut
         */
        create(opts) {
            const form    = document.getElementById(opts.formId);
            const input   = document.getElementById(opts.inputId);
            const saveBtn = document.getElementById(opts.saveBtnId);
            if (!form || !input || !saveBtn) return;

            const tableBody  = opts.tableBodySelector
                ? document.querySelector(opts.tableBodySelector) : null;
            const mobileList = opts.mobileListId
                ? document.getElementById(opts.mobileListId) : null;

            const isMobile = () => window.innerWidth < 768;

            const getIds = () => {
                const source = (isMobile() && mobileList) ? mobileList : tableBody;
                if (!source) return [];
                return [...source.querySelectorAll(
                    isMobile() ? `.${opts.cardClass}` : `.${opts.rowClass}`
                )].map(el => el.dataset.id);
            };

            const updateLabels = () => {
                tableBody?.querySelectorAll(`.${opts.rowClass}`).forEach((row, i) => {
                    const num = row.querySelector(opts.numberSelector);
                    const lbl = row.querySelector('small.text-muted.d-block');
                    if (num) num.textContent = i + 1;
                    if (lbl) lbl.textContent = `#${i + 1}`;
                });
                mobileList?.querySelectorAll(`.${opts.cardClass}`).forEach((card, i) => {
                    const badge = card.querySelector('.badge.bg-secondary, .news-mobile-order');
                    if (badge) badge.textContent = `#${i + 1}`;
                });
            };

            const move = (el, dir) => {
                Core.moveElement(el, dir);
                updateLabels();
            };

            // Tombol panah desktop & mobile
            [tableBody, mobileList].forEach(container => {
                if (!container) return;
                container.addEventListener('click', e => {
                    const up   = e.target.closest('.btn-move-up');
                    const down = e.target.closest('.btn-move-down');
                    const rowSel = isMobile() ? `.${opts.cardClass}` : `.${opts.rowClass}`;
                    if (up)   move(up.closest(rowSel),   'up');
                    if (down) move(down.closest(rowSel), 'down');
                });
            });

            // Swipe mobile
            mobileList?.querySelectorAll(`.${opts.cardClass}`).forEach(card => {
                Core.bindSwipeReorder(card, dir => {
                    move(card, dir);
                });
            });

            // Drag-and-drop desktop
            if (tableBody) {
                let dragging = null;
                tableBody.addEventListener('dragstart', e => {
                    dragging = e.target.closest(`.${opts.rowClass}`);
                    if (dragging) setTimeout(() => dragging.classList.add('dragging'), 0);
                });
                tableBody.addEventListener('dragend', () => {
                    dragging?.classList.remove('dragging');
                    tableBody.querySelectorAll('.drag-over').forEach(r => r.classList.remove('drag-over'));
                    dragging = null;
                });
                tableBody.addEventListener('dragover', e => {
                    e.preventDefault();
                    const target = e.target.closest(`.${opts.rowClass}`);
                    if (target && target !== dragging) {
                        tableBody.querySelectorAll('.drag-over').forEach(r => r.classList.remove('drag-over'));
                        target.classList.add('drag-over');
                    }
                });
                tableBody.addEventListener('dragleave', e => {
                    e.target.closest(`.${opts.rowClass}`)?.classList.remove('drag-over');
                });
                tableBody.addEventListener('drop', e => {
                    e.preventDefault();
                    const target = e.target.closest(`.${opts.rowClass}`);
                    if (!target || !dragging || target === dragging) return;
                    target.classList.remove('drag-over');
                    const after = (e.clientY - target.getBoundingClientRect().top) > (target.offsetHeight / 2);
                    tableBody.insertBefore(dragging, after ? target.nextSibling : target);
                    updateLabels();
                });
            }

            saveBtn.addEventListener('click', () => {
                const ids = getIds();
                if (!ids.length) return;
                input.value = ids.join(',');
                form.submit();
            });

            updateLabels();
        },
    };