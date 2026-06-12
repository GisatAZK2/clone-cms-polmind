
// =========================================================================
// 2. IMAGE RATIO CHECKER
// =========================================================================

const ImageRatio = {
    config: {
        expectedRatio: 2 / 3,
        expectedWidth: 410,
        expectedHeight: 634,
        tolerance: 0.05
    },
    _isValid: false,
    currentDimensions: null,

    init(inputId, previewId, alertId, messageId, validInputId) {
        this.input = document.getElementById(inputId);
        this.preview = document.getElementById(previewId);
        this.alert = document.getElementById(alertId);
        this.message = document.getElementById(messageId);
        this.validInput = document.getElementById(validInputId);

        if (this.input) {
            this.input.addEventListener('change', (e) => this.validateFile(e.target.files[0]));
        }

        // Jika ada preview existing (mode edit)
        if (this.preview && this.preview.src && this.preview.src !== '') {
            this.validateExistingImage(this.preview.src);
        }
    },

    getImageDimensions(file) {
        return new Promise((resolve) => {
            if (!file || (file.size !== undefined && file.size === 0)) {
                // treat empty file as no dimensions
                return resolve(null);
            }

            if (file.type && !file.type.startsWith('image/')) {
                return resolve(null);
            }

            const img = new Image();
            const url = URL.createObjectURL(file);
            img.onload = () => {
                URL.revokeObjectURL(url);
                resolve({ width: img.width, height: img.height });
            };
            img.onerror = () => {
                URL.revokeObjectURL(url);
                resolve(null);
            };
            img.src = url;
        });
    },

    async validateFile(file) {
        if (!file) {
            this.setValid(false);
            return false;
        }

        try {
            const dimensions = await this.getImageDimensions(file);
            if (!dimensions) {
                // couldn't read image dimensions (empty or invalid image)
                this.setValid(false);
                // do not log error for empty/invalid images
                return false;
            }
            this.currentDimensions = dimensions;
            const ratio = dimensions.width / dimensions.height;
            const expected = this.config.expectedRatio;
            const isValid = Math.abs(ratio - expected) <= this.config.tolerance;

            this.setValid(isValid);
            this.showNotification(dimensions, ratio, isValid);

            return isValid;
        } catch (error) {
            // Unexpected error while validating — mark invalid but don't spam console
            this.setValid(false);
            return false;
        }
    },

    async validateExistingImage(src) {
        try {
            const response = await fetch(src);
            const blob = await response.blob();
            const file = new File([blob], 'existing.jpg', { type: blob.type });
            await this.validateFile(file);
        } catch (error) {
            console.error('Error validating existing image:', error);
        }
    },

    setValid(valid) {
        this._isValid = valid;
        if (this.validInput) this.validInput.value = valid ? '1' : '0';
    },

    showNotification(dimensions, ratio, isValid) {
        if (!this.alert || !this.message) return;

        if (isValid) {
            this.message.innerHTML = `
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    <strong>Rasio gambar sesuai!</strong><br>
                    Dimensi: ${dimensions.width} x ${dimensions.height} pixel (Rasio: ${ratio.toFixed(4)})
                `;
            this.alert.classList.remove('alert-warning');
            this.alert.classList.add('alert-success');
            this.alert.classList.remove('d-none');

            setTimeout(() => {
                this.alert.classList.add('d-none');
            }, 5000);
        } else {
            let suggestion = '';
            if (ratio > this.config.expectedRatio) {
                suggestion = `Gambar terlalu lebar. Gunakan rasio ${this.config.expectedWidth}x${this.config.expectedHeight} pixel.`;
            } else {
                suggestion = `Gambar terlalu tinggi. Gunakan rasio ${this.config.expectedWidth}x${this.config.expectedHeight} pixel.`;
            }

            this.message.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                    <strong>Perhatian: Rasio gambar tidak sesuai!</strong><br>
                    Dimensi: ${dimensions.width} x ${dimensions.height} pixel (Rasio: ${ratio.toFixed(4)})<br>
                    Yang diharapkan: 2:3 (${this.config.expectedWidth}x${this.config.expectedHeight} pixel)<br>
                    <span class="text-danger">${suggestion}</span>
                `;
            this.alert.classList.remove('alert-success');
            this.alert.classList.add('alert-warning');
            this.alert.classList.remove('d-none');
        }

        this.alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },

    isValid() {
        return !!this._isValid;
    }
};

window.ImageRatio = ImageRatio;