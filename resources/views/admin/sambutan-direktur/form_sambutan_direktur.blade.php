@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-person-badge"></i>
    @if(isset($item))
        <span data-translate="common.editForm">Edit</span> <span data-translate="sambutan.title">Sambutan Direktur</span>
    @else
        <span data-translate="sambutan.addNew">Tambah Sambutan Direktur</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.sambutan-direktur.index') }}" class="text-muted text-decoration-none me-1 me-md-2">
                <i class="bi bi-chevron-left"></i> <span data-translate="sambutan.title">Sambutan Direktur</span>
            </a>
            <span class="text-muted d-none d-md-inline">/</span>
            <span class="ms-0 ms-md-2">
                @if(isset($item))
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
        <form action="{{ isset($item) ? route('admin.sambutan-direktur.update', $item->id) : route('admin.sambutan-direktur.store') }}"
            method="POST" id="mainForm" enctype="multipart/form-data" novalidate>
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <!-- Judul Sambutan -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="sambutan.judulSambutan">Judul Sambutan</div>
                <div class="editor-card-body">
                    <input type="text"
                        name="judul_sambutan"
                        class="title-input form-control @error('judul_sambutan') is-invalid @enderror"
                        placeholder="Contoh: Sambutan Direktur Utama..."
                        data-translate-placeholder="sambutan.judulPlaceholder"
                        value="{{ old('judul_sambutan', $item->content['judul_sambutan'] ?? '') }}"
                        required>
                    @error('judul_sambutan')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Foto Direktur -->
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="sambutan.fotoDirektur">Foto Direktur</div>
                <div class="editor-card-body">
                    <div class="row g-3 align-items-start">
                        <div class="col-12 col-md-9">
                            <label class="form-label">
                                <span data-translate="sambutan.uploadFoto">Upload Foto</span> <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                name="foto_direktur"
                                id="fotoInput"
                                class="form-control form-control-sm @error('foto_direktur') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                                {{ isset($item) ? '' : 'required' }}>
                            @error('foto_direktur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Informasi rasio -->
                            <div class="ratio-info mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    <span data-translate="sambutan.ratioRequired">Rasio yang diharapkan: 2:3 (410x634 pixel)</span>
                                </small>
                            </div>
                            
                            <!-- Alert rasio -->
                            <div id="ratioAlert" class="alert alert-warning alert-dismissible fade show mt-2 d-none" role="alert">
                                <i class="bi bi-exclamation-triangle"></i>
                                <span id="ratioMessage"></span>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                            </div>
                            
                            <!-- Tombol Remove Background -->
                            <div id="removeBgSection" class="mt-3">
                                <button type="button" id="removeBgBtn" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-magic"></i> <span data-translate="sambutan.removeBg">Hapus Background</span>
                                </button>
                                <div id="removeBgLoading" class="d-none mt-2">
                                    <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div>
                                    <small>Memproses...</small>
                                </div>
                                <div id="removeBgResult" class="mt-2 d-none">
                                    <small class="text-success"><i class="bi bi-check-circle"></i> Background berhasil dihapus!</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 text-center">
                            <div id="fotoPreviewWrap" class="{{ isset($item) && !empty($item->content['foto_direktur']) ? '' : 'd-none' }}">
                                <img id="fotoPreview"
                                    src="{{ isset($item) && !empty($item->content['foto_direktur']) ? asset('storage/' . $item->content['foto_direktur']) : '' }}"
                                    alt="Preview Foto"
                                    class="img-thumbnail rounded-circle "
                                    style="width:90px;height:120px;object-fit:cover;cursor:pointer;">
                            </div>
                            <div id="fotoPlaceholder" class="{{ isset($item) && !empty($item->content['foto_direktur']) ? 'd-none' : '' }} text-center text-muted">
                                <i class="bi bi-person-circle" style="font-size:4rem;"></i>
                                <small class="d-block">Preview Foto</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="foto_direktur_processed" id="fotoProcessedInput" value="">
            <input type="hidden" name="image_ratio_valid" id="imageRatioValid" value="0">

            <!-- Kata Sambutan -->
            <div class="editor-card mb-3">
                <div class="editor-card-header">
                    <span data-translate="sambutan.kataSambutan">Kata Sambutan</span> <span class="text-danger">*</span>
                </div>
                <div class="editor-card-body">
                    @error('kata_sambutan')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror
                    @include('admin.components.text-editor', [
                        'name'     => 'kata_sambutan',
                        'label'    => false,
                        'value'    => old('kata_sambutan', $item->content['kata_sambutan'] ?? ''),
                        'required' => true,
                    ])
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="editor-sidebar">
        <div class="publish-box">
            <div class="publish-box-section">
                <div class="action-buttons">
                    <a href="{{ route('admin.sambutan-direktur.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> <span data-translate="common.cancel">Batal</span>
                    </a>
                    <button type="submit" form="mainForm" id="submitBtn" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg"></i> <span data-translate="common.save">{{ isset($item) ? 'Update' : 'Simpan' }}</span>
                    </button>
                </div>
            </div>

            <!-- Foto Preview Card -->
            @if(isset($item) && !empty($item->content['foto_direktur']))
                <div class="publish-box-section text-center">
                    <span class="publish-box-label">Foto Saat Ini</span>
                    <img src="{{ asset('storage/' . $item->content['foto_direktur']) }}"
                        alt="Foto Direktur"
                        class="img-thumbnail rounded-circle mt-1 sidebar-preview preview-image"
                        style="width:80px;height:107px;object-fit:cover;cursor:pointer;">
                </div>
            @endif

            <div class="publish-box-section">
                <span class="publish-box-label" data-translate="greeting.guide">
                    Panduan
                </span>

                <small class="text-muted d-block" data-translate="greeting.guideDesc">
                    Isi ketiga field: foto, judul, dan kata sambutan agar tampil sempurna di halaman utama.
                </small>

                <hr class="my-2">

                <small class="text-muted d-block">
                    <i class="bi bi-aspect-ratio"></i>
                    <span data-translate="greeting.imageRatio">
                        Rasio gambar yang disarankan: 2:3 (Lebar: 410px, Tinggi: 634px)
                    </span>
                </small>

                @if(isset($item))
                    <hr class="my-2">

                    <small class="text-muted d-block">
                        <span data-translate="common.createdAt">Dibuat</span>:
                        {{ $item->created_at->format('d M Y') }}
                    </small>

                    <small class="text-muted d-block">
                        <span data-translate="common.updatedAt">Diperbarui</span>:
                        {{ $item->updated_at->format('d M Y') }}
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
    .title-input { font-size: 1.25rem; font-weight: 600; border: none; border-bottom: 2px solid #dee2e6; border-radius: 0; padding: 0.5rem 0; }
    .title-input:focus { box-shadow: none; border-bottom-color: #0d6efd; }
    .publish-box-section { padding: 0.75rem; background: #f8f9fa; border-radius: 0.375rem; margin-bottom: 0.75rem; }
    .publish-box-label { font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 0.5rem; }
    .action-buttons { display: flex; flex-direction: column; gap: 0.5rem; }
    .ratio-info { background: #f0f7ff; padding: 6px 10px; border-radius: 6px; border-left: 3px solid #0d6efd; }
    .preview-image:hover, .sidebar-preview:hover { opacity: 0.8; transform: scale(1.05); transition: all 0.2s; cursor: pointer; }
    @media (max-width: 768px) { .editor-container { grid-template-columns: 1fr; } }
    
    /* Dark mode */
    [data-theme="dark"] .editor-card { background-color: #2d2d2d; color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .editor-card-header { background-color: #3a3a3a; border-bottom-color: #444; }
    [data-theme="dark"] .editor-card-body { background-color: #2d2d2d; }
    [data-theme="dark"] .form-control { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .title-input { background-color: #2d2d2d; color: #f0f0f0; border-bottom-color: #555; }
    [data-theme="dark"] .publish-box-section { background-color: #3a3a3a; }
    [data-theme="dark"] .ratio-info { background: #1e3a5f; border-left-color: #4a9eff; }
    [data-theme="dark"] .alert-warning { background-color: #3a2a1a; color: #f0a0a0; border-color: #664422; }
    [data-theme="dark"] .alert-success { background-color: #1a3a1a; color: #a0f0a0; border-color: #226622; }
    [data-theme="dark"] .btn-outline-primary { color: #4a9eff; border-color: #4a9eff; }
    [data-theme="dark"] .btn-outline-primary:hover { background-color: #4a9eff; color: #1e1e1e; }
</style>

@endsection