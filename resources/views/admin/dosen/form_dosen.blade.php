@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-people"></i>
    @if(isset($dosen))
        <span data-translate="dosen.editForm">Edit Dosen</span>
    @else
        <span data-translate="dosen.addNew">Tambah Dosen Baru</span>
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
                        @if(isset($dosen))
                            <i class="bi bi-pencil-square me-1"></i>
                            <span data-translate="dosen.editForm">Edit Dosen</span>
                        @else
                            <i class="bi bi-person-plus me-1"></i>
                            <span data-translate="dosen.addNew">Tambah Dosen Baru</span>
                        @endif
                    </h5>
                </div>

                <form id="multiDosenForm"
                      action="{{ isset($dosen) ? route('admin.dosen.update', $dosen->id) : route('admin.dosen.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($dosen))
                        @method('PUT')
                    @endif

                    <div class="card-body p-3 p-md-4" id="formsContainer">
                        <!-- FORM PERTAMA -->
                        <div class="dosen-form mb-5" data-form-index="0">
                            <h6 class="form-single-header mb-3">
                                <i class="bi bi-person-badge"></i>
                                <span data-translate="dosen.itemLabel">Dosen</span> #1
                            </h6>

                            <input type="hidden" name="dosen[0][id]" value="{{ $dosen->id ?? '' }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <span data-translate="dosen.name">Nama Dosen</span> <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="dosen[0][name]" id="nameInput_0" class="form-control"
                                       data-translate-placeholder="dosen.namePlaceholder"
                                       placeholder="Masukkan nama dosen"
                                       value="{{ old('dosen.0.name', $dosen->name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <span data-translate="dosen.type">Tipe Dosen</span> <span class="text-danger">*</span>
                                </label>
                                <select name="dosen[0][type]" class="form-select" required>
                                    <option value="" disabled data-translate="dosen.selectType">-- Pilih Tipe Dosen --</option>
                                    <option value="Dosen_Internal" data-translate="dosen.typeInternal" {{ old('dosen.0.type', $dosen->type ?? '') === 'Dosen_Internal' ? 'selected' : '' }}>Dosen Internal</option>
                                    <option value="Expert_industri" data-translate="dosen.typeExpert" {{ old('dosen.0.type', $dosen->type ?? '') === 'Expert_industri' ? 'selected' : '' }}>Expert Industri</option>
                                    <option value="Tenaga_Pendidik" data-translate="dosen.typeEducator" {{ old('dosen.0.type', $dosen->type ?? '') === 'Tenaga_Pendidik' ? 'selected' : '' }}>Tenaga Pendidik</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <span data-translate="dosen.altText">Alt Text Gambar</span> <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="dosen[0][alt]" class="form-control"
                                       data-translate-placeholder="dosen.altPlaceholder"
                                       placeholder="Deskripsi gambar"
                                       value="{{ old('dosen.0.alt', $dosen->alt ?? '') }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <span data-translate="dosen.photo">Foto Dosen</span> @if(!isset($dosen))<span class="text-danger">*</span>@endif
                                </label>
                                <input type="file" name="dosen[0][url_image]" class="form-control"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       {{ !isset($dosen) ? 'required' : '' }}>
                                <small class="text-muted" data-translate="dosen.imageHelp">JPEG, PNG, JPG, GIF • Maks. 2MB</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" data-translate="dosen.description">Deskripsi / Biodata</label>
                                @include('admin.components.text-editor', [
                                    'name' => 'dosen[0][deskripsi]',
                                    'value' => old('dosen.0.deskripsi', $dosen->deskripsi ?? ''),
                                    'required' => false,
                                    'label' => false
                                ])
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <button type="button" id="btnAddForm" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> <span data-translate="common.add">Tambah</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary" data-translate="common.back">Kembali</a>
                <button type="submit" form="multiDosenForm" class="btn btn-primary" data-translate="common.saveAll">Simpan Semua</button>
            </div>
        </div>

        <!-- LIVE PREVIEW -->
        <div class="col-12 col-lg-4">
            <h6 class="mb-3 text-muted">
                <i class="bi bi-eye"></i> <span data-translate="dosen.livePreview">Live Preview</span>
            </h6>
            <div
                id="previewsContainer"
                class="d-flex flex-column gap-4"
                data-existing-name="{{ isset($dosen) ? e($dosen->name) : '' }}"
                data-existing-image="{{ isset($dosen) && $dosen->url_image ? Storage::url($dosen->url_image) : '' }}">
            </div>
        </div>
    </div>
