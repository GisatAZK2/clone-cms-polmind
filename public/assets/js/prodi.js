// =============================================
// PRODI GALLERY MODAL
// =============================================

function prodiAdjustImage(imgElement) {
    if (!imgElement.naturalWidth || !imgElement.naturalHeight) {
        return;
    }

    if (imgElement.naturalHeight > imgElement.naturalWidth) {
        imgElement.classList.add('portrait');
    } else {
        imgElement.classList.remove('portrait');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.prodi-gallery-img').forEach(img => {
        if (img.complete) {
            prodiAdjustImage(img);
        } else {
            img.addEventListener('load', () => prodiAdjustImage(img));
        }
    });
});

function prodiOpenModal(imgElement) {
    const modal = document.getElementById('prodi-modal');
    const modalImg = document.getElementById('prodi-modal-img');

    modal.style.display = 'flex';
    modalImg.src = imgElement.src;
    modalImg.alt = imgElement.alt || '';
}

function prodiCloseModal() {
    document.getElementById('prodi-modal').style.display = 'none';
}

// Tutup modal saat klik backdrop (bukan gambar)
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('prodi-modal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.classList.contains('prodi-close')) {
                prodiCloseModal();
            }
        });
    }
});

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        prodiCloseModal();
    }
});

// =============================================
// SEMESTER CARD TOGGLE
// =============================================

function toggleProdiCard(headerElement) {
    const card = headerElement.closest('.card-prodi');

    const container = card.closest('.card-prodi-container');
    const allCards = container.querySelectorAll('.card-prodi');

    allCards.forEach(function (c) {
        if (c !== card) {
            c.classList.remove('expanded');
            c.querySelector('.card-prodi-header').classList.remove('active');
        }
    });

    card.classList.toggle('expanded');
    headerElement.classList.toggle('active');
}