(function () {
    'use strict';

    const STORAGE_KEY = 'sidebar_collapsed';
    const SIDEBAR_SCROLL_KEY = 'admin_sidebar_scroll_top';
    const SIDEBAR_ACTIVE_KEY = 'admin_sidebar_active_href';
    const MOBILE_BREAKPOINT = 768;

    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const collapseBtn = document.getElementById('sidebarCollapseBtn');
    const mainContent = document.querySelector('.main-content');

    if (!sidebar) return;

    const sidebarNav = sidebar.querySelector('nav');

    function isMobile() {
        return window.innerWidth <= MOBILE_BREAKPOINT;
    }

    function isStoredCollapsed() {
        return localStorage.getItem(STORAGE_KEY) === 'true';
    }

    function saveSidebarScroll() {
        if (!sidebarNav) return;
        localStorage.setItem(SIDEBAR_SCROLL_KEY, String(sidebarNav.scrollTop));
    }

    function clearSidebarPrepaint() {
        document.documentElement.classList.remove('sidebar-scroll-prepaint');
        document.documentElement.style.removeProperty('--sidebar-prepaint-scroll');
    }

    function restoreSidebarScroll() {
        if (!sidebarNav) return;

        sidebarNav.style.scrollBehavior = 'auto';

        const savedScroll = localStorage.getItem(SIDEBAR_SCROLL_KEY);

        if (savedScroll !== null) {
            sidebarNav.scrollTop = Number(savedScroll) || 0;
            clearSidebarPrepaint();
            return;
        }

        const activeMenu = sidebarNav.querySelector('.nav-menu li.active');

        if (activeMenu) {
            const activeTop = activeMenu.offsetTop;
            const activeHeight = activeMenu.offsetHeight;
            const navHeight = sidebarNav.clientHeight;

            sidebarNav.scrollTop = activeTop - (navHeight / 2) + (activeHeight / 2);
        }

        clearSidebarPrepaint();
    }

    function saveActiveMenuLink(link) {
        if (!link) return;

        const href = link.getAttribute('href');

        if (href) {
            localStorage.setItem(SIDEBAR_ACTIVE_KEY, href);
        }
    }

    function updateCollapseIcon(collapsed) {
        if (!collapseBtn) return;

        const icon = collapseBtn.querySelector('i');
        if (!icon) return;

        icon.className = collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
        collapseBtn.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
    }

    function applyDesktopState(collapsed, animate) {
        document.documentElement.classList.toggle('sidebar-collapsed', collapsed);

        if (!animate) {
            sidebar.style.transition = 'none';

            if (mainContent) {
                mainContent.style.transition = 'none';
            }
        }

        if (collapsed) {
            sidebar.classList.add('collapsed');
            sidebar.classList.remove('show');

            if (mainContent) {
                mainContent.style.marginLeft = 'var(--sidebar-collapsed-width)';
            }
        } else {
            sidebar.classList.remove('collapsed');

            if (mainContent) {
                mainContent.style.marginLeft = 'var(--sidebar-width)';
            }
        }

        updateCollapseIcon(collapsed);

        if (!animate) {
            void sidebar.offsetHeight;

            sidebar.style.transition = '';

            if (mainContent) {
                mainContent.style.transition = '';
            }
        }
    }

    function setMobileOverlay(open) {
        document.body.classList.toggle('sidebar-mobile-open', open);
    }

    function handleToggle() {
        if (isMobile()) {
            sidebar.classList.remove('collapsed');

            const isOpen = sidebar.classList.toggle('show');

            setMobileOverlay(isOpen);

            if (isOpen) {
                restoreSidebarScroll();
            } else {
                saveSidebarScroll();
            }

            return;
        }

        saveSidebarScroll();

        const willCollapse = !sidebar.classList.contains('collapsed');

        localStorage.setItem(STORAGE_KEY, String(willCollapse));

        applyDesktopState(willCollapse, true);

        if (!willCollapse) {
            restoreSidebarScroll();
        }
    }

    function handleCollapseBtn() {
        saveSidebarScroll();

        if (isMobile()) {
            sidebar.classList.remove('show');
            setMobileOverlay(false);
            return;
        }

        localStorage.setItem(STORAGE_KEY, 'true');
        applyDesktopState(true, true);
    }

    document.addEventListener('click', function (e) {
        if (!isMobile()) return;
        if (!sidebar.classList.contains('show')) return;
        if (sidebar.contains(e.target)) return;
        if (toggleBtn && toggleBtn.contains(e.target)) return;

        sidebar.classList.remove('show');
        setMobileOverlay(false);
    });

    if (isMobile()) {
        document.documentElement.classList.remove('sidebar-collapsed');

        sidebar.classList.remove('collapsed', 'show');
        setMobileOverlay(false);

        if (mainContent) {
            mainContent.style.marginLeft = '0';
        }

        restoreSidebarScroll();
    } else {
        setMobileOverlay(false);
        applyDesktopState(isStoredCollapsed(), false);
        restoreSidebarScroll();
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', handleToggle);
    }

    if (collapseBtn) {
        collapseBtn.addEventListener('click', handleCollapseBtn);
    }

    let resizeTimer;

    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {
            if (isMobile()) {
                document.documentElement.classList.remove('sidebar-collapsed');

                sidebar.classList.remove('collapsed', 'show');
                setMobileOverlay(false);

                if (mainContent) {
                    mainContent.style.marginLeft = '0';
                }
            } else {
                setMobileOverlay(false);
                applyDesktopState(isStoredCollapsed(), false);
            }
        }, 100);
    });

    if (sidebarNav) {
        let scrollTimer;

        sidebarNav.addEventListener('scroll', function () {
            clearTimeout(scrollTimer);

            scrollTimer = setTimeout(function () {
                saveSidebarScroll();
            }, 80);
        });

        sidebarNav.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                saveSidebarScroll();
                saveActiveMenuLink(link);
            });
        });

        restoreSidebarScroll();
    }
})();