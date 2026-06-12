@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-newspaper"></i> <span data-translate="{{ isset($news) ? 'news.editForm' : 'news.addForm' }}">{{ isset($news) ? 'Edit Berita' : 'Tambah Berita Baru' }}</span>
@endsection

@section('content')

    <div class="editor-header px-2 px-md-3 py-2 py-md-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.news.index') }}" class="text-muted text-decoration-none me-1 me-md-2">
                    <i class="bi bi-chevron-left"></i> <span data-translate="common.back">Berita</span>
                </a>
                <span class="text-muted d-none d-md-inline">/</span>
                <span class="ms-0 ms-md-2" data-translate="{{ isset($news) ? 'common.edit' : 'common.new' }}">{{ isset($news) ? 'Edit' : 'Baru' }}</span>
            </div>
        </div>
    </div>

    <div class="editor-container px-2 px-md-3">
        <!-- Main Editor Area -->
        <div class="editor-main">
            <form action="{{ isset($news) ? route('admin.news.update', $news->id) : route('admin.news.store') }}"
                method="POST" enctype="multipart/form-data" id="newsForm" novalidate>
                @csrf
                @if(isset($news))
                    @method('PUT')
                @endif

                <!-- Title -->
                <div class="editor-card mb-3">
                    <div class="editor-card-body">
                        <input type="text" class="title-input form-control @error('title') is-invalid @enderror" id="title" name="title"
                            value="{{ old('title', $news->content['title'] ?? '') }}"
                            data-translate-placeholder="news.titleCol"
                            placeholder="Tambahkan judul berita..." required>
                        @error('title')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Content Type -->
                <div class="editor-card mb-3">
                    <div class="editor-card-body">
                        <label for="jenis_content" class="form-label" data-translate="news.contentType">
                            Jenis Konten
                        </label>

                        <select class="form-select form-select-sm @error('jenis_content') is-invalid @enderror"
                            id="jenis_content"
                            name="jenis_content"
                            required>
                            <option value="" data-translate="news.selectContentType">
                                Pilih jenis konten
                            </option>

                            <option value="Umum"
                                {{ old('jenis_content', $news->jenis_content ?? 'Umum') === 'Umum' ? 'selected' : '' }}
                                data-translate="news.categoryGeneral">
                                Umum
                            </option>

                            <option value="Prestasi"
                                {{ old('jenis_content', $news->jenis_content ?? '') === 'Prestasi' ? 'selected' : '' }}
                                data-translate="news.categoryAchievement">
                                Prestasi
                            </option>

                            <option value="Kerjasama"
                                {{ old('jenis_content', $news->jenis_content ?? '') === 'Kerjasama' ? 'selected' : '' }}
                                data-translate="news.categoryCollaboration">
                                Kerjasama
                            </option>
                        </select>
                        @error('jenis_content')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Content Blocks -->
                <div class="editor-card">
                    <div class="editor-card-header" data-translate="news.contentLabel">Konten</div>
                    <div class="editor-card-body">
                        <div id="blocksContainer">
                            @if(isset($news) && isset($news->content['blocks']) && count($news->content['blocks']) > 0)
                                @foreach($news->content['blocks'] as $blockIndex => $block)
                                    @if($block['type'] === 'text')
                                        <div class="block-item" data-block-index="{{ $blockIndex }}">
                                            <div class="block-header">
                                                <span class="block-badge bg-info" data-translate="news.blockParagraph">
                                                    Paragraf
                                                </span>

                                                <button type="button"
                                                    class="btn btn-sm btn-link text-danger delete-block"
                                                    title="Hapus"
                                                    data-translate-title="common.delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="block-content">

                                                <textarea id="editor-block-{{ $blockIndex }}"
                                                    name="blocks[{{ $blockIndex }}][content]">{!! $block['content'] !!}</textarea>
                                                <input type="hidden" name="blocks[{{ $blockIndex }}][type]" value="text">
                                            </div>
                                        </div>
                                    @elseif($block['type'] === 'image')
                                        <div class="block-item" data-block-index="{{ $blockIndex }}">
                                            <div class="block-header">
                                                <span class="block-badge bg-success" data-translate="news.blockImage">
                                                    Gambar
                                                </span>

                                                <button type="button"
                                                    class="btn btn-sm btn-link text-danger delete-block"
                                                    title="Hapus"
                                                    data-translate-title="common.delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="block-content">
                                                <div class="row g-2 g-md-3">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label" data-translate="common.image">
                                                            Gambar
                                                        </label>
                                                        <input type="file" class="form-control form-control-sm image-input"
                                                            name="blocks[{{ $blockIndex }}][image]"
                                                            accept="image/jpeg,image/png,image/webp"
                                                            onchange="previewImageBlock(event, {{ $blockIndex }})">
                                                       <small class="text-muted d-block mt-2" data-translate="news.imageFormat">
                                                            JPG, PNG, WEBP. Maks 2MB
                                                        </small>
                                                        @if($block['image'])
                                                            <div class="mt-3">
                                                                <img src="{{ asset('storage/' . $block['image']) }}" class="img-thumbnail preview-image"
                                                                    style="max-width: 100%; max-height: 200px;" alt="{{ $block['alt'] }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">
                                                            <span data-translate="common.altText">Alt Text</span>
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <textarea class="form-control form-control-sm"
                                                            name="blocks[{{ $blockIndex }}][alt]"
                                                            rows="3"
                                                            placeholder="Deskripsi gambar untuk aksesibilitas"
                                                            data-translate-placeholder="news.altPlaceholder"
                                                            required>{{ $block['alt'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="blocks[{{ $blockIndex }}][type]" value="image">
                                                @if($block['image'])
                                                    <input type="hidden" name="blocks[{{ $blockIndex }}][existing_image]"
                                                        value="{{ $block['image'] }}">
                                                @endif
                                                <div class="image-preview mt-3" style="display:none;"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div id="emptyBlocks"
                            style="display: {{ (isset($news) && isset($news->content['blocks']) && count($news->content['blocks']) > 0) ? 'none' : 'block' }};"
                            class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <span data-translate="news.startAdding">Mulai dengan menambahkan konten di bawah</span>
                        </div>

                        <div class="add-block-buttons">
                            <button type="button" class="btn-add-block btn btn-outline-primary btn-sm" id="addTextBlock">
                                <i class="bi bi-file-text"></i><br>
                                <small data-translate="news.blockParagraph">Paragraf</small>
                            </button>

                            <button type="button" class="btn-add-block btn btn-outline-primary btn-sm" id="addImageBlock">
                                <i class="bi bi-image"></i><br>
                                <small data-translate="news.blockImage">Gambar</small>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="editor-sidebar">
            <!-- Publish Box -->
            <div class="publish-box">
                <div class="publish-box-section">
                    <span class="publish-box-label" data-translate="news.status">Status</span>
                    <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status"
                        name="status" form="newsForm" required>
                        <option value="" data-translate="news.selectStatus">
                            Pilih Status
                        </option>

                        <option value="draft"
                            {{ old('status', $news->status ?? '') === 'draft' ? 'selected' : '' }}
                            data-translate="common.draft">
                            Draft
                        </option>

                        <option value="published"
                            {{ old('status', $news->status ?? '') === 'published' ? 'selected' : '' }}
                            data-translate="common.published">
                            Published
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="publish-box-section">
                    <span class="publish-box-label" data-translate="news.publishDate">Tanggal Publikasi</span>
                    @php
                        $publishedAtValue = old('published_at');

                        if (!$publishedAtValue) {
                            $publishedAtValue = isset($news) && $news->published_at
                                ? $news->published_at->timezone(config('app.timezone'))->format('Y-m-d\TH:i')
                                : now(config('app.timezone'))->format('Y-m-d\TH:i');
                        }
                    @endphp

                    <input type="datetime-local"
                        class="form-control form-control-sm @error('published_at') is-invalid @enderror"
                        id="published_at"
                        name="published_at"
                        form="newsForm"
                        value="{{ $publishedAtValue }}">
                    @error('published_at')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="publish-box-section">
                    <div class="action-buttons">
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm w-100 w-md-auto">
                            <i class="bi bi-x-lg"></i> <span data-translate="common.cancel">Batal</span>
                        </a>
                        <button type="submit" form="newsForm" class="btn btn-primary btn-sm w-100 w-md-auto">
                            <i class="bi bi-check-lg"></i> <span data-translate="common.save">{{ isset($news) ? 'Update' : 'Publikasikan' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="editor-card">
                <div class="editor-card-header" data-translate="common.help">
                    Info
                </div>
                <div class="editor-card-body">
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                        <strong><span data-translate="news.contentLabel">Blok Konten</span>:</strong><br>
                        <span id="blockCount">0</span> <span data-translate="news.blocks">blok</span>
                    </p>

                    <hr>

                    <p class="text-muted mb-2" style="font-size: 0.9rem;">
                        <strong data-translate="news.previewLabel">Preview</strong>
                    </p>

                    <div id="previewContainer" class="border rounded p-2 p-md-3" style="background: #f9f9f9; cursor: pointer;"
                        data-bs-toggle="modal" data-bs-target="#previewModal">
                        <div
                            style="background: white; border-radius: 6px; padding: 1rem; min-height: 200px; max-height: 300px; overflow-y: auto;">
                            <div id="previewTitle"
                                style="font-size: 1rem; font-size: md-1.2rem; font-weight: 600; margin-bottom: 1rem; color: #333;">
                                <em class="text-muted" data-translate="news.previewTitlePlaceholder">
                                    Judul akan muncul di sini
                                </em>
                            </div>
                            <div id="previewContent" style="font-size: 0.8rem; color: #666; line-height: 1.5;">
                                <em class="text-muted" data-translate="news.previewContentPlaceholder">
                                    Preview konten akan muncul di sini
                                </em>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-primary">
                                <i class="bi bi-eye"></i>
                                <span data-translate="news.previewFullClick">Klik untuk preview penuh</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel" data-translate="news.previewModalTitle">
                        Preview Berita
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <article id="fullPreviewContent" style="max-width: 100%; margin: 0 auto;">
                        <h1 id="fullPreviewTitle"
                            style="font-size: 2rem; font-weight: 700; margin-bottom: 1.5rem; color: #333;"></h1>
                        <div id="fullPreviewBlocks"></div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
    

    <style>
        @media (max-width: 1024px) {
            .editor-container {
                display: block;
            }

            .editor-main {
                margin-bottom: 2rem;
            }

            .editor-sidebar {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .editor-header {
                padding: 0.75rem 0.5rem;
                margin-bottom: 1rem;
            }

            .editor-header-nav {
                font-size: 0.9rem;
                gap: 0.5rem;
            }

            .editor-card {
                margin-bottom: 1.25rem;
                border-radius: 0.5rem;
            }

            .editor-card-header {
                padding: 1rem;
                font-size: 0.95rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .editor-card-body {
                padding: 1rem;
            }

            .title-input {
                font-size: 1.3rem;
                padding: 0.75rem;
                line-height: 1.5;
            }

            .block-item {
                margin-bottom: 1.25rem;
                padding: 0.75rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
            }

            .block-header {
                padding: 0.75rem;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .block-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }

            .block-content {
                padding: 0.5rem 0;
            }

            .row.g-2 {
                gap: 1rem !important;
            }

            .row.g-md-3 {
                gap: 1rem !important;
            }

            .col-12.col-md-6 {
                margin-bottom: 0.5rem;
            }

            .add-block-buttons {
                display: flex;
                gap: 0.75rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-top: 1rem;
            }

            .btn-add-block {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
                flex: 1;
                min-width: 130px;
                max-width: 160px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .publish-box {
                margin-top: 1.5rem;
            }

            .publish-box-section {
                margin-bottom: 1.25rem;
                padding: 1rem;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
            }

            .publish-box-section:last-child {
                margin-bottom: 0;
            }

            .publish-box-label {
                font-size: 0.9rem;
                font-weight: 600;
                display: block;
                margin-bottom: 0.75rem;
                color: #374151;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .action-buttons .btn {
                width: 100%;
                padding: 0.75rem;
                font-size: 0.95rem;
            }

            .form-control,
            .form-select,
            textarea.form-control {
                font-size: 1rem;
                padding: 0.75rem;
            }

            .form-control-sm,
            .form-select-sm {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .form-label {
                font-size: 0.95rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .field-group {
                margin-bottom: 1rem;
            }

            #previewContainer {
                max-height: 280px;
                padding: 1rem;
            }

            #previewContainer > div {
                padding: 1rem;
            }

            #previewTitle {
                font-size: 1.1rem;
            }

            #previewContent {
                font-size: 0.85rem;
            }

            /* Image block specific */
            .image-input {
                cursor: pointer;
            }

            .preview-image {
                max-width: 100%;
                max-height: 250px;
                object-fit: cover;
            }

            small {
                font-size: 0.8rem;
                line-height: 1.4;
            }
        }

        @media (max-width: 576px) {
            .editor-header {
                padding: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .editor-header a, .editor-header span {
                font-size: 0.85rem;
            }

            .editor-container {
                padding: 0.5rem;
            }

            .editor-card {
                margin-bottom: 1rem;
                border-radius: 0.4rem;
                overflow: hidden;
            }

            .editor-card-header {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .editor-card-body {
                padding: 0.75rem;
            }

            .title-input {
                font-size: 1.15rem;
                padding: 0.6rem;
            }

            .block-item {
                margin-bottom: 1rem;
                padding: 0.6rem;
            }

            .block-header {
                padding: 0.5rem 0;
                gap: 0.5rem;
            }

            .block-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }

            .btn-sm {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            .add-block-buttons {
                gap: 0.5rem;
                margin-top: 0.75rem;
            }

            .btn-add-block {
                padding: 0.6rem 0.8rem;
                font-size: 0.75rem;
                min-width: 110px;
                max-width: 140px;
            }

            .btn-add-block i {
                font-size: 1.1rem;
            }

            .btn-add-block small {
                font-size: 0.65rem;
            }

            .publish-box-section {
                margin-bottom: 1rem;
                padding: 0.75rem;
            }

            .action-buttons {
                gap: 0.6rem;
            }

            .action-buttons .btn {
                padding: 0.6rem;
                font-size: 0.85rem;
            }

            .form-control,
            .form-select,
            textarea.form-control {
                font-size: 16px;
                padding: 0.6rem;
                border-radius: 0.35rem;
            }

            .form-label {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
            }

            small {
                font-size: 0.7rem;
            }

            #previewContainer {
                max-height: 250px;
                padding: 0.75rem;
                border-radius: 0.35rem;
            }

            #previewContainer > div {
                padding: 0.75rem;
            }

            #previewTitle {
                font-size: 1rem;
                margin-bottom: 0.75rem;
            }

            #previewContent {
                font-size: 0.8rem;
            }

            textarea {
                min-height: 120px;
            }

            .row g-2 {
                row-gap: 0.75rem !important;
            }
        }

        @media (max-width: 400px) {
            .editor-header {
                padding: 0.5rem;
            }

            .editor-container {
                padding: 0.4rem;
            }

            .editor-card {
                margin-bottom: 0.85rem;
            }

            .editor-card-header {
                padding: 0.6rem;
                font-size: 0.85rem;
            }

            .editor-card-body {
                padding: 0.6rem;
            }

            .title-input {
                font-size: 1rem;
                padding: 0.5rem;
            }

            .block-item {
                margin-bottom: 0.85rem;
                padding: 0.5rem;
            }

            .form-control,
            .form-select {
                font-size: 14px;
                padding: 0.5rem;
            }

            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            small {
                font-size: 0.65rem;
            }
        }

        /* Dark theme */
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
            color: #f0f0f0;
        }

        [data-theme="dark"] .title-input,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] textarea {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border-color: #555;
        }

        [data-theme="dark"] .title-input:focus,
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] textarea:focus {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border-color: #666;
        }

        [data-theme="dark"] .publish-box-section {
            background-color: #3a3a3a;
            border-color: #444;
        }

        [data-theme="dark"] .block-header {
            background-color: #3a3a3a;
            border-bottom-color: #444;
        }

        [data-theme="dark"] #previewContainer {
            background-color: #3a3a3a !important;
            border-color: #555;
        }

        [data-theme="dark"] #previewContainer > div {
            background-color: #2d2d2d !important;
            color: #f0f0f0;
        }

        [data-theme="dark"] #previewTitle {
            color: #f0f0f0 !important;
        }

        [data-theme="dark"] #previewContent {
            color: #aaa !important;
        }

        [data-theme="dark"] .alert-info {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border-color: #555;
        }

        [data-theme="dark"] .text-muted {
            color: #999 !important;
        }
        [data-theme="dark"] .editor-header {
            border-bottom-color: #444;
            background-color: #1e1e1e;
        }

        [data-theme="dark"] .editor-header a.text-muted,
        [data-theme="dark"] .editor-header .text-muted {
            color: #aaa !important;
        }

        [data-theme="dark"] .editor-header a.text-muted:hover {
            color: #f0f0f0 !important;
        }

        [data-theme="dark"] .block-item {
            background-color: #2d2d2d;
            border-color: #444;
        }

        [data-theme="dark"] .publish-box-label {
            color: #e0e0e0;
        }

        [data-theme="dark"] .form-label {
            color: #e0e0e0;
        }

        /* Dark mode mobile */
        @media (max-width: 768px) {
            [data-theme="dark"] .publish-box-section {
                background-color: #1e1e1e;
                border-color: #444;
            }

            [data-theme="dark"] .editor-card {
                border-color: #444;
            }

            [data-theme="dark"] .block-item {
                border-color: #444;
            }
        }

        @media (max-width: 576px) {
            [data-theme="dark"] .form-control,
            [data-theme="dark"] .form-select,
            [data-theme="dark"] textarea {
                background-color: #3a3a3a;
                border-color: #555;
                font-size: 16px;
            }

            [data-theme="dark"] .publish-box-section {
                background-color: #1e1e1e;
                border-color: #444;
                padding: 0.75rem;
            }
        }
    </style>
@endsection