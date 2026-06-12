import { Core } from '../core/core.js';

export const MenuSearch = {
        init() {
            const input   = document.getElementById('menuSearch');
            const results = document.getElementById('searchResults');
            const clear   = document.getElementById('searchClear');
            if (!input || !results) return;

            const data = [...document.querySelectorAll('.nav-menu a')].reduce((acc, a) => {
                const label = a.querySelector('.nav-label');
                if (label) acc.push({
                    text: label.textContent.trim(),
                    href: a.href,
                    icon: a.querySelector('.nav-icon')?.className ?? 'bi bi-link',
                });
                return acc;
            }, []);

            const show = q => {
                const matches = data.filter(d => d.text.toLowerCase().includes(q));
                results.innerHTML = matches.length
                    ? matches.map(r => `<a href="${r.href}" class="search-result-item">
                        <i class="${r.icon}"></i>
                        <span>${r.text.replace(new RegExp(`(${q})`, 'gi'), '<strong>$1</strong>')}</span>
                      </a>`).join('')
                    : `<div class="search-no-results">${Core.t('menuSearch.noResults', 'Menu tidak ditemukan')}</div>`;
                results.style.display = 'block';
            };

            input.addEventListener('input', e => {
                const q = e.target.value.toLowerCase().trim();
                if (clear) clear.style.display = q ? 'flex' : 'none';
                q ? show(q) : (results.style.display = 'none');
            });

            clear?.addEventListener('click', () => {
                input.value = ''; input.focus();
                results.style.display = 'none';
                if (clear) clear.style.display = 'none';
            });

            document.addEventListener('click', e => {
                if (!e.target.closest('.sidebar-search')) results.style.display = 'none';
            });
        },
    };