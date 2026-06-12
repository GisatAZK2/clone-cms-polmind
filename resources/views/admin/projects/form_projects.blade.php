@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-folder2-open"></i>
    @if(isset($project))
        <span data-translate="common.editForm">Edit</span> <span data-translate="projects.judulProject">Project</span>
    @else
        <span data-translate="projects.addNew">Tambah Project</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.projects.index') }}" class="text-muted text-decoration-none me-1 me-md-2">
                <i class="bi bi-chevron-left"></i> <span data-translate="projects.title">Projects</span>
            </a>
            <span class="text-muted d-none d-md-inline">/</span>
            <span class="ms-0 ms-md-2">
                @if(isset($project))
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
        <form action="{{ isset($project) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
            method="POST" id="mainForm" enctype="multipart/form-data" novalidate>
            @csrf
            @if(isset($project))
                @method('PUT')
            @endif

            <!-- Gambar -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="projects.gambarProject">Gambar Project</div>
                <div class="editor-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">
                                <span data-translate="projects.uploadGambar">Upload Gambar</span> <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                name="url_images"
                                id="urlImagesInput"
                                class="form-control form-control-sm @error('url_images') is-invalid @enderror"
                                accept="image/*"
                                {{ isset($project) ? '' : 'required' }}>
                            @error('url_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" data-translate="projects.imageFormat">Format: JPG, PNG. Maksimal 2MB.</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" data-translate="projects.altText">Alt Text</label>
                            <input type="text"
                                name="alt"
                                class="form-control form-control-sm"
                                placeholder="Deskripsi gambar"
                                data-translate-placeholder="projects.altPlaceholder"
                                value="{{ old('alt', $project->alt ?? '') }}">
                        </div>
                        <div class="col-12">
                            <div id="imgPreviewWrap" class="{{ isset($project) && !empty($project->url_images) ? '' : 'd-none' }}">
                                <p class="small text-muted mb-1" data-translate="projects.preview">Preview:</p>

                                <img id="imgPreview"
                                    src="{{ isset($project) && !empty($project->url_images) ? asset('storage/' . $project->url_images) : '' }}"
                                    alt="Preview"
                                    class="img-thumbnail preview-image"
                                    style="max-height:200px;object-fit:cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="projects.judulProject">Judul Project</div>
                <div class="editor-card-body">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Masukkan judul project"
                        data-translate-placeholder="projects.titlePlaceholder"
                        value="{{ old('title', $project->title ?? '') }}"
                        required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="projects.deskripsiProject">Deskripsi Project</div>
                <div class="editor-card-body">
                    @error('deskripsi')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror
                    @include('admin.components.text-editor', [
                        'name'     => 'deskripsi',
                        'label'    => false,
                        'value'    => old('deskripsi', $project->deskripsi ?? ''),
                        'required' => true,
                    ])
                    <small class="text-muted" data-translate="projects.deskripsiHint">Masukkan deskripsi project dengan detail lengkap.</small>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="editor-sidebar">
        <div class="publish-box">
            <div class="publish-box-section">
                <div class="action-buttons">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> <span data-translate="common.cancel">Batal</span>
                    </a>
                    <button type="submit" form="mainForm" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i> <span data-translate="common.save">{{ isset($project) ? 'Update' : 'Simpan' }}</span>
                    </button>
                </div>
            </div>
            @if(isset($project))
                <div class="publish-box-section">
                    <span class="publish-box-label" data-translate="projects.infoLabel">Informasi</span>
                    <small class="text-muted d-block">
                        <span data-translate="projects.infoCreated">Dibuat</span>: {{ $project->created_at->format('d M Y') }}
                    </small>
                    <small class="text-muted d-block">
                        <span data-translate="projects.infoUpdated">Diperbarui</span>: {{ $project->updated_at->format('d M Y') }}
                    </small>
                </div>
            @endif
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