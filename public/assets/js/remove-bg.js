
// =========================================================================
// REMOVE BACKGROUND IMAGE
// =========================================================================

const RemoveBG = {
    config: {
        apiKey: 'nYmePSiLmeDtU6k9vxkCv6Tp',
        apiUrl: 'https://api.remove.bg/v1.0/removebg'
    },
    isProcessing: false,

    init(inputId, previewId, buttonId, loadingId, resultId, processedInputId) {
        this.input = document.getElementById(inputId);
        this.preview = document.getElementById(previewId);
        this.button = document.getElementById(buttonId);
        this.loading = document.getElementById(loadingId);
        this.result = document.getElementById(resultId);
        this.processedInput = document.getElementById(processedInputId);

        if (this.button) {
            this.button.addEventListener('click', () => this.process());
        }

        // Tampilkan tombol ketika file dipilih
        if (this.input) {
            this.input.addEventListener('change', (e) => {
                const section = document.getElementById('removeBgSection');
                if (section && e.target.files.length > 0) {
                    section.style.display = 'block';
                }
            });

            // Trigger change if file already exists
            if (this.input.files.length > 0) {
                const section = document.getElementById('removeBgSection');
                if (section) section.style.display = 'block';
            }
        }

        // If there's an existing preview image (edit mode), show remove section
        if (this.preview && this.preview.src) {
            const section = document.getElementById('removeBgSection');
            if (section) section.style.display = 'block';
        }
    },

    async process() {
        if (this.isProcessing) return;
        let file = null;

        if (this.input && this.input.files && this.input.files[0]) {
            file = this.input.files[0];
        } else if (this.preview && this.preview.src) {
            // Try fetch existing preview from storage (edit mode)
            try {
                const resp = await fetch(this.preview.src);
                if (!resp.ok) throw new Error('Gagal memuat gambar dari server');
                const blob = await resp.blob();
                file = new File([blob], 'existing.png', { type: blob.type || 'image/png' });
            } catch (err) {
                alert('Pilih foto terlebih dahulu!');
                return;
            }
        } else {
            alert('Pilih foto terlebih dahulu!');
            return;
        }
        this.isProcessing = true;

        if (this.loading) this.loading.classList.remove('d-none');
        if (this.button) this.button.disabled = true;
        if (this.result) this.result.classList.add('d-none');

        try {
            const formData = new FormData();
            formData.append('image_file', file);
            formData.append('size', 'auto');

            const response = await fetch(this.config.apiUrl, {
                method: 'POST',
                headers: { 'X-Api-Key': this.config.apiKey },
                body: formData
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.errors?.[0]?.title || 'Gagal menghapus background');
            }

            const blob = await response.blob();
            const processedFileName = `no-bg-${Date.now()}.png`;
            const processedFile = new File([blob], processedFileName, { type: 'image/png' });
            const previewUrl = URL.createObjectURL(blob);

            // Update preview
            if (this.preview) this.preview.src = previewUrl;

            // Update file input
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(processedFile);
                if (this.input) this.input.files = dataTransfer.files;
            } catch (e) {
                // ignore if DataTransfer not available
            }

            // Tandai sudah diproses (berguna untuk server-side logic)
            if (this.processedInput) this.processedInput.value = 'processed';

            // Pastikan preview wrapper terlihat
            const fotoPreviewWrap = document.getElementById('fotoPreviewWrap');
            const fotoPlaceholder = document.getElementById('fotoPlaceholder');
            if (fotoPreviewWrap) fotoPreviewWrap.classList.remove('d-none');
            if (fotoPlaceholder) fotoPlaceholder.classList.add('d-none');

            // Tandai sudah diproses
            if (this.processedInput) this.processedInput.value = 'processed';

            // Re-validate ratio
            if (typeof ImageRatio !== 'undefined') {
                await ImageRatio.validateFile(processedFile);
            }

            if (this.result) this.result.classList.remove('d-none');

            setTimeout(() => URL.revokeObjectURL(previewUrl), 1000);

        } catch (error) {
            console.error('Remove BG Error:', error);
            alert('Gagal menghapus background: ' + error.message);
        } finally {
            this.isProcessing = false;
            if (this.loading) this.loading.classList.add('d-none');
            if (this.button) this.button.disabled = false;
        }
    }
};

window.RemoveBG = RemoveBG;
