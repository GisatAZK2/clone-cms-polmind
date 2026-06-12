/**
 * Admin modules entrypoint.
 * Loaded by assets/js/admin-script.js classic loader.
 */
import { ActiveMenu } from '../modules/active-menu.js';
import { MenuSearch } from '../modules/menu-search.js';
import { StaticEditors } from '../modules/static-editors.js';
import { BulkDelete } from '../modules/bulk-delete.js';
import { ConfirmDelete } from '../modules/confirm-delete.js';
import { ReorderList } from '../modules/reorder-list.js';
import { DynamicBlocks } from '../modules/dynamic-blocks.js';
import { HeadlinePreview } from '../modules/headline-preview.js';
import { HeadlineForm } from '../modules/headline-form.js';
import { LoginPage } from '../modules/login-page.js';
import { ContentPendaftaranForm } from '../modules/content-pendaftaran-form.js';
import { DokumentasiForm } from '../modules/dokumentasi-form.js';
import { DosenForm } from '../modules/dosen-form.js';
import { KeunikanKeunggulanForm } from '../modules/keunikan-keunggulan-form.js';
import { SimpleImagePreview } from '../modules/simple-image-preview.js';
import { ProgramSarjanaTerapanForm } from '../modules/program-sarjana-terapan-form.js';
import { PendaftaranMahasiswaForm } from '../modules/pendaftaran-mahasiswa-form.js';
import { ProfilePageForm } from '../modules/profile-page-form.js';
import { ProdiForm } from '../modules/prodi-form.js';
import { HomeStatForm } from '../modules/home-stat-form.js';
import { SambutanDirekturForm } from '../modules/sambutan-direktur-form.js';
import { ImageModal } from '../modules/image-modal.js';

const initClickableImages = () => {
    const fotoPreview = document.getElementById('fotoPreview');
    if (fotoPreview) {
        fotoPreview.style.cursor = 'pointer';
        fotoPreview.title = 'Klik untuk memperbesar';
        fotoPreview.addEventListener('click', function () {
            if (this.src) ImageModal.open(this.src, this.alt || 'Foto');
        });
    }

    document.querySelectorAll('.clickable-image').forEach(img => {
        if (img.dataset.modalAttached) return;
        img.style.cursor = 'pointer';
        img.dataset.modalAttached = 'true';
        img.addEventListener('click', function () {
            if (this.src) ImageModal.open(this.src, this.alt || 'Gambar');
        });
    });
};

const bootAdmin = () => {
    window.ImageModal = ImageModal;

    ActiveMenu.init();
    MenuSearch.init();
    StaticEditors.init();
    BulkDelete.init();
    ConfirmDelete.init();
    ImageModal.init();

    ReorderList.create({
        formId: 'headlineOrderForm', inputId: 'headlineOrderInput', saveBtnId: 'saveHeadlineOrderBtn',
        tableBodySelector: '#headlineReorderTable tbody', mobileListId: 'headlineMobileList',
        rowClass: 'headline-order-row', cardClass: 'headline-order-card', numberSelector: '.headline-number',
    });

    ReorderList.create({
        formId: 'newsOrderForm', inputId: 'newsOrderInput', saveBtnId: 'saveNewsOrderBtn',
        tableBodySelector: '#newsReorderTable tbody', mobileListId: 'newsMobileList',
        rowClass: 'news-order-row', cardClass: 'news-order-card', numberSelector: '.news-number',
    });

    DynamicBlocks.init();
    HeadlinePreview.init();
    HeadlineForm.init();
    LoginPage.init();
    ContentPendaftaranForm.init();
    DokumentasiForm.init();
    DosenForm.init();
    SimpleImagePreview.init();
    ProgramSarjanaTerapanForm.init();
    PendaftaranMahasiswaForm.init();
    HomeStatForm.init();
    SambutanDirekturForm.init();
    ProfilePageForm.init();
    ProdiForm.init();
    KeunikanKeunggulanForm.init();

    initClickableImages();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootAdmin, { once: true });
} else {
    bootAdmin();
}
