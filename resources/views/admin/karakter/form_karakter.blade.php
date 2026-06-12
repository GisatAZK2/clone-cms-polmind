@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-star"></i>
    @if(isset($karakter))
        <span data-translate="common.editForm">Edit Karakter</span>
    @else
        <span data-translate="karakter.addNew">Tambah Karakter Baru</span>
    @endif
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-4">

        <!-- FORM -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        @if(isset($karakter))
                            <i class="bi bi-pencil-square me-1"></i> <span data-translate="common.editForm">Edit Karakter</span>
                        @else
                            <i class="bi bi-plus-circle me-1"></i> <span data-translate="karakter.addNew">Tambah Karakter Baru</span>
                        @endif
                    </h5>
                </div>

                <form id="karakterForm"
                      action="{{ isset($karakter) ? route('admin.karakter.update', $karakter->id) : route('admin.karakter.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($karakter))
                        @method('PUT')
                    @endif

                    <div class="card-body p-3 p-md-4">

                        <!-- Nama Konten -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" data-translate="karakter.namaKonten">
                                Nama Konten <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_konten" 
                                   class="form-control @error('nama_konten') is-invalid @enderror"
                                   value="{{ old('nama_konten', $karakter->nama_konten ?? '') }}" 
                                   data-translate-placeholder="karakter.namaKontenPlaceholder"
                                   placeholder="Masukkan nama konten karakter"
                                   required>
                            @error('nama_konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alt Text -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" data-translate="karakter.altText">
                                Alt Text Gambar
                            </label>
                            <input type="text" 
                                   name="alt" 
                                   class="form-control @error('alt') is-invalid @enderror"
                                   value="{{ old('alt', $karakter->alt ?? '') }}"
                                   data-translate-placeholder="karakter.altTextPlaceholder"
                                   placeholder="Deskripsi alternatif untuk gambar">
                            @error('alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <!-- Gambar -->
<div class="mb-4">
    <label class="form-label fw-semibold" data-translate="karakter.image">
        Gambar @if(!isset($karakter))<span class="text-danger">*</span>@endif
    </label>

    <div id="imagePreview"
         class="mb-3 {{ isset($karakter) && $karakter->url_image ? '' : 'd-none' }}"
         style="{{ isset($karakter) && $karakter->url_image ? 'display: block;' : 'display: none;' }}">

        <div class="border rounded p-2" style="max-width: 200px;">
            <img id="previewImg"
                 src="{{ isset($karakter) && $karakter->url_image ? asset('storage/' . $karakter->url_image) : '' }}"
                 alt="{{ old('alt', $karakter->alt ?? 'Preview gambar') }}"
                 class="img-fluid rounded preview-image {{ isset($karakter) && $karakter->url_image ? '' : 'd-none' }}">
        </div>

        <small class="text-muted d-block mt-2">
            <i class="bi bi-info-circle"></i>
            <span id="previewLabel"
                  data-new-text="Preview Gambar Baru"
                  data-translate="{{ isset($karakter) && $karakter->url_image ? 'karakter.currentImage' : 'karakter.newImagePreview' }}">
                {{ isset($karakter) && $karakter->url_image ? 'Gambar saat ini' : 'Preview Gambar Baru' }}
            </span>
        </small>
    </div>

    <input type="file"
           id="url_image"
           name="url_image"
           class="form-control @error('url_image') is-invalid @enderror"
           accept="image/jpeg,image/png,image/jpg,image/gif"
           {{ !isset($karakter) ? 'required' : '' }}>

    <small class="text-muted d-block mt-1">
        <i class="bi bi-info-circle"></i>
        <span data-translate="karakter.imageHelp">JPEG, PNG, JPG, GIF • Maks. 2MB</span>
    </small>

    @error('url_image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" data-translate="karakter.description">
                                Deskripsi
                            </label>
                            @include('admin.components.text-editor', [
                                'name' => 'deskripsi',
                                'value' => old('deskripsi', $karakter->deskripsi ?? ''),
                                'required' => false,
                                'label' => false
                            ])
                            @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.karakter.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                <span data-translate="common.back">Kembali</span>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                <span data-translate="common.save">Simpan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- HELP SIDEBAR -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm bg-light-subtle">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-question-circle me-1"></i>
                        <span data-translate="common.help">Bantuan</span>
                    </h6>
                </div>
                <div class="card-body small">
                    <p class="mb-2" data-translate="karakter.helpNamaKonten">
                        <strong>Nama Konten:</strong> Berisi nama atau judul karakter yang akan ditampilkan.
                    </p>
                    <p class="mb-2" data-translate="karakter.helpAlt">
                        <strong>Alt Text:</strong> Teks alternatif untuk gambar (penting untuk SEO dan aksesibilitas).
                    </p>
                    <p class="mb-2" data-translate="karakter.helpImage">
                        <strong>Gambar:</strong> Upload gambar dalam format JPEG, PNG, JPG, atau GIF dengan ukuran maksimal 2MB.
                    </p>
                    <p data-translate="karakter.helpDeskripsi">
                        <strong>Deskripsi:</strong> Deskripsi detail karakter yang dapat memuat teks format (bold, italic, dll).
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
