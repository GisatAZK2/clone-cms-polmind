@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-gear"></i> <span data-translate="settings.title">Pengaturan Sistem</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-4 py-3 py-md-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <form id="settingsForm">
                        <div class="row g-3 g-md-4">
                            <!-- Tema -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="theme" class="form-label fw-semibold mb-2">
                                        <i class="bi bi-palette"></i> 
                                        <span data-translate="settings.theme">Tema</span>
                                    </label>
                                    <select class="form-select form-select-sm" id="theme" name="theme">
                                        <option value="light"> <span data-translate="settings.themeLight">Light</span></option>
                                        <option value="dark"> <span data-translate="settings.themeDark">Dark</span></option>
                                        <option value="system"> <span data-translate="settings.themeSystem">Ikuti Sistem</span></option>
                                    </select>
                                    <div class="form-text small mt-2" data-translate="settings.themeInfo">Theme berubah ketika sudah klik tombol simpan.</div>
                                </div>
                            </div>

                            <!-- Bahasa -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="locale" class="form-label fw-semibold mb-2">
                                        <i class="bi bi-globe"></i> 
                                        <span data-translate="settings.language">Bahasa</span>
                                    </label>
                                    <select class="form-select form-select-sm" id="locale" name="locale">
                                        <option value="id"><span data-translate="settings.languageIndo">Bahasa Indonesia</span></option>
                                        <option value="en"><span data-translate="settings.languageEng">English</span></option>
                                    </select>
                                    <div class="form-text small mt-2" data-translate="settings.languageInfo">Mengubah teks antarmuka (masih dalam pengembangan).</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mt-3 mt-md-4">
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-success w-100 w-md-auto">
                                    <i class="bi bi-save"></i> 
                                    <span data-translate="settings.save">Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Load saved theme
        const savedTheme = localStorage.getItem('admin_theme') || 'light';
        document.getElementById('theme').value = savedTheme;

        // Load saved language
        const savedLanguage = localStorage.getItem('admin_language') || 'id';
        document.getElementById('locale').value = savedLanguage;

        // Theme change handler
        document.getElementById('theme').addEventListener('change', function () {
            AdminTheme.setTheme(this.value);
        });

        // Language change handler
        document.getElementById('locale').addEventListener('change', function () {
            AdminTranslate.setLanguage(this.value);
            AdminTranslate.translatePage();
        });

        // Form submission
        document.getElementById('settingsForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const theme = document.getElementById('theme').value;
            const language = document.getElementById('locale').value;

            localStorage.setItem('admin_theme', theme);
            localStorage.setItem('admin_language', language);

            AdminTheme.setTheme(theme);
            AdminTranslate.setLanguage(language);
            AdminTranslate.translatePage();

            // Show success message
            Swal.fire({
                icon: 'success',
                title: AdminTranslate.t('common.success'),
                text: 'Pengaturan berhasil disimpan',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });
</script>

<style>
    @media (max-width: 576px) {
        .card {
            margin: 0 -12px;
            border-radius: 0;
        }

        .form-select-sm {
            font-size: 0.875rem;
        }

        .btn {
            font-size: 0.875rem;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-text {
            font-size: 0.8rem !important;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .form-group {
            margin-bottom: 0;
        }
    }

    /* Theme styles */
    [data-theme="dark"] {
        --bs-body-bg: #1a1a1a;
        --bs-body-color: #f0f0f0;
    }

    [data-theme="dark"] .card {
        background-color: #2d2d2d;
        color: #f0f0f0;
        border-color: #444;
    }

    [data-theme="dark"] .card-header {
        background-color: #3a3a3a !important;
        border-color: #444;
    }

    [data-theme="dark"] .form-select,
    [data-theme="dark"] .form-control {
        background-color: #3a3a3a;
        color: #f0f0f0;
        border-color: #555;
    }

    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-control:focus {
        background-color: #3a3a3a;
        color: #f0f0f0;
        border-color: #666;
    }

    [data-theme="dark"] .form-text {
        color: #aaa !important;
    }

    [data-theme="dark"] .text-muted {
        color: #999 !important;
    }
</style>
@endsection
