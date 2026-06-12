@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-file-earmark-text"></i>
    @if(isset($dokumentasi))
        <span data-translate="dokumentasi.editForm">Edit Dokumentasi</span>
    @else
        <span data-translate="dokumentasi.addNew">Tambah Dokumentasi</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.dokumentasi.index') }}" class="text-muted text-decoration-none me-1 me-md-2">
                <i class="bi bi-chevron-left"></i>
                <span data-translate="dokumentasi.title">Dokumentasi</span>
            </a>

            <span class="text-muted d-none d-md-inline">/</span>

            <span class="ms-0 ms-md-2">
                @if(isset($dokumentasi))
                    <span data-translate="common.editForm">Edit</span>
                @else
                    <span data-translate="common.new">Baru</span>
                @endif
            </span>
        </div>
    </div>
</div>

<div class="editor-container px-2 px-md-3">
    <div class="editor-main">
        <form action="{{ isset($dokumentasi) ? route('admin.dokumentasi.update', $dokumentasi->id) : route('admin.dokumentasi.store') }}"
            method="POST"
            id="mainForm"
            enctype="multipart/form-data"
            novalidate>
            @csrf

            @if(isset($dokumentasi))
                @method('PUT')
            @endif

            {{-- ==================== TITLE ==================== --}}
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="dokumentasi.titleField">
                    Title
                </div>

                <div class="editor-card-body">
                    <label class="form-label">
                        <span data-translate="dokumentasi.titleField">Title</span>
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Masukkan judul dokumentasi"
                        data-translate-placeholder="dokumentasi.titlePlaceholder"
                        value="{{ old('title', $dokumentasi->content['title'] ?? '') }}"
                        required>

                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ==================== DESKRIPSI ==================== --}}
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="dokumentasi.deskripsiField">
                    Deskripsi
                </div>

                <div class="editor-card-body">
                    @error('deskripsi')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror

                    @include('admin.components.text-editor', [
                        'name' => 'deskripsi',
                        'label' => false,
                        'value' => old('deskripsi', $dokumentasi->content['deskripsi'] ?? ''),
                        'required' => true,
                    ])

                    <small class="text-muted" data-translate="dokumentasi.deskripsiHint">
                        Masukkan deskripsi dokumentasi.
                    </small>
                </div>
            </div>

            {{-- ==================== IMAGE BLOCKS ==================== --}}
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="dokumentasi.imagesSection">
                    Gambar Dokumentasi
                </div>

                <div class="editor-card-body">
                    <div
                        id="imageBlocks"
                        class="g-3"
                        data-storage-base="{{ asset('storage') }}"
                        data-existing-items='@json(isset($dokumentasi) ? ($dokumentasi->content["items"] ?? []) : [])'>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" id="addImageBlock">
                        <i class="bi bi-plus-circle"></i>
                        <span data-translate="dokumentasi.addImage">Tambah Gambar</span>
                    </button>

                    <p class="small text-muted mt-2" data-translate="dokumentasi.imageHint">
                        Gambar yang dihapus atau diganti akan dihapus dari storage.
                    </p>
                </div>
            </div>
        </form>
    </div>

    {{-- ==================== SIDEBAR ==================== --}}
    <div class="editor-sidebar">
        <div class="publish-box">
            <div class="publish-box-section">
                <div class="action-buttons">
                    <a href="{{ route('admin.dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i>
                        <span data-translate="common.cancel">Batal</span>
                    </a>

                    <button type="submit" form="mainForm" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i>
                        @if(isset($dokumentasi))
                            <span data-translate="common.update">Perbarui</span>
                        @else
                            <span data-translate="common.save">Simpan</span>
                        @endif
                    </button>
                </div>
            </div>

            @if(isset($dokumentasi))
                <div class="publish-box-section">
                    <span class="publish-box-label" data-translate="dokumentasi.infoLabel">
                        Informasi
                    </span>

                    <small class="text-muted d-block">
                        <span data-translate="dokumentasi.infoCreated">Dibuat</span>:
                        {{ $dokumentasi->created_at->format('d M Y') }}
                    </small>

                    <small class="text-muted d-block">
                        <span data-translate="dokumentasi.infoUpdated">Diperbarui</span>:
                        {{ $dokumentasi->updated_at->format('d M Y') }}
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .editor-container {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.5rem;
        align-items: start;
        padding-bottom: 2rem;
    }

    .editor-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .editor-card-header {
        padding: 0.75rem 1rem;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .editor-card-body {
        padding: 1rem;
    }

    .editor-header {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .publish-box-section {
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        margin-bottom: 0.75rem;
    }

    .publish-box-label {
        font-size: 0.85rem;
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .image-block {
    touch-action: pan-y;
    user-select: none;
    transition: transform 0.18s ease, opacity 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}

.image-block.is-touching {
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
    z-index: 5;
}

.image-block.order-changed {
    outline: 2px solid #2563eb;
    background: rgba(37, 99, 235, 0.08) !important;
}

[data-theme="dark"] .image-block.order-changed {
    background: rgba(59, 130, 246, 0.16) !important;
}

    .image-block {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        background: #fdfdfd;
        position: relative;
        cursor: grab;
    }

    .image-block.dragging {
        opacity: 0.5;
    }

    .image-block.drag-over {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
    }

    .image-block.removed {
        opacity: 0.5;
    }

    .image-block .remove-block {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
    }

    .image-block .block-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        padding-right: 2.8rem;
    }

    .image-block .block-header .drag-handle {
        cursor: grab;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        padding: 0.4rem 0.6rem;
        border-radius: 0.4rem;
    }

    .image-block .block-header .move-buttons {
        display: flex;
        gap: 0.3rem;
    }

    .image-block .block-header .move-buttons button {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
    }

    .image-block.d-none {
        display: none;
    }

    @media (max-width: 768px) {
        .editor-container {
            grid-template-columns: 1fr;
        }
    }

    [data-theme="dark"] .editor-card {
        background-color: #2d2d2d;
        color: #f0f0f0;
        border-color: #444;
    }

    [data-theme="dark"] .editor-card-header {
        background-color: #3a3a3a;
        color: #f0f0f0;
        border-bottom-color: #444;
    }

    [data-theme="dark"] .editor-card-body {
        background-color: #2d2d2d;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .tox .tox-editor-container {
        background-color: #3a3a3a !important;
        color: #f0f0f0 !important;
        border-color: #555 !important;
    }

    [data-theme="dark"] .publish-box-section {
        background-color: #3a3a3a;
    }

    [data-theme="dark"] .text-muted {
        color: #999 !important;
    }

    [data-theme="dark"] .btn-outline-primary {
        color: #cbd5ff;
        border-color: #5363cc;
    }

    [data-theme="dark"] .btn-outline-primary:hover {
        background-color: rgba(83, 99, 204, 0.12);
    }

    [data-theme="dark"] .editor-header {
        border-bottom-color: #444;
        background-color: #1e1e1e;
    }

    [data-theme="dark"] .editor-header a.text-muted,
    [data-theme="dark"] .editor-header .text-muted {
        color: #aaa !important;
    }

    [data-theme="dark"] .image-block {
        background-color: #2d2d2d;
        border-color: #444;
    }

    [data-theme="dark"] .image-block .block-header .drag-handle {
        background-color: #3a3a3a;
        border-color: #555;
        color: #f0f0f0;
    }
</style>

<script>
    
</script>
@endsection