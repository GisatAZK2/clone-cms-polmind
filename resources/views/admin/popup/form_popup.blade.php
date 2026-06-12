@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-window"></i> 
    @if(isset($popup))
        <span data-translate="common.editForm">Edit Popup</span>
    @else
        <span data-translate="popup.addNew">Tambah Popup Baru</span>
    @endif
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        @if(isset($popup))
                            <span data-translate="common.editForm">Edit Popup</span>
                        @else
                            <span data-translate="popup.addNew">Tambah Popup Baru</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form 
                        action="{{ isset($popup) ? route('admin.popup.update', $popup->id) : route('admin.popup.store') }}" 
                        method="POST" 
                        enctype="multipart/form-data"
                        novalidate
                    >
                        @csrf
                        @if(isset($popup))
                            @method('PUT')
                        @endif
                        
                        <div class="mb-3">
                            <label for="url_image" class="form-label" data-translate="popup.image">Gambar {{ isset($popup) ? '(Opsional)' : '(Wajib)' }} <span class="text-danger">*</span></label>
                            <input 
                                type="file" 
                                class="form-control form-control-sm @error('url_image') is-invalid @enderror" 
                                id="url_image" 
                                name="url_image" 
                                accept="image/jpeg,image/png,image/webp"
                                {{ isset($popup) ? '' : 'required' }}
                                data-preview-target="previewImg"
                            >
                            <small class="text-muted" data-translate="popup.imageFormat">Format: JPG, PNG, WEBP. Max 2MB</small>
                            @error('url_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="alt" class="form-label" data-translate="popup.altText">Alt Text <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control form-control-sm @error('alt') is-invalid @enderror" 
                                id="alt" 
                                name="alt" 
                                value="{{ old('alt', $popup->alt ?? '') }}"
                                placeholder="Deskripsi gambar"
                                data-translate-placeholder="popup.altPlaceholder"
                                required
                            >
                            @error('alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            @include('admin.components.text-editor', [
                                'name' => 'content',
                                'label' => 'Konten Popup',
                                'value' => old('content', $popup->content ?? ''),
                                'required' => true
                            ])
                            @error('content')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input 
                                    class="form-check-input @error('is_active') is-invalid @enderror" 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active" 
                                    value="1"
                                    {{ old('is_active', $popup->is_active ?? false) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="is_active" data-translate="popup.activate">
                                    Aktifkan Popup
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @php
                            $hasCurrentImage = isset($popup) && !empty($popup->url_image);
                        @endphp

                        <div id="imagePreview" class="mb-3" style="{{ $hasCurrentImage ? '' : 'display: none;' }}">
                            <div class="card" style="max-width: 300px;">
                                <img
                                    id="previewImg"
                                    src="{{ $hasCurrentImage ? asset('storage/' . $popup->url_image) : '' }}"
                                    class="card-img-top preview-image"
                                    alt="{{ old('alt', $popup->alt ?? 'Preview') }}"
                                >

                                <div class="card-body">
                                   <small 
                                        id="previewLabel"
                                        class="text-muted"
                                        data-current-key="common.currentImage"
                                        data-new-key="common.previewImage"
                                        data-translate="{{ $hasCurrentImage ? 'popup.currentImage' : 'popup.previewImage' }}"
                                    >
                                        {{ $hasCurrentImage ? 'Gambar Saat Ini' : 'Preview Gambar Baru' }}
                                    </small>
                                </div>
                            </div>
</div>
                        
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('admin.popup.index') }}" class="btn btn-secondary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-arrow-left"></i> <span data-translate="common.back">Batal</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-check-circle"></i> <span data-translate="common.save">{{ isset($popup) ? 'Update' : 'Simpan' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .form-control-sm,
        .form-label {
            font-size: 0.85rem !important;
        }

        .btn-sm {
            padding: 0.35rem 0.5rem !important;
            font-size: 0.75rem !important;
        }
    }

    /* Dark theme */
    [data-theme="dark"] .card {
        background-color: #2d2d2d;
        color: #f0f0f0;
        border-color: #444;
    }

    [data-theme="dark"] .card-header {
        background-color: #3a3a3a !important;
        border-bottom-color: #444 !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
        background-color: #3a3a3a;
        color: #f0f0f0;
        border-color: #555;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        background-color: #4a4a4a;
        border-color: #666;
        color: #f0f0f0;
    }

    [data-theme="dark"] .form-label {
        color: #f0f0f0;
    }

    [data-theme="dark"] .text-muted {
        color: #999 !important;
    }

    [data-theme="dark"] .form-check-label {
        color: #f0f0f0;
    }
</style>

@endsection