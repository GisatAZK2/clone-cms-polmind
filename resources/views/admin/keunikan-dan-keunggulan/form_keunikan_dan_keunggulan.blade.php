@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-stars"></i>
    @isset($item)
        <span data-translate="keunikanKeunggulan.editTitle">Edit Keunikan &amp; Keunggulan</span>
    @else
        <span data-translate="keunikanKeunggulan.addNew">Tambah Keunikan &amp; Keunggulan</span>
    @endisset
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
<div class="row g-3 g-md-4 justify-content-center">

    {{-- ===== FORM COLUMN ===== --}}
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">
                    @isset($item)
                        <i class="bi bi-pencil-square me-1"></i>
                        <span data-translate="keunikanKeunggulan.editTitle">Edit Keunikan &amp; Keunggulan</span>
                    @else
                        <i class="bi bi-file-plus me-1"></i>
                        <span data-translate="keunikanKeunggulan.addNew">Tambah Keunikan &amp; Keunggulan</span>
                    @endisset
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <strong>Error!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="mainForm"
                      action="{{ isset($item)
                                ? route('admin.keunikan-dan-keunggulan.update', $item->id)
                                : route('admin.keunikan-dan-keunggulan.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      novalidate>
                    @csrf
                    @isset($item) @method('PUT') @endisset

                    {{-- ===== TITLE ===== --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <span data-translate="keunikanKeunggulan.fieldTitle">Judul</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $item->content['title'] ?? '') }}"
                               placeholder="Masukkan judul keunikan & keunggulan"
                               data-translate-placeholder="keunikanKeunggulan.titlePlaceholder"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ===== CONTENT BLOCKS ===== --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <span data-translate="keunikanKeunggulan.fieldContent">Konten</span>
                            <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            <span data-translate="keunikanKeunggulan.contentHint">
                                Susun blok paragraf dan gambar sesuai kebutuhan. Urutan blok = urutan tampilan.
                            </span>
                        </p>
                    </div>

                    {{-- BLOCK CONTAINER --}}
                    <div id="blockContainer"
                            class="mb-3"
                            data-storage-base="{{ asset('storage') }}"
                            data-edit-blocks='@json($item->content["blocks"] ?? [])'>
                        {{-- Diisi oleh JavaScript. Saat edit, PHP mencetak data lama melalui JS. --}}
                    </div>

                    {{-- ADD BUTTONS --}}
                    <div class="d-flex gap-2 mb-4">
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            id="addKeunikanParagraphBlock">
                            <i class="bi bi-paragraph me-1"></i>
                            <span data-translate="keunikanKeunggulan.addParagraph">+ Paragraf</span>
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                id="addKeunikanImageBlock">
                            <i class="bi bi-image me-1"></i>
                            <span data-translate="keunikanKeunggulan.addImage">+ Gambar</span>
                        </button>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="{{ route('admin.keunikan-dan-keunggulan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            <span data-translate="common.cancel">Batal</span>
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i>
                            @isset($item)
                                <span data-translate="common.update">Update</span>
                            @else
                                <span data-translate="common.create">Simpan</span>
                            @endisset
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ===== SIDEBAR ===== --}}
    <div class="col-12 col-lg-3">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    <span data-translate="keunikanKeunggulan.infoLabel">Panduan</span>
                </h6>
            </div>
            <div class="card-body p-3 small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-type-h1 text-primary me-1"></i>
                        <span data-translate="keunikanKeunggulan.infoTitle">Isi judul terlebih dahulu.</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-paragraph text-info me-1"></i>
                        <span data-translate="keunikanKeunggulan.infoParagraph">Klik "+ Paragraf" untuk menambah blok teks dengan rich editor.</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-image text-warning me-1"></i>
                        <span data-translate="keunikanKeunggulan.infoImage">Klik "+ Gambar" untuk menambah blok gambar. Saat edit, centang "Hapus Gambar" untuk menghapus file dari server.</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrows-move text-success me-1"></i>
                        <span data-translate="keunikanKeunggulan.infoOrder">Urutan blok = urutan tampilan. Klik ↑↓ untuk mengubah urutan.</span>
                    </li>
                    <li class="mt-3 text-muted border-top pt-2">
                        <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                        <span data-translate="keunikanKeunggulan.infoImageDelete">Gambar yang dihapus atau diganti akan dihapus permanen dari server.</span>
                    </li>
                </ul>
            </div>
        </div>

        @isset($item)
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-1"></i>
                    <span data-translate="keunikanKeunggulan.infoTimestamp">Informasi</span>
                </h6>
            </div>
            <div class="card-body p-3 small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" data-translate="keunikanKeunggulan.infoCreated">Dibuat:</span>
                    <span>{{ $item->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" data-translate="keunikanKeunggulan.infoUpdated">Diperbarui:</span>
                    <span>{{ $item->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        @endisset
    </div>

</div>
</div>

{{-- ============================================================
     STYLES
============================================================ --}}
<style>
    /* Dark mode */
    [data-theme="dark"] .card                    { background-color:#2d2d2d;color:#f0f0f0;border:none; }
    [data-theme="dark"] .card-header            { background-color:#3a3a3a!important;color:#f0f0f0;border-bottom-color:#444; }
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select            { background-color:#3a3a3a;color:#f0f0f0;border-color:#555; }
    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus      { background-color:#444;color:#f0f0f0;border-color:#6c757d;box-shadow:none; }
    [data-theme="dark"] .form-control::placeholder { color:#888; }
    [data-theme="dark"] .form-label             { color:#ddd; }
    [data-theme="dark"] .text-muted             { color:#999!important; }
    [data-theme="dark"] .img-thumbnail          { background-color:#3a3a3a;border-color:#555; }
    [data-theme="dark"] .btn-outline-secondary  { color:#aaa;border-color:#666; }
    [data-theme="dark"] .btn-outline-secondary:hover { background-color:#555;color:#fff; }
    [data-theme="dark"] .alert-danger           { background-color:#4a2020;border-color:#7a3535;color:#f0f0f0; }
    [data-theme="dark"] .block-card            { background-color:#333;border-color:#555; }
    [data-theme="dark"] .block-card .block-header { background-color:#3d3d3d;border-bottom-color:#555; }

    /* Block card */
    .block-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .block-header {
        background: #f8f9fa;
        padding: 0.5rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #dee2e6;
    }
    .block-body {
        padding: 0.75rem;
    }
    .block-badge-paragraph { color:#0dcaf0; }
    .block-badge-image     { color:#ffc107; }

    /* Sembunyikan label bawaan text-editor di dalam block-card karena sudah ada block-header */
    .block-card .block-body .form-group > label {
        display: none;
    }
    /* Sesuaikan border TinyMCE agar menyatu dengan block-card */
    .block-card .tox-tinymce {
        border-color: #dee2e6;
    }
    [data-theme="dark"] .block-card .tox-tinymce {
        border-color: #555;
    }
</style>

@once
    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
@endonce

@endsection