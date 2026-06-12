
export const HomeStatForm = {
        async init() {
            const input = document.getElementById('iconInput');
            const box   = document.getElementById('iconSuggestions');
            if (!input || !box) return;

            let icons = [];
            try {
                const res = await fetch('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.json');
                if (res.ok) icons = Object.keys(await res.json()).map(k => `bi bi-${k}`);
            } catch (_) {
                icons = ['bi bi-graph-up','bi bi-trophy','bi bi-award','bi bi-mortarboard','bi bi-handshake'];
            }

            input.addEventListener('input', () => {
                const kw = input.value.toLowerCase().trim();
                if (!kw) { box.style.display = 'none'; return; }
                const filtered = icons.filter(i => i.includes(kw)).slice(0, 30);
                box.innerHTML = filtered.map(ic =>
                    `<a href="#" class="list-group-item list-group-item-action d-flex align-items-center py-2" data-icon="${ic}">
                        <i class="${ic} me-3 fs-4"></i><span>${ic}</span>
                    </a>`
                ).join('');
                box.style.display = filtered.length ? 'block' : 'none';
            });

            box.addEventListener('click', e => {
                const a = e.target.closest('[data-icon]');
                if (!a) return;
                e.preventDefault();
                input.value = a.dataset.icon;
                box.style.display = 'none';
            });

            document.addEventListener('click', e => {
                if (!input.contains(e.target) && !box.contains(e.target)) box.style.display = 'none';
            });
        },
    };