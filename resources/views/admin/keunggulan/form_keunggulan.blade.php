@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-star"></i>
    @if(isset($keunggulan))
        <span data-translate="common.editForm">Edit</span> <span data-translate="keunggulan.content">Keunggulan</span>
    @else
        <span data-translate="keunggulan.addNew">Tambah Keunggulan</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            {{-- Breadcrumb --}}
            <a href="{{ route('admin.keunggulan.index') }}" ...>
                <i class="bi bi-chevron-left"></i> <span data-translate="keunggulan.title">Keunggulan</span>
            </a>
            <span class="text-muted d-none d-md-inline">/</span>
            <span class="ms-0 ms-md-2">
                @if(isset($keunggulan))
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
        <form action="{{ isset($keunggulan) ? route('admin.keunggulan.update', $keunggulan->id) : route('admin.keunggulan.store') }}"
            method="POST" id="mainForm" enctype="multipart/form-data" novalidate>
            @csrf
            @if(isset($keunggulan))
                @method('PUT')
            @endif

            <!-- URL Images & Alt -->
            <div class="editor-card mb-3">
             
<div class="editor-card-header" data-translate="keunggulan.image">Gambar</div>
                <div class="editor-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            {{-- Label Upload --}}
<label class="form-label">
    <span data-translate="keunggulan.image">Upload Gambar</span> <span class="text-danger">*</span>
</label>
                            <input type="file"
                                name="url_images"
                                class="form-control form-control-sm @error('url_images') is-invalid @enderror"
                                accept="image/*"
                                id="urlImagesInput"
                                {{ isset($keunggulan) ? '' : 'required' }}>
                            @error('url_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" data-translate="keunggulan.imageFormat">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        <div class="col-12 col-md-4">
                            {{-- Label Alt Text --}}
                            <label class="form-label" data-translate="keunggulan.altText">Alt Text</label>
                            <input type="text"
                                name="alt"
                                class="form-control form-control-sm"
                                data-translate-placeholder="keunggulan.altPlaceholder"
                                placeholder="Deskripsi gambar"
                                value="{{ old('alt', $keunggulan->alt ?? '') }}">
                        </div>
                        <div class="col-12">
                            <div id="imgPreviewWrap" class="{{ isset($keunggulan) && !empty($keunggulan->url_images) ? '' : 'd-none' }}">
                               <p class="small text-muted mb-1" data-translate="keunggulan.preview">Preview:</p>
                            <img id="imgPreview"
                                    src="{{ isset($keunggulan) && !empty($keunggulan->url_images) ? asset('storage/' . $keunggulan->url_images) : '' }}"
                                    alt="Preview"
                                    class="img-thumbnail preview-image"
                                    style="max-height:180px;object-fit:cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keunggulan -->
            <div class="editor-card mb-3">
                <div class="editor-card-header">
    <span data-translate="keunggulan.content">Keunggulan</span> <span class="text-danger">*</span>
</div>
                <div class="editor-card-body">
                    @include('admin.components.text-editor', [
                        'name'        => 'keunggulan',
                        'id'          => 'keunggulanEditor',
                        'value'       => old('keunggulan', $keunggulan->keunggulan ?? ''),
                        'placeholder' => 'Masukkan teks keunggulan...',
                        'required'    => true,
                    ])
                    @error('keunggulan')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </form>
    </div>

    <!-- Sidebar -->
    <div class="editor-sidebar">
        <div class="publish-box">
            <div class="publish-box-section">
                <div class="action-buttons">
                    {{-- Tombol Batal & Simpan --}}
                    <a href="{{ route('admin.keunggulan.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> <span data-translate="common.cancel">Batal</span>
                    </a>
                    <button type="submit" form="mainForm" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i>
                        <span data-translate="common.save">{{ isset($keunggulan) ? 'Update' : 'Simpan' }}</span>
                    </button>
                </div>
            </div>
           {{-- Sidebar info --}}
@if(isset($keunggulan))
    <div class="publish-box-section">
        <span class="publish-box-label">Informasi</span>
        <small class="text-muted d-block">
            <span data-translate="keunggulan.infoCreated">Dibuat</span>: {{ $keunggulan->created_at->format('d M Y') }}
        </small>
        <small class="text-muted d-block">
            <span data-translate="keunggulan.infoUpdated">Diperbarui</span>: {{ $keunggulan->updated_at->format('d M Y') }}
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
    .editor-card { background: #fff; border: 1px solid #dee2e6; border-radius: 0.5rem; overflow: hidden; }
    .editor-card-header { padding: 0.75rem 1rem; background: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; font-size: 0.9rem; }
    .editor-card-body { padding: 1rem; }
    .editor-header { border-bottom: 1px solid #dee2e6; margin-bottom: 1rem; font-size: 0.9rem; }
    .publish-box-section { padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem; margin-bottom: 0.75rem; }
    .publish-box-label { font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 0.5rem; }
    .action-buttons { display: flex; flex-direction: column; gap: 0.5rem; }

    @media (max-width: 768px) {
        .editor-container { grid-template-columns: 1fr; }
    }
    [data-theme="dark"] .editor-card { background-color: #2d2d2d; color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .editor-card-header { background-color: #3a3a3a; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .editor-card-body { background-color: #2d2d2d; }
    [data-theme="dark"] .form-control { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .publish-box-section { background-color: #3a3a3a; }
    [data-theme="dark"] .text-muted { color: #999 !important; }
    [data-theme="dark"] .img-thumbnail { background-color: #3a3a3a; border-color: #555; }
    [data-theme="dark"] .editor-header { border-bottom-color: #444; background-color: #1e1e1e; }
    [data-theme="dark"] .editor-header a.text-muted,
    [data-theme="dark"] .editor-header .text-muted { color: #aaa !important; }
    [data-theme="dark"] .editor-header a.text-muted:hover { color: #f0f0f0 !important; }
</style>
@endsection