/**
 * Sweet Alert 2 Alert System
 * Comprehensive alert functions for success, error, warning, info, and confirmation
 * 
 * @author SWEETALERT2
 */

(function () {
    'use strict';

    // =========================================================================
    // ALERT CONFIGURATION & HELPER FUNCTIONS
    // =========================================================================

    /**
     * Default Sweet Alert configuration
     */
    const defaultConfig = {
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            container: 'swal2-custom-container',
            popup: 'swal2-custom-popup',
            header: 'swal2-custom-header',
            title: 'swal2-custom-title',
            closeButton: 'swal2-custom-close-button',
            content: 'swal2-custom-content',
            htmlContainer: 'swal2-custom-html-container',
            input: 'swal2-custom-input',
            inputLabel: 'swal2-custom-input-label',
            validationMessage: 'swal2-custom-validation-message',
            actions: 'swal2-custom-actions',
            confirmButton: 'swal2-custom-confirm-button btn btn-primary',
            denyButton: 'swal2-custom-deny-button btn btn-secondary',
            cancelButton: 'swal2-custom-cancel-button btn btn-outline-secondary',
            loader: 'swal2-custom-loader',
            footer: 'swal2-custom-footer'
        }
    };

    // =========================================================================
    // 1. SUCCESS ALERT
    // =========================================================================
    window.alertSuccess = function (title, message, callback) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'success',
            title: title || 'Berhasil!',
            html: message || 'Operasi berhasil dilakukan.',
            confirmButtonText: 'OK',
            didClose: callback
        });
    };

    // =========================================================================
    // 2. ERROR ALERT
    // =========================================================================
    window.alertError = function (title, message, callback) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'error',
            title: title || 'Terjadi Kesalahan!',
            html: message || 'Operasi gagal dilakukan.',
            confirmButtonText: 'OK',
            didClose: callback
        });
    };

    // =========================================================================
    // 3. WARNING ALERT
    // =========================================================================
    window.alertWarning = function (title, message, callback) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'warning',
            title: title || 'Peringatan!',
            html: message || 'Pastikan sebelum melanjutkan.',
            confirmButtonText: 'OK',
            didClose: callback
        });
    };

    // =========================================================================
    // 4. INFO ALERT
    // =========================================================================
    window.alertInfo = function (title, message, callback) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'info',
            title: title || 'Informasi',
            html: message || 'Informasi tambahan untuk Anda.',
            confirmButtonText: 'OK',
            didClose: callback
        });
    };

    // =========================================================================
    // 5. CONFIRMATION ALERT (Yes/No)
    // =========================================================================
    window.alertConfirm = function (title, message, onConfirm, onCancel) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'question',
            title: title || 'Konfirmasi',
            html: message || 'Apakah Anda yakin?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            didClose: function (result) {
                if (result.isConfirmed && onConfirm) {
                    onConfirm();
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel && onCancel) {
                    onCancel();
                }
            }
        });
    };

    // =========================================================================
    // 6. DELETE CONFIRMATION ALERT
    // =========================================================================
    window.alertDelete = function (title, message, onConfirm, onCancel) {
        return Swal.fire({
            ...defaultConfig,
            icon: 'warning',
            title: title || 'Hapus Data?',
            html: message || 'Data yang dihapus tidak dapat dipulihkan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            didClose: function (result) {
                if (result.isConfirmed && onConfirm) {
                    onConfirm();
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel && onCancel) {
                    onCancel();
                }
            }
        });
    };

    // =========================================================================
    // 7. LOADING ALERT (without confirm button)
    // =========================================================================
    window.alertLoading = function (title, message) {
        return Swal.fire({
            ...defaultConfig,
            title: title || 'Memproses...',
            html: message || 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: (modalElement) => {
                Swal.showLoading();
            },
            willClose: () => {
                Swal.hideLoading();
            }
        });
    };

    // =========================================================================
    // 8. CLOSE LOADING ALERT
    // =========================================================================
    window.closeAlert = function () {
        return Swal.close();
    };

    // =========================================================================
    // 9. INPUT PROMPT ALERT
    // =========================================================================
    window.alertPrompt = function (title, message, inputType, onConfirm, onCancel) {
        return Swal.fire({
            ...defaultConfig,
            title: title || 'Input Data',
            html: message || 'Masukkan data berikut:',
            input: inputType || 'text',
            inputAttributes: {
                autocapitalize: 'off',
                class: 'form-control'
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            didClose: function (result) {
                if (result.isConfirmed && onConfirm) {
                    onConfirm(result.value);
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel && onCancel) {
                    onCancel();
                }
            }
        });
    };

    // =========================================================================
    // 10. CUSTOM ALERT
    // =========================================================================
    window.alertCustom = function (config) {
        return Swal.fire({
            ...defaultConfig,
            ...config
        });
    };

    // =========================================================================
    // TOAST NOTIFICATIONS
    // =========================================================================

    /**
     * Toast configuration
     */
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'swal2-toast-custom'
        }
    });

    // =========================================================================
    // 11. SUCCESS TOAST
    // =========================================================================
    window.toastSuccess = function (message) {
        return Toast.fire({
            icon: 'success',
            title: message || 'Berhasil!'
        });
    };

    // =========================================================================
    // 12. ERROR TOAST
    // =========================================================================
    window.toastError = function (message) {
        return Toast.fire({
            icon: 'error',
            title: message || 'Terjadi kesalahan!'
        });
    };

    // =========================================================================
    // 13. WARNING TOAST
    // =========================================================================
    window.toastWarning = function (message) {
        return Toast.fire({
            icon: 'warning',
            title: message || 'Peringatan!'
        });
    };

    // =========================================================================
    // 14. INFO TOAST
    // =========================================================================
    window.toastInfo = function (message) {
        return Toast.fire({
            icon: 'info',
            title: message || 'Informasi'
        });
    };

    // =========================================================================
    // 15. CUSTOM TOAST
    // =========================================================================
    window.toastCustom = function (config) {
        return Toast.fire(config);
    };

})();
