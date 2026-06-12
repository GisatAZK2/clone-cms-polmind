@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-megaphone"></i>
    <span data-translate="{{ isset($headline) ? 'common.edit' : 'common.new' }}">
        {{ isset($headline) ? 'Edit Headline' : 'Tambah Headline Baru' }}
    </span>
@endsection

@section('content')
<div class="form-page-wrapper">

    {{-- Page Header --}}
    <div class="form-page-header">
        <div class="form-page-header-left">
            <a href="{{ route('admin.headline.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="form-page-title">
                    <span data-translate="{{ isset($headline) ? 'common.edit' : 'common.new' }}">
                        {{ isset($headline) ? 'Edit Headline' : 'Tambah Headline Baru' }}
                    </span>
                </h1>
                <p class="form-page-subtitle"
                   data-translate="{{ isset($headline) ? 'headline.subtitleEdit' : 'headline.subtitleAdd' }}">
                    {{ isset($headline) ? 'Perbarui informasi headline yang sudah ada' : 'Buat headline baru untuk halaman beranda' }}
                </p>
            </div>
        </div>
        <div class="form-page-header-right">
            <a href="{{ route('admin.headline.index') }}" class="btn-action btn-action--ghost">
                <i class="bi bi-x-lg"></i>
                <span data-translate="common.cancel">Batal</span>
            </a>
            <button type="submit" form="headlineForm" class="btn-action btn-action--primary">
                <i class="bi bi-check2"></i>
                <span data-translate="{{ isset($headline) ? 'common.save' : 'common.published' }}">
                    {{ isset($headline) ? 'Simpan Perubahan' : 'Publikasikan' }}
                </span>
            </button>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="form-layout">

        {{-- LEFT COLUMN: Main Form --}}
        <div class="form-layout-main">

            {{-- Card: Konten Headline --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon--blue">
                        <i class="bi bi-type-h1"></i>
                    </div>
                    <div>
                        <h3 class="form-card-title" data-translate="headline.contentTitle">Konten Headline</h3>
                        <p class="form-card-desc" data-translate="headline.contentDesc">Judul utama yang akan ditampilkan di halaman beranda</p>
                    </div>
                </div>
                <div class="form-card-body">
                    <form
                        action="{{ isset($headline) ? route('admin.headline.update', $headline->id) : route('admin.headline.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                        id="headlineForm"
                    >
                        @csrf
                        @if(isset($headline))
                            @method('PUT')
                        @endif

                        <div class="field-group">
                            <label class="field-label">
                                <span data-translate="headline.titleLabel">Judul Headline</span>
                                <span class="field-required">*</span>
                            </label>
                            <div class="field-hint" data-translate="headline.titleHint">Gunakan teks yang menarik dan deskriptif</div>
                            @include('admin.components.text-editor', [
                                'name' => 'title',
                                'label' => '',
                                'value' => old('title', $headline->title ?? ''),
                                'required' => true
                            ])
                            @error('title')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card: Media Gambar --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon--green">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h3 class="form-card-title" data-translate="headline.mediaTitle">Media Gambar</h3>
                        <p class="form-card-desc" data-translate="headline.mediaDesc">Upload gambar untuk banner headline</p>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="field-group">
                        <label class="field-label">
                            <span data-translate="headline.imageUploadLabel">Upload Gambar</span>
                            <span class="field-required">*</span>
                        </label>

                        {{-- Drop Zone --}}
                        <div class="upload-zone" id="uploadZone">
                            <div class="upload-zone-placeholder" id="uploadPlaceholder"
                                 style="{{ isset($headline) && $headline->url_image ? 'display:none' : '' }}">
                                <div class="upload-zone-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <p class="upload-zone-text" data-translate="headline.imageDragHint">Klik atau seret gambar ke sini</p>
                                <p class="upload-zone-hint" data-translate="headline.imageFormatHint">JPEG, PNG, JPG, GIF · Maks. 2MB</p>
                            </div>
                            <img
                                id="uploadPreviewImg"
                                src="{{ isset($headline) && $headline->url_image ? asset($headline->url_image) : '' }}"
                                alt="Preview"
                                class="upload-preview-img"
                                style="{{ isset($headline) && $headline->url_image ? 'display:block' : 'display:none' }}"
                            >
                            <div class="upload-zone-overlay" id="uploadOverlay"
                                 style="{{ isset($headline) && $headline->url_image ? 'display:flex' : 'display:none' }}">
                                <i class="bi bi-arrow-repeat"></i>
                                <span data-translate="headline.replaceImage">Ganti Gambar</span>
                            </div>
                        </div>

                        <input
                            type="file"
                            class="d-none @error('url_image') is-invalid @enderror"
                            id="url_image"
                            name="url_image"
                            form="headlineForm"
                            accept="image/*"
                            {{ !isset($headline) ? 'required' : '' }}
                        >
                        @error('url_image')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="alt" data-translate="headline.imageAltSeo">Teks Alt (SEO)</label>
                        <div class="field-input-wrap">
                            <i class="bi bi-tag field-input-icon"></i>
                            <input
                                type="text"
                                class="field-input @error('alt') is-invalid @enderror"
                                id="alt"
                                name="alt"
                                form="headlineForm"
                                value="{{ old('alt', $headline->alt ?? '') }}"
                                placeholder="Deskripsi singkat gambar untuk SEO"
                                data-translate-placeholder="headline.altPlaceholder"
                            >
                        </div>
                        @error('alt')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                        <div class="field-hint" data-translate="headline.altHint">Teks alt membantu mesin pencari memahami konten gambar</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Sidebar (Status + Preview) --}}
        <div class="form-layout-sidebar">

            {{-- Card: Publikasi --}}
            <div class="form-card form-card--sticky">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon--purple">
                        <i class="bi bi-send"></i>
                    </div>
                    <div>
                        <h3 class="form-card-title" data-translate="headline.publishTitle">Publikasi</h3>
                        <p class="form-card-desc" data-translate="headline.publishDesc">Atur status tampilan</p>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="field-group mb-0">
                        <label class="field-label" for="status">
                            <span data-translate="headline.statusLabel">Status</span>
                            <span class="field-required">*</span>
                        </label>
                        @php
                            $selectedStatus = old('status', $headline->status ?? 'active');
                        @endphp

                        <div class="status-toggle-group">
                            <label class="status-toggle-option {{ $selectedStatus == 'active' ? 'active' : '' }}">
                                <<input 
                                    type="radio" 
                                    name="status" 
                                    value="active"
                                    form="headlineForm"
                                    {{ $selectedStatus == 'active' ? 'checked' : '' }}
                                >
                                <span class="status-toggle-dot status-toggle-dot--green"></span>
                                <span class="status-toggle-label" data-translate="common.active">Aktif</span>
                                <i class="bi bi-check2 status-toggle-check"></i>
                            </label>

                            <label class="status-toggle-option {{ $selectedStatus == 'inactive' ? 'active' : '' }}">
                                <input 
                                    type="radio" 
                                    name="status" 
                                    value="inactive"
                                    form="headlineForm"
                                    {{ $selectedStatus == 'inactive' ? 'checked' : '' }}
                                >
                                <span class="status-toggle-dot status-toggle-dot--gray"></span>
                                <span class="status-toggle-label" data-translate="common.inactive">Nonaktif</span>
                                <i class="bi bi-check2 status-toggle-check"></i>
                            </label>
                        </div>
                        @error('status')
                            <div class="field-error mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="publish-actions">
                        <a href="{{ route('admin.headline.index') }}" class="btn-action btn-action--ghost w-100">
                            <i class="bi bi-x-lg"></i>
                            <span data-translate="common.cancel">Batal</span>
                        </a>
                        <button type="submit" form="headlineForm" class="btn-action btn-action--primary w-100">
                            <i class="bi bi-check2"></i>
                            <span data-translate="{{ isset($headline) ? 'common.save' : 'common.published' }}">
                                {{ isset($headline) ? 'Simpan Perubahan' : 'Publikasikan' }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Card: Preview --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon form-card-icon--orange">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <h3 class="form-card-title" data-translate="headline.previewTitle">Preview</h3>
                        <p class="form-card-desc" data-translate="headline.previewDesc">Tampilan di halaman beranda</p>
                    </div>
                </div>
                <div class="form-card-body p-0">
                    <div class="preview-banner">
                        <div class="preview-banner-img-wrap" id="previewBannerWrap">
                            <div class="preview-banner-placeholder" id="previewBannerPlaceholder"
                                 style="{{ isset($headline) && $headline->url_image ? 'display:none' : '' }}">
                                <i class="bi bi-image"></i>
                                <span data-translate="headline.noImageYet">Belum ada gambar</span>
                            </div>
                            <img
                                id="previewImage"
                                src="{{ isset($headline) && $headline->url_image ? asset($headline->url_image) : '' }}"
                                alt="Preview"
                                class="preview-banner-img"
                                style="{{ isset($headline) && $headline->url_image ? 'display:block' : 'display:none' }}"
                            >
                            <div class="preview-banner-gradient"></div>
                        </div>
                        <div class="preview-banner-content">
                            <div class="preview-banner-badge" data-translate="headline.previewBadge">HEADLINE</div>
                            <div id="previewTitle" class="preview-banner-title">
                                {!! $headline->title ?? '<span class="preview-placeholder-text" data-translate="headline.previewEmpty">Judul headline akan muncul di sini</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection