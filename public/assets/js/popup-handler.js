/* POPUP PAGES MAIN */

(function () {
    'use strict';

    /* ── helpers ── */
    function closePopup(overlay, sessionKey) {
        var modal = overlay.querySelector('#ppModal');

        if (overlay.classList.contains('pp-floating')) {
            try { if (overlay._pp_resize_handler) { window.removeEventListener('resize', overlay._pp_resize_handler); overlay._pp_resize_handler = null; } } catch (e) { }
            try { if (overlay._pp_restore_click && modal) { modal.removeEventListener('click', overlay._pp_restore_click); overlay._pp_restore_click = null; } } catch (e) { }

            overlay.classList.remove('pp-floating');
            overlay.classList.remove('pp-show');
            overlay.classList.add('pp-hide');
            overlay.style.display = 'none';

            if (modal) resetModalStyle(modal);
            overlay.style.background = '';
            overlay.style.pointerEvents = '';
            sessionStorage.setItem(sessionKey, 'true');
            return;
        }

        if (modal) resetModalStyle(modal);
        overlay.classList.remove('pp-floating');
        overlay.style.background = '';
        overlay.style.pointerEvents = '';

        try {
            if (overlay._pp_resize_handler) { window.removeEventListener('resize', overlay._pp_resize_handler); overlay._pp_resize_handler = null; }
            if (overlay._pp_restore_click && modal) { modal.removeEventListener('click', overlay._pp_restore_click); overlay._pp_restore_click = null; }
        } catch (e) { }

        overlay.classList.remove('pp-show');
        overlay.classList.add('pp-hide');
        setTimeout(function () { overlay.style.display = 'none'; }, 300);
        sessionStorage.setItem(sessionKey, 'true');
    }

    function resetModalStyle(modal) {
        modal.classList.remove('pp-modal--floating');

        [
            'position',
            'left',
            'top',
            'right',
            'bottom',
            'width',
            'maxWidth',
            'height',
            'maxHeight',
            'margin',
            'transition',
            'borderRadius',
            'boxShadow',
            'overflow',
            'pointerEvents'
        ].forEach(function (p) {
            modal.style[p] = '';
        });
    }

    function resetFloatingInlineStyle() {
        var img = document.getElementById('ppImage');
        var imgWrap = document.getElementById('ppImageWrap');
        var contentWrap = document.getElementById('ppContentWrap');

        if (imgWrap) {
            [
                'width',
                'height',
                'maxHeight',
                'overflow'
            ].forEach(function (p) {
                imgWrap.style[p] = '';
            });
        }

        if (img) {
            [
                'width',
                'height',
                'maxWidth',
                'maxHeight',
                'objectFit',
                'objectPosition'
            ].forEach(function (p) {
                img.style[p] = '';
            });
        }

        if (contentWrap) {
            [
                'width',
                'height',
                'maxHeight',
                'overflow'
            ].forEach(function (p) {
                contentWrap.style[p] = '';
            });
        }
    }
    function showPopupCenter(overlay) {
        var modal = document.getElementById('ppModal');
        if (!overlay || !modal) return;

        overlay.classList.remove('pp-floating', 'pp-hide');
        overlay.classList.add('pp-show');

        overlay.style.display = 'flex';
        overlay.style.background = '';
        overlay.style.pointerEvents = '';

        resetModalStyle(modal);
        resetFloatingInlineStyle();

        /*
         * Apply ulang logic yang sama seperti popup pertama muncul,
         * tapi TANPA panggil openPopup() lagi.
         */
        detectImageAspectRatio();
    }

    /* ── Aspect ratio detect ── */
    function detectImageAspectRatio() {
        var img = document.getElementById('ppImage');
        var container = document.getElementById('ppContainer');
        var modal = document.getElementById('ppModal');
        var wrap = document.getElementById('ppImageWrap');

        if (!img || !container || !modal || !wrap) return;

        function resetPopupImageStyle() {
            modal.style.width = '';
            modal.style.maxWidth = '';
            modal.style.height = '';

            wrap.style.width = '';
            wrap.style.height = '';

            img.style.width = '';
            img.style.height = '';
            img.style.maxWidth = '';
            img.style.maxHeight = '';
            img.style.objectFit = '';
            img.style.objectPosition = '';
        }

        function apply() {
            var naturalW = img.naturalWidth;
            var naturalH = img.naturalHeight;

            if (!naturalW || !naturalH) return;

            var ratio = naturalW / naturalH;

            resetPopupImageStyle();

            container.classList.remove(
                'pp-portrait',
                'pp-square',
                'pp-landscape',
                'pp-ultrawide'
            );

            modal.classList.remove(
                'pp-modal-portrait',
                'pp-modal-square',
                'pp-modal-landscape',
                'pp-modal-ultrawide'
            );

            if (ratio >= 2.2) {
                container.classList.add('pp-ultrawide');
                modal.classList.add('pp-modal-ultrawide');

                resizeUltraWide(ratio);
                return;
            }

            if (ratio > 1.15) {
                container.classList.add('pp-landscape');
                modal.classList.add('pp-modal-landscape');
            } else if (ratio < 0.85) {
                container.classList.add('pp-portrait');
                modal.classList.add('pp-modal-portrait');
            } else {
                container.classList.add('pp-square');
                modal.classList.add('pp-modal-square');
            }

            /*
             * Normal image:
             * Jangan set width/height dari JS.
             * Biarkan CSS + rasio asli image yang menentukan bentuk popup.
             */
        }

        function resizeUltraWide() {
            var viewportW = window.innerWidth;
            var viewportH = window.innerHeight;

            var contentReserveH = 82;

            var maxW = Math.min(viewportW - 32, 1040);
            var maxH = Math.min(viewportH - contentReserveH - 32, 620);

            if (viewportW <= 600) {
                maxW = viewportW - 20;
                maxH = viewportH - contentReserveH - 24;
            }

            var targetW = maxW;
            var targetH = targetW * 9 / 16;

            if (targetH > maxH) {
                targetH = maxH;
                targetW = targetH * 16 / 9;
            }

            targetW = Math.round(targetW);
            targetH = Math.round(targetH);

            modal.style.width = targetW + 'px';

            wrap.style.width = targetW + 'px';
            wrap.style.height = targetH + 'px';

            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            img.style.objectPosition = 'center center';
        }

        if (img.complete && img.naturalWidth > 0) {
            apply();
        } else {
            img.addEventListener('load', apply, { once: true });
            img.addEventListener('error', function () {
                container.classList.add('pp-landscape');
                modal.classList.add('pp-modal-landscape');
            }, { once: true });
        }

        window.addEventListener('resize', function () {
            if (!img.naturalWidth || !img.naturalHeight) return;

            var ratio = img.naturalWidth / img.naturalHeight;

            if (ratio >= 2.2) {
                resizeUltraWide();
            }
        });
    }

    /* ── Read-more toggle ── */
    function initReadMore() {
        var wrap = document.getElementById('ppContentWrap');
        var body = document.getElementById('ppBody');
        var toggle = document.getElementById('ppReadToggle');
        var fade = document.getElementById('ppReadFade');
        if (!wrap || !body || !toggle) return;

        var COLLAPSED_H = 92;
        wrap.classList.add('pp-collapsed');

        function checkNeeded() {
            if (body.scrollHeight <= COLLAPSED_H + 10) {
                wrap.classList.remove('pp-collapsed');
                wrap.classList.add('pp-expanded');
                toggle.classList.add('pp-hidden');
                if (fade) fade.style.display = 'none';
            }
        }

        if (document.readyState === 'complete') checkNeeded();
        else { window.addEventListener('load', checkNeeded, { once: true }); setTimeout(checkNeeded, 350); }

        toggle.addEventListener('click', function () {
            var collapsed = wrap.classList.contains('pp-collapsed');
            if (collapsed) {
                wrap.classList.remove('pp-collapsed');
                wrap.classList.add('pp-expanded');
                toggle.textContent = 'Sembunyikan ▲';
            } else {
                wrap.classList.remove('pp-expanded');
                wrap.classList.add('pp-collapsed');
                toggle.textContent = '.... Lihat Selengkapnya';
                body.scrollTop = 0;
            }
        });
    }

    /* ── Slider ── */
    function initPopupSlider() {
        var wrap = document.getElementById('ppImageWrap');
        if (!wrap) return;
        var imgs = Array.prototype.slice.call(wrap.querySelectorAll('img'));
        if (imgs.length <= 1) return;

        wrap.classList.add('pp-slider');
        imgs.forEach(function (img) {
            var slide = document.createElement('div');
            slide.className = 'pp-slide';
            img.parentNode.insertBefore(slide, img);
            slide.appendChild(img);
        });

        var slides = wrap.querySelectorAll('.pp-slide');
        var index = 0;
        slides.forEach(function (s, i) { s.style.transform = 'translateX(' + (i * 100) + '%)'; });

        function goTo(i) {
            index = (i + slides.length) % slides.length;
            slides.forEach(function (s, k) {
                s.style.transition = 'transform 420ms ease';
                s.style.transform = 'translateX(' + ((k - index) * 100) + '%)';
            });
        }

        var iv = setInterval(function () { goTo(index + 1); }, 3800);
        var modal = document.getElementById('ppModal');
        if (modal) {
            modal.addEventListener('mouseenter', function () { clearInterval(iv); });
            modal.addEventListener('mouseleave', function () { iv = setInterval(function () { goTo(index + 1); }, 3800); });
            modal.addEventListener('touchstart', function () { clearInterval(iv); }, { passive: true });
            modal.addEventListener('touchend', function () { iv = setInterval(function () { goTo(index + 1); }, 3800); }, { passive: true });
        }
    }

    /* ── Floating corner ── */
    function makeFloating(overlay, sessionKey) {
        var modal = document.getElementById('ppModal');
        var img = document.getElementById('ppImage');
        var imgWrap = document.getElementById('ppImageWrap');
        var contentWrap = document.getElementById('ppContentWrap');

        if (!modal || overlay.classList.contains('pp-floating')) return;

        var ratio = 16 / 9;

        if (img && img.naturalWidth > 0 && img.naturalHeight > 0) {
            ratio = img.naturalWidth / img.naturalHeight;
        }

        overlay.classList.add('pp-floating');
        overlay.style.background = 'transparent';
        overlay.style.pointerEvents = 'auto';

        modal.classList.add('pp-modal--floating');

        applyFloatingSize(modal, img, imgWrap, contentWrap, ratio);

        var handleResize = function () {
            if (!overlay.classList.contains('pp-floating')) return;
            applyFloatingSize(modal, img, imgWrap, contentWrap, ratio);
        };

        window.addEventListener('resize', handleResize);
        overlay._pp_resize_handler = handleResize;

        var restoreHandler = function (e) {
            if (e.target.closest && e.target.closest('.pp-close')) return;
            if (!overlay.classList.contains('pp-floating')) return;
            restoreToCenter(overlay);
        };

        overlay._pp_floating_backdrop = function (e) {
            if (!overlay.classList.contains('pp-floating')) return;

            if (!e.target.closest('#ppModal')) {
                closePopup(overlay, sessionKey);
            }
        };

        overlay.addEventListener('click', overlay._pp_floating_backdrop);

        modal.addEventListener('click', restoreHandler);
        overlay._pp_restore_click = restoreHandler;
    }

    function applyFloatingSize(modal, img, imgWrap, contentWrap, ratio) {
        var viewportW = window.innerWidth;
        var viewportH = window.innerHeight;

        var gap = viewportW <= 480 ? 12 : 18;
        var isUltraWide = ratio >= 2.2;

        var imageW;
        var imageH;

        if (isUltraWide) {
            imageW = viewportW <= 480 ? Math.min(230, viewportW - 32) : 320;
            imageH = Math.round(imageW * 9 / 16);
        } else if (ratio > 1.15) {
            imageW = viewportW <= 480 ? Math.min(240, viewportW - 32) : 300;
            imageH = Math.round(imageW / ratio);
        } else if (ratio < 0.85) {
            imageW = viewportW <= 480 ? Math.min(150, viewportW - 32) : 170;
            imageH = Math.round(imageW / ratio);
        } else {
            imageW = viewportW <= 480 ? Math.min(180, viewportW - 32) : 220;
            imageH = imageW;
        }

        var contentH = 0;

        if (contentWrap) {
            /*
             * Floating jangan terlalu tinggi.
             * Content cukup preview pendek saja.
             */
            contentH = viewportW <= 480 ? 86 : 96;
        }

        /*
         * Tinggi aman popup.
         * Ini yang mencegah popup tenggelam ke bawah viewport.
         */
        var maxModalH = viewportH - (gap * 2);

        /*
         * Kalau image + content melebihi layar,
         * yang dikurangi adalah tinggi image, bukan modal dibiarkan keluar layar.
         */
        var maxImageH = maxModalH - contentH;

        if (maxImageH < 90) {
            maxImageH = 90;
            contentH = Math.max(56, maxModalH - maxImageH);
        }

        if (imageH > maxImageH) {
            imageH = maxImageH;

            if (isUltraWide) {
                imageW = Math.round(imageH * 16 / 9);
            } else {
                imageW = Math.round(imageH * ratio);
            }
        }

        /*
         * Jangan sampai width keluar layar setelah height dikoreksi.
         */
        var maxImageW = viewportW - (gap * 2);

        if (imageW > maxImageW) {
            imageW = maxImageW;

            if (isUltraWide) {
                imageH = Math.round(imageW * 9 / 16);
            } else {
                imageH = Math.round(imageW / ratio);
            }
        }

        var modalW = imageW;
        var modalH = imageH + contentH;

        /*
         * Reserve bawah:
         * desktop: sedikit naik
         * mobile: naik lebih banyak supaya tidak tenggelam dan tidak tabrakan WA
         */
        var bottomReserve = viewportW <= 480 ? 86 : 76;

        /*
         * Titik start floating dibuat lebih tinggi.
         * Jadi bukan mepet bottom viewport lagi.
         */
        var modalTop = viewportH - modalH - bottomReserve;

        if (modalTop < gap) {
            modalTop = gap;
        }

        modal.style.setProperty('position', 'fixed', 'important');
        modal.style.setProperty('left', gap + 'px', 'important');
        modal.style.setProperty('top', modalTop + 'px', 'important');
        modal.style.setProperty('bottom', 'auto', 'important');
        modal.style.setProperty('width', modalW + 'px', 'important');
        modal.style.setProperty('height', modalH + 'px', 'important');
        modal.style.margin = '0';
        modal.style.borderRadius = '12px';
        modal.style.boxShadow = '0 12px 38px rgba(6,24,66,0.18)';
        modal.style.overflow = 'hidden';
        modal.style.pointerEvents = 'auto';
        modal.style.transition =
            'left 520ms cubic-bezier(.2,.9,.2,1), ' +
            'top 520ms cubic-bezier(.2,.9,.2,1), ' +
            'width 520ms cubic-bezier(.2,.9,.2,1), ' +
            'height 520ms cubic-bezier(.2,.9,.2,1), ' +
            'box-shadow 420ms ease, border-radius 420ms ease';

        if (imgWrap) {
            imgWrap.style.setProperty('width', imageW + 'px', 'important');
            imgWrap.style.setProperty('height', imageH + 'px', 'important');
            imgWrap.style.setProperty('max-height', imageH + 'px', 'important');
            imgWrap.style.setProperty('overflow', 'hidden', 'important');
        }

        if (img) {
            img.style.setProperty('width', '100%', 'important');
            img.style.setProperty('height', '100%', 'important');
            img.style.setProperty('max-width', 'none', 'important');
            img.style.setProperty('max-height', 'none', 'important');
            img.style.setProperty('object-fit', 'cover', 'important');
            img.style.setProperty('object-position', 'center center', 'important');
        }

        if (contentWrap) {
            contentWrap.style.setProperty('width', imageW + 'px', 'important');
            contentWrap.style.setProperty('height', contentH + 'px', 'important');
            contentWrap.style.setProperty('max-height', contentH + 'px', 'important');
            contentWrap.style.setProperty('overflow', 'hidden', 'important');
        }
    }


    function restoreToCenter(overlay) {
        var modal = document.getElementById('ppModal');

        if (!modal || !overlay.classList.contains('pp-floating')) return;

        if (overlay._pp_restore_click) {
            try {
                modal.removeEventListener('click', overlay._pp_restore_click);
            } catch (e) { }

            overlay._pp_restore_click = null;
        }

        if (overlay._pp_resize_handler) {
            try {
                window.removeEventListener('resize', overlay._pp_resize_handler);
            } catch (e) { }

            overlay._pp_resize_handler = null;
        }

        showPopupCenter(overlay);
    }

    /* ── Main open ── */
    function openPopup() {
        var overlay = document.getElementById('popupOverlay');
        if (!overlay) return;

        var popupId = overlay.getAttribute('data-popup-id') || 'default';
        var sessionKey = 'pp_shown_' + popupId;
        if (sessionStorage.getItem(sessionKey) === 'true') return;

        overlay.style.display = 'flex';

        detectImageAspectRatio();
        initReadMore();
        initPopupSlider();

        requestAnimationFrame(function () {
            requestAnimationFrame(function () { overlay.classList.add('pp-show'); });
        });

        var floatingTimer = null;
        function clearFloatingTimeout() { if (floatingTimer) { clearTimeout(floatingTimer); floatingTimer = null; } }

        /* close button */
        var closeBtn = document.getElementById('ppCloseBtn');
        if (closeBtn) closeBtn.addEventListener('click', function () {
            clearFloatingTimeout();
            closePopup(overlay, sessionKey);
        });

        /* ESC */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { clearFloatingTimeout(); closePopup(overlay, sessionKey); }
        }, { once: true });


        /* klik di luar modal */
        overlay.addEventListener('click', function (e) {
            if (overlay.classList.contains('pp-floating')) return;
            if (!e.target.closest('#ppModal')) {
                clearFloatingTimeout();
                closePopup(overlay, sessionKey);
            }
        });

        /* float after 5 s */
        floatingTimer = setTimeout(function () { makeFloating(overlay, sessionKey); }, 5000);
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', openPopup);
    else setTimeout(openPopup, 150);

})();