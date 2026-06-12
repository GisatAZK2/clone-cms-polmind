import { Core } from '../core/core.js';

export const SambutanDirekturForm = {
        init() {
            const form    = document.getElementById('mainForm');
            const fotoIn  = document.getElementById('fotoInput');
            const fotoPrev = document.getElementById('fotoPreview');
            const fotoWrap = document.getElementById('fotoPreviewWrap');
            const fotoHold = document.getElementById('fotoPlaceholder');
            const submitBtn = document.getElementById('submitBtn');
            if (!form || !fotoIn || !fotoPrev) return;

            Core.bindImagePreview(fotoIn, fotoPrev, fotoHold, fotoWrap);

            form.addEventListener('submit', e => {
                Core.tinymceSave();
                if (typeof ImageRatio !== 'undefined' && typeof ImageRatio.isValid === 'function' && !ImageRatio.isValid()) {
                    if (!confirm('Peringatan: Rasio gambar tidak sesuai rekomendasi (2:3).\nApakah Anda tetap ingin menyimpan?')) {
                        e.preventDefault(); return;
                    }
                }
                if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'; }
            });

            // Plugin opsional
            window.ImageModal?.init?.();
            window.RemoveBG?.init?.('fotoInput','fotoPreview','removeBgBtn','removeBgLoading','removeBgResult','fotoProcessedInput');
            window.ImageRatio?.init?.('fotoInput','fotoPreview','ratioAlert','ratioMessage','imageRatioValid');
            document.getElementById('removeBgBtn') && (document.getElementById('removeBgBtn').disabled = false);
        },
    };