</div>

<!-- Templates -->
<template id="formTemplate">
    <div class="dosen-form mb-5" data-form-index="__INDEX__">
        <h6 class="form-single-header mb-3">
            <i class="bi bi-person-badge"></i>
            <span data-translate="dosen.itemLabel">Dosen</span> #<span class="form-number">__INDEX__</span>
        </h6>
        <input type="hidden" name="dosen[__INDEX__][id]" value="">
        <div class="mb-3">
            <label class="form-label fw-semibold">
                <span data-translate="dosen.name">Nama Dosen</span> <span class="text-danger">*</span>
            </label>
            <input type="text" name="dosen[__INDEX__][name]" class="form-control"
                   data-translate-placeholder="dosen.namePlaceholder"
                   placeholder="Masukkan nama dosen"
                   required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">
                <span data-translate="dosen.type">Tipe Dosen</span> <span class="text-danger">*</span>
            </label>
            <select name="dosen[__INDEX__][type]" class="form-select" required>
                <option value="" disabled selected data-translate="dosen.selectType">-- Pilih Tipe Dosen --</option>
                <option value="Dosen_Internal" data-translate="dosen.typeInternal">Dosen Internal</option>
                <option value="Expert_industri" data-translate="dosen.typeExpert">Expert Industri</option>
                <option value="Tenaga_Pendidik" data-translate="dosen.typeEducator">Tenaga Pendidik</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">
                <span data-translate="dosen.altText">Alt Text Gambar</span> <span class="text-danger">*</span>
            </label>
            <input type="text" name="dosen[__INDEX__][alt]" class="form-control"
                   data-translate-placeholder="dosen.altPlaceholder"
                   placeholder="Deskripsi gambar"
                   required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">
                <span data-translate="dosen.photo">Foto Dosen</span> <span class="text-danger">*</span>
            </label>
            <input type="file" name="dosen[__INDEX__][url_image]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" required>
            <small class="text-muted" data-translate="dosen.imageHelp">JPEG, PNG, JPG, GIF • Maks. 2MB</small>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" data-translate="dosen.description">Deskripsi / Biodata</label>
            @include('admin.components.text-editor', [
                'name' => 'dosen[__INDEX__][deskripsi]',
                'value' => '',
                'required' => false,
                'label' => false
            ])
        </div>
    </div>
</template>

<template id="previewTemplate">
    <div class="preview-item" data-preview-index="__INDEX__">
        <div class="card shadow-sm">
            <div class="card-body text-center p-3">
                <div class="preview-photo-wrapper mx-auto mb-3">
                    <img id="previewPhoto___INDEX__" src="#" class="preview-photo d-none">
                    <div id="previewPlaceholder___INDEX__" class="preview-photo-placeholder">
                        <i class="bi bi-person" style="font-size: 2.5rem; color: #adb5bd;"></i>
                    </div>
                </div>
                <p class="preview-name mb-0" id="previewName___INDEX__" data-translate="dosen.previewName">Nama Dosen</p>
            </div>
        </div>
    </div>
</template>

<style>
.form-single-header { font-size: 1.1rem; font-weight: 600; color: #1a2f6b; }
.tox-tinymce { border-radius: 0.375rem !important; }

.preview-photo-wrapper {
    width: 120px; height: 120px;
    border-radius: 50%; overflow: hidden;
    border: 3px solid #e2e8f0; background: #f8f9fa;
}
.preview-photo { width: 100%; height: 100%; object-fit: cover; }
.preview-photo-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: #f1f3f5;
}
.preview-name { font-size: 1rem; font-weight: 700; color: #1a2f6b; }
</style>
@endsection