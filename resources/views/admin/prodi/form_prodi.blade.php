@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-mortarboard"></i>
    @if(isset($prodi))
        <span data-translate="common.editForm">Edit</span> <span data-translate="prodi.addNew">Prodi</span>
    @else
        <span data-translate="prodi.addNew">Tambah Prodi</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.prodi.index') }}" class="text-muted text-decoration-none me-1 me-md-2">
                <i class="bi bi-chevron-left"></i> <span data-translate="prodi.title">Prodi</span>
            </a>
            <span class="text-muted d-none d-md-inline">/</span>
            <span class="ms-0 ms-md-2">
                @if(isset($prodi))
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
        <form action="{{ isset($prodi) ? route('admin.prodi.update', $prodi->id) : route('admin.prodi.store') }}"
            method="POST" id="mainForm" enctype="multipart/form-data" novalidate>
            @csrf
            @if(isset($prodi))
                @method('PUT')
                <input type="hidden" name="existing_type" value="{{ $prodi->type }}">
            @endif

            <!-- Type (create only) -->
            @if(!isset($prodi))
                <div class="editor-card mb-3">
                    <div class="editor-card-header" data-translate="prodi.tipeProdi">Tipe Prodi</div>
                    <div class="editor-card-body">
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeImage" value="image"
                                    {{ old('type') === 'image' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeImage">
                                    <i class="bi bi-image"></i> <span data-translate="prodi.typeImage">Image</span>
                                    <small class="text-muted d-block" data-translate="prodi.typeImageDesc">Hanya gambar dan alt text</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeCard" value="card"
                                    {{ old('type', '') === 'card' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeCard">
                                    <i class="bi bi-card-text"></i> <span data-translate="prodi.typeCard">Card</span>
                                    <small class="text-muted d-block" data-translate="prodi.typeCardDesc">Gambar + daftar deskripsi</small>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                        <div id="typeWarning" class="alert alert-warning py-2 mt-3 d-none">
                            <i class="bi bi-exclamation-triangle"></i> <span data-translate="prodi.typeWarning">Type tidak dapat diubah setelah disimpan.</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="editor-card mb-3">
                    <div class="editor-card-header" data-translate="prodi.tipeProdi">Tipe Prodi</div>
                    <div class="editor-card-body">
                        <span class="badge bg-{{ $prodi->type === 'image' ? 'success' : 'info' }} fs-6 py-2 px-3">
                            <i class="bi bi-{{ $prodi->type === 'image' ? 'image' : 'card-text' }}"></i>
                            {{ strtoupper($prodi->type) }}
                        </span>
                        <p class="text-muted small mt-2 mb-0">
                            <i class="bi bi-lock"></i> <span data-translate="prodi.typeLocked">Type tidak dapat diubah.</span>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Gambar -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="prodi.gambar">Gambar</div>
                <div class="editor-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">
                                <span data-translate="prodi.uploadGambar">Upload Gambar</span> <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                name="url_images"
                                id="urlImagesInput"
                                class="form-control form-control-sm @error('url_images') is-invalid @enderror"
                                accept="image/*"
                                {{ isset($prodi) ? '' : 'required' }}>
                            @error('url_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" data-translate="prodi.imageFormat">Format: JPG, PNG. Maksimal 2MB.</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" data-translate="prodi.altText">Alt Text</label>
                            <input type="text"
                                name="alt"
                                class="form-control form-control-sm"
                                placeholder="Deskripsi gambar"
                                data-translate-placeholder="prodi.altPlaceholder"
                                value="{{ old('alt', $prodi->content['alt'] ?? '') }}">
                        </div>
                        <div class="col-12">
                            <div id="imgPreviewWrap" class="{{ isset($prodi) && !empty($prodi->content['url_images']) ? '' : 'd-none' }}">
                                <p class="small text-muted mb-1" data-translate="prodi.preview">Preview:</p>
                                <img id="imgPreview"
                                    src="{{ isset($prodi) && !empty($prodi->content['url_images']) ? asset('storage/' . $prodi->content['url_images']) : '' }}"
                                    alt="Preview"
                                    class="img-thumbnail preview-image"
                                    style="max-height:200px;object-fit:cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi (card type only) -->
            @php
                $currentType = $prodi->type ?? old('type');
                $existingTitle = old('title', $prodi->content['title'] ?? '');
                $existingDeskripsi = old('deskripsi', $prodi->content['deskripsi'] ?? []);
            @endphp

            <div class="editor-card mb-3" id="deskripsiSection"
                style="{{ $currentType !== 'card' && $currentType !== null ? 'display:none;' : ($currentType === null ? 'display:none;' : '') }}">
                <div class="editor-card-header d-flex justify-content-between align-items-center">
                    <span>
                        <span data-translate="prodi.deskripsi">Judul &amp; Deskripsi</span> <span class="text-danger">*</span>
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addDeskripsi">
                        <i class="bi bi-plus-circle"></i> <span data-translate="prodi.tambahBaris">Tambah Baris</span>
                    </button>
                </div>
                <div class="editor-card-body">
                    @error('title')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror
                    @error('deskripsi')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <span data-translate="prodi.title">Judul</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            name="title"
                            id="title"
                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                            placeholder="Masukkan judul program studi"
                            data-translate-placeholder="prodi.titlePlaceholder"
                            value="{{ $existingTitle }}"
                            required>
                    </div>

                    <div id="deskripsiContainer">
                        @if(count($existingDeskripsi) > 0)
                            @foreach($existingDeskripsi as $di => $d)
                                <div class="deskripsi-row mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-semibold">Deskripsi {{ $di + 1 }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-deskripsi flex-shrink-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @include('admin.components.text-editor', [
                                        'name' => "deskripsi[$di]",
                                        'label' => '',
                                        'value' => $d,
                                        'required' => true,
                                    ])
                                </div>
                            @endforeach
                        @else
                            <div id="emptyDeskripsi" class="alert alert-info">
                                <i class="bi bi-info-circle"></i> <span data-translate="prodi.emptyDeskripsi">Belum ada deskripsi. Klik "Tambah Baris".</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="editor-sidebar">
        <div class="publish-box">
            <div class="publish-box-section">
                <div class="action-buttons">
                    <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> <span data-translate="common.cancel">Batal</span>
                    </a>
                    <button type="submit" form="mainForm" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i> <span data-translate="common.save">{{ isset($prodi) ? 'Update' : 'Simpan' }}</span>
                    </button>
                </div>
            </div>
            <div class="publish-box-section">
                <span class="publish-box-label" data-translate="prodi.panduan">Panduan</span>
                <small class="text-muted d-block" data-translate="prodi.panduanImage">Type Image: Hanya tampilkan gambar.</small>
                <small class="text-muted d-block mt-1" data-translate="prodi.panduanCard">Type Card: Gambar dengan daftar item deskripsi di bawahnya.</small>
                @if(isset($prodi))
                    <hr class="my-2">
                    <small class="text-muted d-block">
                        <span data-translate="prodi.infoCreated">Dibuat</span>: {{ $prodi->created_at->format('d M Y') }}
                    </small>
                    <small class="text-muted d-block">
                        <span data-translate="prodi.infoUpdated">Diperbarui</span>: {{ $prodi->updated_at->format('d M Y') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>


<style>
    .editor-container { display: grid; grid-template-columns: 1fr 280px; gap: 1.5rem; align-items: start; padding-bottom: 2rem; }
    .editor-card { background: #fff; border: 1px solid #dee2e6; border-radius: 0.5rem; overflow: hidden; }
    .editor-card-header { padding: 0.75rem 1rem; background: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; font-size: 0.9rem; }
    .editor-card-body { padding: 1rem; }
    .editor-header { border-bottom: 1px solid #dee2e6; margin-bottom: 1rem; font-size: 0.9rem; }
    .publish-box-section { padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem; margin-bottom: 0.75rem; }
    .publish-box-label { font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 0.5rem; }
    .action-buttons { display: flex; flex-direction: column; gap: 0.5rem; }
    @media (max-width: 768px) { .editor-container { grid-template-columns: 1fr; } }
    [data-theme="dark"] .editor-card { background-color: #2d2d2d; color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .editor-card-header { background-color: #3a3a3a; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .editor-card-body { background-color: #2d2d2d; }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-check-input { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .publish-box-section { background-color: #3a3a3a; }
    [data-theme="dark"] .text-muted { color: #999 !important; }
    [data-theme="dark"] .img-thumbnail { background-color: #3a3a3a; border-color: #555; }
    [data-theme="dark"] .alert-info { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .alert-warning { background-color: #3a2a00; color: #ffc107; border-color: #665500; }
    [data-theme="dark"] .editor-header { border-bottom-color: #444; background-color: #1e1e1e; }
    [data-theme="dark"] .editor-header a.text-muted,
    [data-theme="dark"] .editor-header .text-muted { color: #aaa !important; }
    [data-theme="dark"] .editor-header a.text-muted:hover { color: #f0f0f0 !important; }
</style>

<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>

@endsection