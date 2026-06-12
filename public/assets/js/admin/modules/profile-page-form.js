import { Core } from '../core/core.js';

export const ProfilePageForm = {
        init() {
            const form = document.getElementById('profilePageForm');
            if (!form) return;

            const typeInput = document.getElementById('type') || form.querySelector('input[name="type"]');
            const map = { cover: 'coverFields', visi_misi: 'visiMisiFields', profile: 'profileFields' };

            const refresh = () => {
                const t = typeInput?.value;
                form.querySelectorAll('.type-fields').forEach(s => {
                    s.classList.add('d-none');
                    s.querySelectorAll('input,textarea,select').forEach(el => el.disabled = true);
                });
                const active = document.getElementById(map[t]);
                if (active) {
                    active.classList.remove('d-none');
                    active.querySelectorAll('input,textarea,select').forEach(el => el.disabled = false);
                }
            };

            if (typeInput?.tagName === 'SELECT') typeInput.addEventListener('change', refresh);

            form.querySelectorAll('input[type="file"][data-preview-target]').forEach(input => {
                input.addEventListener('change', async () => {
                    const file = input.files?.[0];
                    const prev = document.getElementById(input.dataset.previewTarget);
                    if (!file?.type.startsWith('image/') || !prev) return;
                    prev.src = await Core.readFileAsDataURL(file);
                    prev.classList.remove('d-none');
                });
            });

            refresh();
        },
    };