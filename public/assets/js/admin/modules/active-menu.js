
export const ActiveMenu = {
        init() {
            const path = location.pathname;
            document.querySelectorAll('.nav-menu a').forEach(a => {
                if (a.href.includes(path)) a.parentElement?.classList.add('active');
            });
        },
    };

    // =========================================================================
    // MODULE: Menu Search
    // =========================================================================
