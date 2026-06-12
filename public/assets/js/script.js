/*----------------------------------------------

[ALL CONTENTS]

1. Header & Mobile Navigation
2. Header Scroll Hide/Show
3. Logo Single/Double Click
4. Mobile Nav Link Close
5. Mobile Dropdown Toggle
6. Outside Click Close Nav
7. Language Switcher
8. Active Nav Highlight
9. Search Dropdown
10. Hero Slider (Autoplay + Drag + Dots)
11. News Slider (Drag + Arrows + Autoplay)
12. News Filter
13. Header Scroll Shadow
14. Floating Buttons (WA + Scroll To Top)
15. Banner PMB Transparency Detection
16. Toggle Kartu Prodi
17. Translation Helper
18. Modal Dosen
19. News List Page Filter
20. News Thumbnail Click/Double Click
21. Person Card Modal Handler
22. Partner Marquee (Autoplay + Drag + Momentum)

----------------------------------------------*/

if (!window.scriptController) window.scriptController = null

document.addEventListener('turbo:load', function () {
    if (window.scriptController) window.scriptController.abort()
    window.scriptController = new AbortController()
    const sig = window.scriptController.signal


    // ============================================================
    // 1. HEADER & MOBILE NAVIGATION
    // ============================================================
    const burgerMenu = document.getElementById('burgerMenu')
    const mobileNav = document.getElementById('mobileNav')

    if (burgerMenu && mobileNav) {
        burgerMenu.addEventListener("click", function (e) {
            e.stopPropagation()
            const isActive = mobileNav.classList.toggle("active")
            burgerMenu.classList.toggle("active", isActive)
            burgerMenu.setAttribute("aria-expanded", isActive)
        }, { signal: sig })
    }

    // Inject header behavior so it runs even when heroSlides is absent
    (function () {
        if (window.innerWidth <= 1024) return;

        const headerMain = document.querySelector('.header-main');
        if (!headerMain) return;

        headerMain.classList.remove('hide-nav');
        headerMain.classList.add('show-nav');

        let lastScrollY = window.scrollY;
        let scrollTimer = null;
        let isHidden = false;
        let ticking = false;

        const SCROLL_THRESHOLD = 80;
        const HIDE_DELTA = 8;
        const IDLE_DELAY = 700;

        function hideNav() {
            if (isHidden) return;
            isHidden = true;
            headerMain.classList.add('hide-nav');
            headerMain.classList.remove('show-nav');
        }

        function showNav() {
            if (!isHidden) return;
            isHidden = false;
            headerMain.classList.remove('hide-nav');
            headerMain.classList.add('show-nav');
        }

        window.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                const currentScrollY = window.scrollY;
                const delta = currentScrollY - lastScrollY;

                if (currentScrollY <= SCROLL_THRESHOLD) {
                    clearTimeout(scrollTimer);
                    showNav();
                } else if (delta > HIDE_DELTA) {
                    hideNav();
                    clearTimeout(scrollTimer);
                    scrollTimer = setTimeout(showNav, IDLE_DELAY);
                } else if (delta < -HIDE_DELTA) {
                    clearTimeout(scrollTimer);
                    showNav();
                }

                lastScrollY = currentScrollY;
                ticking = false;
            });
        }, { passive: true });
    })();


    (function () {
        const logoLink = document.querySelector('.logo-link');
        const logoImg = document.querySelector('.logo-link .logo-img');
        if (!logoLink || !logoImg) return;

        let clickTimer = null;
        const CLICK_DELAY = 300; // ms

        function openLogoModal() {
            if (window.ImageModal && typeof window.ImageModal.open === 'function') {
                window.ImageModal.open(logoImg.src, logoImg.alt || 'Logo');
            } else if (window._globalImageModalInstance && typeof window._globalImageModalInstance.open === 'function') {
                window._globalImageModalInstance.open(logoImg.src, logoImg.alt || 'Logo');
            } else {
                window.open(logoImg.src, '_blank');
            }
        }

        logoLink.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (clickTimer) clearTimeout(clickTimer);

            clickTimer = setTimeout(() => {
                window.location.href = logoLink.href;
                clickTimer = null;
            }, CLICK_DELAY);
        }, true);

        logoLink.addEventListener('dblclick', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (clickTimer) {
                clearTimeout(clickTimer);
                clickTimer = null;
            }

            openLogoModal();
        }, true);

        logoImg.addEventListener('click', function (e) {
            e.stopPropagation();
        }, true);
    })();

    if (mobileNav) {
        mobileNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                if (!this.parentElement?.classList.contains('nav-dropdown')) {
                    mobileNav.classList.remove('active')
                    burgerMenu?.classList.remove('active')
                    burgerMenu?.setAttribute('aria-expanded', 'false')
                }
            })
        })
    }

    // Mobile dropdown toggle
    if (mobileNav) {
        const dropdownToggles = mobileNav.querySelectorAll(".dropdown-toggle");
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                const dropdownMenu = this.closest(".nav-dropdown")?.querySelector(".dropdown-menu");
                const isExpanded = this.getAttribute("aria-expanded") === "true";

                // Close other dropdowns
                dropdownToggles.forEach(otherToggle => {
                    if (otherToggle !== this) {
                        otherToggle.setAttribute("aria-expanded", "false");
                        otherToggle.closest(".nav-dropdown")?.querySelector(".dropdown-menu")?.classList.remove("show-dropdown");
                    }
                });

                // Toggle current dropdown
                if (dropdownMenu) {
                    dropdownMenu.classList.toggle("show-dropdown");
                    this.setAttribute("aria-expanded", !isExpanded);
                }
            });
        });
    }

    // Ensure clicking the profile/menu anchor itself does NOT toggle the dropdown;
    // only the arrow/button should toggle it. Allow navigation but stop propagation
    // so delegated handlers won't treat the anchor as a dropdown toggle.
    document.querySelectorAll('.nav-dropdown .nav-dropdown-header > a').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            // do not prevent default (navigation) but stop propagation
            e.stopPropagation();
        });
    });

    document.addEventListener("click", function (e) {
        if (mobileNav?.classList.contains("active")) {
            // Use tag selector 'header' (not .header class) so clicks inside header are treated correctly
            if (!e.target.closest("header")) {
                mobileNav.classList.remove("active");
                burgerMenu?.classList.remove("active");
                burgerMenu?.setAttribute("aria-expanded", "false");
            }
        }
    });

    // Prevent mobile nav from closing when interacting with language selector
    const desktopLang = document.getElementById('languageSwitcher')
    const mobileLang = document.getElementById('languageSwitcherMobile')
    function setCookie(name, value, days) {
        let expires = ''
        if (days) {
            const date = new Date()
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000)
            expires = '; expires=' + date.toUTCString()
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/'
    }
    function getCookie(name) {
        const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)')
        return v ? v[2] : null
    }

    [desktopLang, mobileLang].forEach(sel => {
        if (!sel) return;
        // stop clicks/touches/changes from bubbling to document (which may close the mobile menu)
        ['click', 'mousedown', 'touchstart', 'focus', 'change'].forEach(evt => {
            sel.addEventListener(evt, function (ev) { ev.stopPropagation(); });
        });
        // when language changed: sync selects, save to localStorage, and apply translation
        sel.addEventListener('change', function () {
            try {
                const val = this.value;
                if (desktopLang) desktopLang.value = val;
                if (mobileLang) mobileLang.value = val;
                // set common cookie names that backends often read
                setCookie('locale', val, 365);
                setCookie('site_lang', val, 365);
                // save to localStorage and apply translation WITHOUT reload
                try {
                    localStorage.setItem('polmind_lang', val);
                } catch (e) { }
                // call switchLanguage from lang.js to apply translations immediately
                if (typeof switchLanguage === 'function') {
                    switchLanguage(val);
                }
            } catch (err) { console.error(err); }
        });
    });

    // init selects from existing cookie if present
    ; (function initLangSelects() {
        const cookieVal = getCookie('locale') || getCookie('site_lang')
        if (cookieVal) {
            if (desktopLang) desktopLang.value = cookieVal
            if (mobileLang) mobileLang.value = cookieVal
        }
    })()

    const currentPath = window.location.pathname.replace(/\/$/, '') || '/'

    /**
     * Cek apakah href link cocok dengan path saat ini.
     * Cocok exact atau sebagai prefix (untuk section/sub-halaman).
     */
    function isActive(href) {
        if (!href || href === '#') return false
        try {
            const linkPath =
                new URL(href, window.location.origin).pathname.replace(
                    /\/$/,
                    ''
                ) || '/'
            // Exact match
            if (linkPath === currentPath) return true
            // Prefix match (misal /tentang aktif untuk /tentang/sejarah)
            if (linkPath !== '/' && currentPath.startsWith(linkPath + '/'))
                return true
        } catch (e) { }
        return false
    }

    function setActiveNav() {
        // ── Desktop nav (.nav-menu) ──
        const navMenus = document.querySelectorAll('.nav-menu')
        navMenus.forEach(function (navMenu) {
            const items = navMenu.querySelectorAll('li')
            items.forEach(function (li) {
                const directLink = li.querySelector(':scope > a')
                const dropdownLinks = li.querySelectorAll('.dropdown-menu li a')

                let matched = false

                // Cek link langsung
                if (directLink && isActive(directLink.getAttribute('href'))) {
                    li.classList.add('active')
                    directLink.classList.add('active')
                    matched = true
                }

                // Cek dropdown children
                dropdownLinks.forEach(function (childLink) {
                    if (isActive(childLink.getAttribute('href'))) {
                        childLink.classList.add('active')
                        childLink.closest('li').classList.add('active')
                        // Tandai parent sebagai active-parent
                        li.classList.add('active-parent')
                        matched = true
                    }
                })
            })
        })

        // ── Mobile nav (.mobile-nav) ──
        const mobileNavs = document.querySelectorAll('.mobile-nav')
        mobileNavs.forEach(function (mobileNav) {
            const items = mobileNav.querySelectorAll('li')
            items.forEach(function (li) {
                const directLink = li.querySelector(':scope > a')
                const dropdownLinks = li.querySelectorAll('.dropdown-menu li a')

                // Cek link langsung
                if (directLink && isActive(directLink.getAttribute('href'))) {
                    li.classList.add('active')
                    directLink.classList.add('active')
                }

                // Cek dropdown children
                dropdownLinks.forEach(function (childLink) {
                    if (isActive(childLink.getAttribute('href'))) {
                        childLink.classList.add('active')
                        childLink.closest('li').classList.add('active')
                        li.classList.add('active-parent')

                        // Auto-buka dropdown mobile jika parent aktif
                        const dropdown = li.querySelector('.dropdown-menu')
                        if (dropdown) dropdown.classList.add('show-dropdown')
                    }
                })
            })
        })
    }

    // Jalankan setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setActiveNav)
    } else {
        setActiveNav()
    }

    // ============================================================
    // 2. SEARCH DROPDOWN & SECTION SEARCH
    // ============================================================
    const searchBtn = document.getElementById('search-icon')
    const mobileSearchBtn = document.getElementById('mobile-search-btn')
    const searchDropdown = document.getElementById('searchDropdown')
    const searchInput = document.getElementById('searchInput')
    const searchResults = document.getElementById('searchResults')

    const searchItems = [
        { title: 'Beranda', description: 'Halaman utama', url: '/beranda' },
        {
            title: 'Profile',
            description: 'Informasi profil dan visi misi',
            url: '/profil'
        },
        {
            title: 'Keunikan dan Keunggulan',
            description: 'Keunggulan Polmind',
            selector: '#keunggulan',
            url: '/beranda#keunggulan'
        },
        {
            title: 'Project Industri',
            description: 'Project dari perusahaan mitra',
            selector: '#project',
            url: '/beranda#project'
        },
        {
            title: 'Tim Dosen',
            description: 'Dosen dan pengajar',
            selector: '#dosen',
            url: '/beranda#dosen'
        },
        {
            title: 'Program Studi',
            description: 'Daftar program studi',
            selector: '#prodi',
            url: '/beranda#prodi'
        },
        {
            title: 'Berita Terkini',
            description: 'Update berita terbaru',
            selector: '#news',
            url: '/beranda#news'
        },
        {
            title: 'Mitra Kami',
            description: 'Mitra industri dan kolaborasi',
            selector: '#partner',
            url: '/beranda#partner'
        },
        {
            title: 'Sambutan Direktur',
            description: 'Sambutan Direktur Polmind',
            selector: '#sambutan',
            url: '/beranda#sambutan'
        },
        {
            title: 'Pendaftaran MB',
            description: 'Form pendaftaran mahasiswa baru',
            url: '/pmb'
        }
    ]

    function buildSearchResults(query) {
        if (!searchResults) return

        const searchQuery = query.trim().toLowerCase()
        if (searchQuery.length === 0) {
            searchResults.innerHTML =
                '<div class="search-empty-state">Ketik untuk mencari section atau halaman</div>'
            return
        }

        const filtered = searchItems.filter(
            item =>
                item.title.toLowerCase().includes(searchQuery) ||
                item.description.toLowerCase().includes(searchQuery)
        )

        if (filtered.length === 0) {
            searchResults.innerHTML =
                '<div class="search-no-results">Tidak ada hasil pencarian</div>'
            return
        }

        searchResults.innerHTML = filtered
            .map(item => {
                const selectorAttr = item.selector
                    ? `data-selector="${item.selector}"`
                    : ''
                return `
        <button type="button" class="search-result-item" ${selectorAttr} data-url="${item.url}">
          <span class="search-title">${item.title}</span>
          <span class="search-page">${item.description}</span>
        </button>
      `
            })
            .join('')

        searchResults
            .querySelectorAll('.search-result-item')
            .forEach(button => {
                button.addEventListener('click', function () {
                    const selector = this.getAttribute('data-selector')
                    const url = this.getAttribute('data-url')

                    if (selector && document.querySelector(selector)) {
                        const element = document.querySelector(selector)
                        window.scrollTo({
                            top: element.offsetTop - 80,
                            behavior: 'smooth'
                        })
                    } else if (url) {
                        window.location.href = url
                        return
                    }

                    searchDropdown?.classList.remove('show-dropdown')
                    if (mobileNav?.classList.contains('active')) {
                        mobileNav.classList.remove('active')
                        burgerMenu?.classList.remove('active')
                        burgerMenu?.setAttribute('aria-expanded', 'false')
                    }
                    searchInput.value = ''
                    searchResults.innerHTML = ''
                })
            })
    }

    if (searchBtn && searchDropdown) {
        searchBtn.addEventListener('click', function (e) {
            e.stopPropagation()
            searchDropdown.classList.toggle('show-dropdown')
            if (searchDropdown.classList.contains('show-dropdown')) {
                searchInput?.focus()
                buildSearchResults(searchInput?.value || '')
            }
        })
    }

    if (mobileSearchBtn && searchDropdown) {
        mobileSearchBtn.addEventListener('click', function (e) {
            e.stopPropagation()
            searchDropdown.classList.toggle('show-dropdown')
            if (searchDropdown.classList.contains('show-dropdown')) {
                searchInput?.focus()
                buildSearchResults(searchInput?.value || '')
            }
        })
    }

    if (searchDropdown) {
        searchDropdown.addEventListener('click', function (e) {
            e.stopPropagation()
        })
    }

    document.addEventListener("click", function (e) {
        if (!e.target.closest("header") && !e.target.closest(".search-dropdown")) {
            searchDropdown?.classList.remove("show-dropdown");
        }
    });

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function () {
            buildSearchResults(this.value)
        })

        searchInput.addEventListener('focus', function () {
            if (!searchDropdown?.classList.contains('show-dropdown')) {
                searchDropdown?.classList.add('show-dropdown')
            }
            buildSearchResults(this.value)
        })
    }

    // ============================================================
    // 3. HERO SLIDER — AUTOPLAY + SWIPE + DRAG + DOTS + ARROWS
    // ============================================================
    const heroSlides = document.getElementById('heroSlides')
    const heroNext = document.getElementById('heroNext')
    const heroPrev = document.getElementById('heroPrev')
    const sliderDots = document.getElementById('sliderDots')

    if (heroSlides) {
        const totalSlides = heroSlides.querySelectorAll('.hero-slide').length
        let currentSlide = 0
        let heroInterval
        let isDragging = false
        let startPosX = 0
        let currentTranslate = 0
        let prevTranslate = 0
        let animationID

        function updateSliderPosition() {
            heroSlides.style.transform = `translateX(${currentTranslate}px)`
        }

        function goToSlide(index) {
            if (index < 0) currentSlide = totalSlides - 1
            else if (index >= totalSlides) currentSlide = 0
            else currentSlide = index

            currentTranslate = -currentSlide * heroSlides.offsetWidth
            prevTranslate = currentTranslate
            heroSlides.style.transition =
                'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)'
            heroSlides.style.transform = `translateX(${currentTranslate}px)`

            if (sliderDots) {
                const dots = sliderDots.querySelectorAll('.slider-dot')
                dots.forEach((dot, i) =>
                    dot.classList.toggle('active', i === currentSlide)
                )
            }
        }

        // Header hide/show and logo click behavior moved to run on all pages
        // (originally inside hero slider) -- logic is injected after the hero
        // slider block so it executes regardless of presence of heroSlides.

        function startHeroAutoplay() {
            stopHeroAutoplay();
            if (totalSlides > 1) {
                heroInterval = setInterval(() => goToSlide(currentSlide + 1), 5000);
            }
        }

        function stopHeroAutoplay() {
            clearInterval(heroInterval)
        }

        if (totalSlides > 1) {
            heroSlides.addEventListener('mousedown', dragStart, { signal: sig })
            heroSlides.addEventListener('mousemove', dragMove, { signal: sig })
            heroSlides.addEventListener('mouseup', dragEnd, { signal: sig })
            heroSlides.addEventListener('mouseleave', dragEnd, { signal: sig })

            heroSlides.addEventListener('touchstart', dragStart, { passive: true, signal: sig })
            heroSlides.addEventListener('touchmove', dragMove, { passive: true, signal: sig })
            heroSlides.addEventListener('touchend', dragEnd, { signal: sig })
        } else {
            heroSlides.style.cursor = 'default'
            heroSlides.style.transform = 'translateX(0)'
        }

        function dragStart(e) {
            if (totalSlides <= 1) return

            stopHeroAutoplay()
            isDragging = true
            startPosX = getPositionX(e)
            heroSlides.style.transition = 'none'
            animationID = requestAnimationFrame(animation)
        }

        function dragMove(e) {
            if (!isDragging) return
            const currentPosX = getPositionX(e)
            currentTranslate = prevTranslate + (currentPosX - startPosX)
        }

        function dragEnd() {
            if (!isDragging) return
            isDragging = false
            cancelAnimationFrame(animationID)

            const movedBy = currentTranslate - prevTranslate
            const threshold = heroSlides.offsetWidth * 0.2

            if (movedBy < -threshold) {
                goToSlide(currentSlide + 1)
            } else if (movedBy > threshold) {
                goToSlide(currentSlide - 1)
            } else {
                goToSlide(currentSlide)
            }

            startHeroAutoplay()
        }

        function getPositionX(e) {
            return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX
        }

        function animation() {
            updateSliderPosition()
            if (isDragging) {
                animationID = requestAnimationFrame(animation)
            }
        }

        if (heroNext && totalSlides > 1) {
            heroNext.addEventListener('click', () => {
                goToSlide(currentSlide + 1)
                startHeroAutoplay()
            }, { signal: sig })
        }

        if (heroPrev && totalSlides > 1) {
            heroPrev.addEventListener('click', () => {
                goToSlide(currentSlide - 1)
                startHeroAutoplay()
            }, { signal: sig })
        }

        if (sliderDots && totalSlides > 1) {
            sliderDots.addEventListener('click', e => {
                const dot = e.target.closest('.slider-dot')
                if (dot) {
                    const index = parseInt(dot.getAttribute('data-idx'))
                    if (!isNaN(index)) {
                        goToSlide(index)
                        startHeroAutoplay()
                    }
                }
            }, { signal: sig })
        }

        startHeroAutoplay()

        window.addEventListener('resize', () => {
            currentTranslate = -currentSlide * heroSlides.offsetWidth
            prevTranslate = currentTranslate
            heroSlides.style.transition = 'none'
            heroSlides.style.transform = `translateX(${currentTranslate}px)`
        })
    }

    // ============================================================
    // 4. NEWS SLIDER — DRAG + SWIPE + ARROWS + AUTOPLAY
    // ============================================================
    const newsTrack = document.getElementById('newsTrack')
    const newsLeft = document.getElementById('newsLeft')
    const newsRight = document.getElementById('newsRight')

    if (newsTrack) {
        let isNewsDragging = false
        let newsStartX, newsScrollLeft
        let newsInterval

        newsTrack.addEventListener('mousedown', e => {
            isNewsDragging = true
            newsTrack.classList.add('dragging')
            newsStartX = e.pageX - newsTrack.offsetLeft
            newsScrollLeft = newsTrack.scrollLeft
            stopNewsAutoplay()
        })

        newsTrack.addEventListener('mousemove', e => {
            if (!isNewsDragging) return
            e.preventDefault()
            const x = e.pageX - newsTrack.offsetLeft
            const walk = (x - newsStartX) * 2
            newsTrack.scrollLeft = newsScrollLeft - walk
        })

        newsTrack.addEventListener('mouseup', () => {
            isNewsDragging = false
            newsTrack.classList.remove('dragging')
            startNewsAutoplay()
        })

        newsTrack.addEventListener('mouseleave', () => {
            if (isNewsDragging) {
                isNewsDragging = false
                newsTrack.classList.remove('dragging')
                startNewsAutoplay()
            }
        })

        newsTrack.addEventListener(
            'touchstart',
            e => {
                isNewsDragging = true
                newsStartX = e.touches[0].pageX - newsTrack.offsetLeft
                newsScrollLeft = newsTrack.scrollLeft
                stopNewsAutoplay()
            },
            { passive: true }
        )

        newsTrack.addEventListener(
            'touchmove',
            e => {
                if (!isNewsDragging) return
                const x = e.touches[0].pageX - newsTrack.offsetLeft
                const walk = (x - newsStartX) * 2
                newsTrack.scrollLeft = newsScrollLeft - walk
            },
            { passive: true }
        )

        newsTrack.addEventListener('touchend', () => {
            isNewsDragging = false
            startNewsAutoplay()
        })

        if (newsRight) {
            newsRight.addEventListener('click', () => {
                newsTrack.scrollBy({
                    left: newsTrack.offsetWidth * 0.75,
                    behavior: 'smooth'
                })
                startNewsAutoplay()
            })
        }

        if (newsLeft) {
            newsLeft.addEventListener('click', () => {
                newsTrack.scrollBy({
                    left: -newsTrack.offsetWidth * 0.75,
                    behavior: 'smooth'
                })
                startNewsAutoplay()
            })
        }

        const newsFilterButtons = document.querySelectorAll(".news-filter-btn");
        const newsCards = newsTrack.querySelectorAll(".news-card:not(.news-card-empty)");
        const newsEmptyMessage = document.getElementById("newsEmptyMessage");

        function updateEmptyMessageVisibility() {
            if (!newsEmptyMessage) return;

            // Kalau database kosong, jangan tampilkan empty filter.
            // Biarkan card @empty "Berita akan segera hadir" yang tampil.
            if (newsCards.length === 0) {
                newsEmptyMessage.style.display = 'none';
                return;
            }

            // Kalau ada berita dari database, baru cek hasil filter.
            const anyVisible = Array.from(newsCards).some(card => {
                return card.style.display !== 'none';
            });

            newsEmptyMessage.style.display = anyVisible ? 'none' : 'flex';
        }

        if (newsFilterButtons.length > 0) {
            newsFilterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filterValue = button.dataset.filter

                    newsFilterButtons.forEach(btn => {
                        btn.classList.toggle('active', btn === button)
                    })

                    newsCards.forEach(card => {
                        const cardType = card.dataset.jenisContent || 'Umum'
                        const shouldShow =
                            filterValue === 'Semua' || cardType === filterValue
                        card.style.display = shouldShow ? '' : 'none'
                    })

                    // Reset scroll and update empty message
                    newsTrack.scrollTo({ left: 0, behavior: "smooth" });
                    updateEmptyMessageVisibility();
                    // Re-equalize heights after filtering
                    if (typeof debouncedEqualize === 'function') debouncedEqualize();
                });
            });

            // kalau ada berita, jangan tampilkan tulisan "Tidak ada berita"
            if (newsEmptyMessage) {
                newsEmptyMessage.style.display = 'none';
            }
        }

        function startNewsAutoplay() {
            stopNewsAutoplay()
            newsInterval = setInterval(() => {
                if (
                    newsTrack.scrollLeft + newsTrack.offsetWidth >=
                    newsTrack.scrollWidth - 10
                ) {
                    newsTrack.scrollTo({ left: 0, behavior: 'smooth' })
                } else {
                    newsTrack.scrollBy({
                        left: newsTrack.offsetWidth * 0.75,
                        behavior: 'smooth'
                    })
                }
            }, 5000)
        }

        function stopNewsAutoplay() {
            clearInterval(newsInterval)
        }

        startNewsAutoplay();

        // Equalize news card heights so cards line up nicely
        function debounce(fn, wait) {
            let t;
            return function () {
                const args = arguments;
                clearTimeout(t);
                t = setTimeout(function () { fn.apply(null, args); }, wait);
            };
        }

        function equalizeNewsCards() {
            // Include all cards so sizing is consistent even when some are off-screen
            const cards = Array.from(newsTrack.querySelectorAll('.news-card'));
            if (!cards.length) return;

            // Reset any inline heights
            cards.forEach(c => {
                const link = c.classList.contains('news-link') ? c : c.querySelector('.news-link') || c;
                c.style.minHeight = '';
                if (link) link.style.minHeight = '';
            });

            // Measure natural heights (use link area if present)
            let maxH = 0;
            cards.forEach(c => {
                const link = c.classList.contains('news-link') ? c : c.querySelector('.news-link') || c;
                const rect = link.getBoundingClientRect();
                const h = Math.ceil(rect.height || link.offsetHeight || c.offsetHeight);
                if (h > maxH) maxH = h;
            });

            // Apply maximum height to all cards/links
            if (maxH > 0) {
                cards.forEach(c => {
                    const link = c.classList.contains('news-link') ? c : c.querySelector('.news-link') || c;
                    if (link) link.style.minHeight = maxH + 'px';
                    c.style.minHeight = maxH + 'px';
                });
            }
        }

        // Run multiple times with delays to handle slow image loads / layout shifts
        function scheduleMultipleEqualize() {
            debouncedEqualize();
            setTimeout(debouncedEqualize, 200);
            setTimeout(debouncedEqualize, 700);
        }

        const debouncedEqualize = debounce(equalizeNewsCards, 120);

        // Run initially and after images load
        scheduleMultipleEqualize();
        // Run again after full window load to catch cached images
        window.addEventListener('load', scheduleMultipleEqualize);
        newsTrack.querySelectorAll('img').forEach(img => {
            if (!img.complete) img.addEventListener('load', scheduleMultipleEqualize, { once: true });
        });

        // Recalculate on resize and orientation change
        window.addEventListener('resize', debouncedEqualize);
        window.addEventListener('orientationchange', debouncedEqualize);
        // Re-run after manual scrollBy/autoplay movement stops
        let scrollEndTimer;
        newsTrack.addEventListener('scroll', function () {
            clearTimeout(scrollEndTimer);
            scrollEndTimer = setTimeout(function () {
                debouncedEqualize();
            }, 120);
        }, { passive: true });

        newsTrack.addEventListener('mouseenter', stopNewsAutoplay)
        newsTrack.addEventListener('mouseleave', startNewsAutoplay)
    }

    // ============================================================
    // 5. HEADER SCROLL SHADOW
    // ============================================================
    const header = document.querySelector('.header')
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10)
        })
    }

    // ============================================================
    // 6. FLOATING BUTTONS — WA MUNCUL SAAT SCROLL KE BAWAH, UP MUNCUL SAAT SCROLL KE ATAS
    // ============================================================
    const scrollBtn = document.getElementById('scrollToTop')
    const waBtn = document.getElementById('whatsappBtn')
    const footer = document.querySelector('.footer')
    let lastScrollTop = 0

    function updateFloatingButtons() {
        if (!scrollBtn || !waBtn) return

        const st = window.pageYOffset || document.documentElement.scrollTop
        const windowHeight = window.innerHeight
        const documentHeight = document.documentElement.scrollHeight
        const footerTop = footer
            ? footer.getBoundingClientRect().top + st
            : documentHeight
        const nearFooter = st + windowHeight >= footerTop - 50

        if (st < 100) {
            scrollBtn.classList.remove('visible')
            waBtn.classList.remove('visible')
            lastScrollTop = st
            return
        }

        if (nearFooter) {
            scrollBtn.classList.remove('visible')
            waBtn.classList.add('visible')
        } else if (st > lastScrollTop) {
            scrollBtn.classList.remove('visible')
            waBtn.classList.add('visible')
        } else if (st < lastScrollTop) {
            scrollBtn.classList.add('visible')
            waBtn.classList.remove('visible')
        }

        lastScrollTop = st
    }

    window.addEventListener('scroll', updateFloatingButtons, { passive: true })
    updateFloatingButtons()

    if (scrollBtn) {
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' })
        })
    }

    // ============================================================
    // 7. BANNER PMB TRANSPARENCY DETECTION
    // ============================================================
    async function isImageTransparent(img) {
        return new Promise(resolve => {
            const check = imgEl => {
                try {
                    const canvas = document.createElement('canvas')
                    canvas.width = Math.min(imgEl.naturalWidth, 100)
                    canvas.height = Math.min(imgEl.naturalHeight, 100)
                    const ctx = canvas.getContext('2d')
                    ctx.drawImage(imgEl, 0, 0, canvas.width, canvas.height)
                    const data = ctx.getImageData(
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    ).data
                    for (let i = 3; i < data.length; i += 4) {
                        if (data[i] < 250) {
                            resolve(true)
                            return
                        }
                    }
                    resolve(false)
                } catch (e) {
                    resolve(false)
                }
            }
            if (img.complete && img.naturalWidth > 0) check(img)
            else {
                img.addEventListener('load', () => check(img), { once: true })
                img.addEventListener('error', () => resolve(false), {
                    once: true
                })
            }
        })
    }

    document.querySelectorAll('[data-banner-wrap]').forEach(async wrap => {
        const img = wrap.querySelector('.banner-pmb-img')
        if (!img) return
        const transparent = await isImageTransparent(img)
        wrap.classList.add(transparent ? 'is-transparent' : 'is-opaque')
    })

    // ============================================================
    // 8. TOGGLE KARTU PRODI (jika ada)
    // ============================================================
    window.toggleCard = function (headerElement) {
        const card = headerElement.closest('.card-prodi')
        if (card) card.classList.toggle('expanded')
    }

    // ============================================================
    // TRANSLATION HELPER
    // ============================================================
    function getCurrentLang() {
        const savedLang = localStorage.getItem('polmind_lang')

        if (savedLang === 'en' || savedLang === 'id') {
            return savedLang
        }

        const htmlLang = (
            document.documentElement.getAttribute('lang') || 'id'
        ).toLowerCase()

        return htmlLang === 'en' ? 'en' : 'id'
    }

    function getTranslation(key, fallback = '') {
        const lang = getCurrentLang()

        if (
            typeof translations !== 'undefined' &&
            translations?.[lang]?.[key]
        ) {
            return translations[lang][key]
        }

        return fallback
    }

    function decodeHtml(html) {
        const textarea = document.createElement('textarea')
        textarea.innerHTML = html || ''
        return textarea.value
    }

    // ============================================================
    // 9. MODAL DOSEN
    // ============================================================
    window.openModal = function (nama, foto, alt, deskripsi, tipe) {
        // Nama & foto
        document.getElementById('modalNama').textContent = nama
        document.getElementById('modalFoto').src = foto
        document.getElementById('modalFoto').alt = alt

        // Badge
        const badge = document.getElementById('modalBadge')

        if (badge) {
            const badgeConfig = {
                Dosen_Internal: {
                    key: 'internal-lecturers',
                    fallback: 'Dosen Internal',
                    className: 'badge-internal'
                },
                Expert_industri: {
                    key: 'industry-lecturers',
                    fallback: 'Expert Industri',
                    className: 'badge-expert'
                },
                Tenaga_Pendidik: {
                    key: 'internal-staff',
                    fallback: 'Tenaga Pendidik',
                    className: 'badge-internal'
                }
            }

            const config = badgeConfig[tipe] || badgeConfig.Dosen_Internal

            badge.setAttribute('data-translate', config.key)
            badge.textContent = getTranslation(config.key, config.fallback)
            badge.className = 'badge-dosen ' + config.className
        }

        // Deskripsi: isi HTML string langsung ke innerHTML
        const bioWrap = document.getElementById('modalBioWrap')
        const bioEl = document.getElementById('modalBio')
        const emptyEl = document.getElementById('modalEmpty')

        if (deskripsi && deskripsi.trim && deskripsi.trim() !== '') {
            bioEl.innerHTML = decodeHtml(deskripsi)
            bioWrap.style.display = 'block'
            if (emptyEl) emptyEl.style.display = 'none'
        } else {
            bioWrap.style.display = 'none'
            if (emptyEl) emptyEl.style.display = 'block'
        }

        // Tampilkan modal
        document.getElementById('modalOverlay').classList.add('active')
        document.body.style.overflow = 'hidden'
    }

    window.closeModal = function () {
        document.getElementById('modalOverlay').classList.remove('active')
        document.body.style.overflow = ''
    }

    window.closeModalOutside = function (e) {
        if (e.target === document.getElementById('modalOverlay')) {
            window.closeModal()
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.closeModal();
    });

    // ============================================================
    // 10. NEWS LIST PAGE - FILTER HANDLING
    // ============================================================
    const newsGrid = document.querySelector('.news-grid')
    if (newsGrid) {
        const newsGridFilterButtons =
            document.querySelectorAll('.news-filter-btn')
        const newsItems = newsGrid.querySelectorAll('.news-item')
        const newsGridEmptyMessage = document.querySelector('.news-empty')

        function updateNewsGridEmptyState() {
            if (!newsGridEmptyMessage) return
            const anyVisible = Array.from(newsItems).some(item => {
                return item.style.display !== 'none'
            })
            newsGridEmptyMessage.style.display = anyVisible ? 'none' : ''
        }

        if (newsGridFilterButtons.length > 0 && newsItems.length > 0) {
            newsGridFilterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filterValue = button.dataset.filter

                    // Update active button
                    newsGridFilterButtons.forEach(btn => {
                        btn.classList.toggle('active', btn === button)
                    })

                    // Filter items
                    newsItems.forEach(item => {
                        const itemCategory = item.dataset.jenisContent || 'Umum'
                        const shouldShow =
                            filterValue === 'Semua' ||
                            itemCategory === filterValue
                        item.style.display = shouldShow ? '' : 'none'
                    })

                    // Update empty message
                    updateNewsGridEmptyState()
                })
            })

            // Ensure correct initial state
            updateNewsGridEmptyState()
        }
    }

    // ============================================================
    // 11. NEWS THUMBNAIL: SINGLE CLICK = OPEN NEWS, DOUBLE CLICK = IMAGE MODAL
    // ============================================================
    document.querySelectorAll('.news-thumb-link').forEach(link => {
        let clickTimer = null
        const delay = 250

        link.addEventListener('click', function (e) {
            e.preventDefault()

            const targetUrl = this.dataset.url || this.getAttribute('href')
            const imageUrl = this.dataset.image
            const imageTitle = this.dataset.title || ''

            // Klik kedua dalam waktu cepat = buka modal gambar
            if (clickTimer) {
                clearTimeout(clickTimer)
                clickTimer = null

                if (imageUrl && window.ImageModal) {
                    window.ImageModal.open(imageUrl, imageTitle)
                }

                return
            }

            // Klik pertama = tunggu sebentar, kalau tidak ada klik kedua maka redirect
            clickTimer = setTimeout(() => {
                clickTimer = null

                if (targetUrl) {
                    window.location.href = targetUrl
                }
            }, delay)
        })
    })

    // ============================================================
    // 12. PERSON CARD MODAL HANDLER
    // Untuk Dosen, Expert Industri, dan Tenaga Pendidik
    // ============================================================
    document.addEventListener('click', function (e) {
        const card = e.target.closest('.js-open-person-modal')

        if (!card) return

        openModal(
            card.dataset.name || '',
            card.dataset.photo || '',
            card.dataset.alt || '',
            card.dataset.desc || '',
            card.dataset.type || ''
        )
    })

})

    // ============================================================
    // PARTNER MARQUEE — SEAMLESS AUTOPLAY + DRAG + MOMENTUM
    // ============================================================
    ; (function () {
        const wrapper = document.querySelector('.partner-marquee-wrapper')
        const track = document.querySelector('.partner-marquee-track')

        if (!wrapper || !track) return

        const originalCards = Array.from(track.querySelectorAll('.partner-card'))
        const originalCount = originalCards.length

        if (originalCount === 0) return

        let position = 0
        let speed = getSpeed()
        let contentWidth = 0
        let loopWidth = 0
        let basePosition = 0
        let rafId = null

        let isDragging = false
        let isHovering = false
        let startX = 0
        let startPosition = 0
        let lastX = 0
        let lastTime = 0
        let velocity = 0
        let hasMoved = false

        const CLICK_THRESHOLD = 6
        const FRICTION = 0.94
        const MIN_VELOCITY = 0.02

        /*
        Data < 5  = no-clone, tidak ada gambar duplikat
        Data >= 5 = clone, agar marquee seamless
      */
        const isNoCloneMode = originalCount < 5

        function getSpeed() {
            if (window.innerWidth <= 500) return 0.35
            if (window.innerWidth <= 768) return 0.45
            return 0.6
        }

        function getGap() {
            const style = window.getComputedStyle(track)
            return parseFloat(style.columnGap || style.gap || 0) || 0
        }

        function removeLoopItems() {
            track
                .querySelectorAll(
                    '[data-loop-item="true"], .partner-card[data-clone="true"]'
                )
                .forEach(item => {
                    item.remove()
                })
        }

        function getCardsWidth(cards) {
            const gap = getGap()

            return cards.reduce((total, card, index) => {
                return (
                    total + card.offsetWidth + (index < cards.length - 1 ? gap : 0)
                )
            }, 0)
        }

        function getCenterPosition() {
            const wrapperWidth = wrapper.offsetWidth

            /*
          Posisi tengah = lebar wrapper dikurangi lebar semua logo,
          lalu dibagi 2.
        */
            return (wrapperWidth - contentWidth) / 2
        }

        function createClone(card) {
            const clone = card.cloneNode(true)

            clone.setAttribute('data-loop-item', 'true')
            clone.setAttribute('data-clone', 'true')
            clone.setAttribute('aria-hidden', 'true')

            return clone
        }

        wrapper.addEventListener('click', function (e) {
            const img = e.target.closest('img.preview-image')

            if (!img || !wrapper.contains(img)) return

            /*
        Kalau user benar-benar drag, jangan buka modal.
      */
            if (hasMoved) {
                e.preventDefault()
                e.stopPropagation()
                return
            }

            /*
        Klik biasa: buka modal image.
      */
            e.preventDefault()
            e.stopPropagation()

            const imageUrl = img.currentSrc || img.src
            const caption = img.alt || img.title || ''

            if (window.ImageModal && typeof window.ImageModal.open === 'function') {
                window.ImageModal.open(imageUrl, caption)
            } else if (
                window._globalImageModalInstance &&
                typeof window._globalImageModalInstance.open === 'function'
            ) {
                window._globalImageModalInstance.open(imageUrl, caption)
            }
        })

        function buildItems() {
            removeLoopItems()

            speed = getSpeed()
            contentWidth = getCardsWidth(originalCards)

            if (contentWidth <= 0) return

            track.style.justifyContent = 'flex-start'

            if (isNoCloneMode) {
                /*
            Mode data < 5:
            Start awal di tengah wrapper.
            Tidak clone, jadi gambar tidak dobel.
          */
                loopWidth = contentWidth
                position = getCenterPosition()

                applyTransform()
                return
            }

            /*
          Mode data >= 5:
          Pakai clone agar marquee seamless.
        */
            const minimumWidth = wrapper.offsetWidth * 2 + contentWidth
            let currentWidth = contentWidth

            while (currentWidth < minimumWidth) {
                originalCards.forEach(card => {
                    track.appendChild(createClone(card))
                })

                currentWidth += contentWidth + getGap()

                if (track.children.length > originalCards.length * 20) break
            }

            loopWidth = contentWidth + getGap()
            basePosition = 0
            position = normalizeClonePosition(getCenterPosition())

            applyTransform()
        }

        function normalizeNoClonePosition(value) {
            const wrapperWidth = wrapper.offsetWidth

            /*
          Kalau semua logo sudah keluar dari kiri,
          munculkan lagi dari kanan.
        */
            if (value < -contentWidth) {
                value = wrapperWidth
            }

            /*
          Kalau user drag terlalu jauh ke kanan,
          munculkan lagi dari kiri.
        */
            if (value > wrapperWidth) {
                value = -contentWidth
            }

            return value
        }

        function normalizeClonePosition(value) {
            if (!loopWidth) return value

            while (value <= basePosition - loopWidth) {
                value += loopWidth
            }

            while (value > basePosition) {
                value -= loopWidth
            }

            return value
        }

        function normalizePosition(value) {
            return isNoCloneMode
                ? normalizeNoClonePosition(value)
                : normalizeClonePosition(value)
        }

        function applyTransform() {
            track.style.transform = `translate3d(${position}px, 0, 0)`
        }

        function animate() {
            if (!isDragging && !isHovering) {
                position -= speed
                position = normalizePosition(position)
                applyTransform()
            }

            rafId = requestAnimationFrame(animate)
        }

        function stopMomentum() {
            velocity = 0
        }

        function startDrag(clientX) {
            isDragging = true
            hasMoved = false
            wrapper.classList.add('is-dragging')

            startX = clientX
            startPosition = position
            lastX = clientX
            lastTime = performance.now()

            stopMomentum()
        }

        function moveDrag(clientX) {
            if (!isDragging) return

            const now = performance.now()
            const deltaX = clientX - startX
            const timeDelta = now - lastTime

            if (Math.abs(deltaX) > CLICK_THRESHOLD) {
                hasMoved = true
            }

            position = normalizePosition(startPosition + deltaX)
            applyTransform()

            if (timeDelta > 0) {
                velocity = (clientX - lastX) / timeDelta
            }

            lastX = clientX
            lastTime = now
        }

        function endDrag() {
            if (!isDragging) return

            isDragging = false
            wrapper.classList.remove('is-dragging')

            applyMomentum()
        }

        function applyMomentum() {
            function momentumLoop() {
                if (isDragging) return

                velocity *= FRICTION

                if (Math.abs(velocity) <= MIN_VELOCITY) {
                    velocity = 0
                    return
                }

                position += velocity * 18
                position = normalizePosition(position)
                applyTransform()

                requestAnimationFrame(momentumLoop)
            }

            requestAnimationFrame(momentumLoop)
        }

        /*
        Mouse drag
      */
        wrapper.addEventListener('mousedown', function (e) {
            if (e.button !== 0) return

            e.preventDefault()
            startDrag(e.clientX)
        })

        document.addEventListener('mousemove', function (e) {
            moveDrag(e.clientX)
        })

        document.addEventListener('mouseup', function () {
            endDrag()
        })

        /*
        Touch drag
      */
        wrapper.addEventListener(
            'touchstart',
            function (e) {
                if (!e.touches || !e.touches.length) return

                startDrag(e.touches[0].clientX)
            },
            { passive: true }
        )

        wrapper.addEventListener(
            'touchmove',
            function (e) {
                if (!e.touches || !e.touches.length) return

                moveDrag(e.touches[0].clientX)
            },
            { passive: true }
        )

        wrapper.addEventListener(
            'touchend',
            function () {
                endDrag()
            },
            { passive: true }
        )

        /*
        Pause saat hover desktop.
      */
        wrapper.addEventListener('mouseenter', function () {
            if (window.innerWidth >= 768) {
                isHovering = true
            }
        })

        wrapper.addEventListener('mouseleave', function () {
            if (window.innerWidth >= 768) {
                isHovering = false
            }
        })

        let resizeTimer = null

        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer)

            resizeTimer = setTimeout(function () {
                buildItems()
            }, 200)
        })

        track.addEventListener('dragstart', function (e) {
            e.preventDefault()
        })

        buildItems()

        if (rafId) {
            cancelAnimationFrame(rafId)
        }

        rafId = requestAnimationFrame(animate)
    })()