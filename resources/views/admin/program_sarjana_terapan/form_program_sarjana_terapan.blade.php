@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-book"></i>
    @if(isset($program))
        <span data-translate="program_sarjana_terapan.editForm">Edit Program Sarjana Terapan</span>
    @else
        <span data-translate="program_sarjana_terapan.addNew">Tambah Program Sarjana Terapan Baru</span>
    @endif
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-3 g-md-4 justify-content-center">

        {{-- ===== KOLOM FORM (KIRI) ===== --}}
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        @if(isset($program))
                            <i class="bi bi-pencil-square me-1"></i>
                            <span data-translate="program_sarjana_terapan.editForm">Edit Program Sarjana Terapan</span>
                        @else
                            <i class="bi bi-plus-circle me-1"></i>
                            <span data-translate="program_sarjana_terapan.addNew">Tambah Program Sarjana Terapan Baru</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> <strong data-translate="common.error">Error!</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="programForm"
                          action="{{ isset($program) ? route('admin.program_sarjana_terapan.update', $program->id) : route('admin.program_sarjana_terapan.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          novalidate>
                        @csrf
                        @if(isset($program))
                            @method('PUT')
                        @endif

                        {{-- Nama Prodi --}}
                        <div class="mb-3">
                            <label for="nama_prodi" class="form-label fw-semibold">
                                <span data-translate="program_sarjana_terapan.fieldNamaProdi">Nama Prodi</span> <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-sm @error('nama_prodi') is-invalid @enderror"
                                   id="nama_prodi"
                                   name="nama_prodi"
                                   value="{{ old('nama_prodi', $program->content['nama_prodi'] ?? '') }}"
                                   placeholder="Masukkan nama program studi"
                                   data-translate-placeholder="program_sarjana_terapan.namePlaceholder"
                                   required>
                            @error('nama_prodi')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gelar Sarjana --}}
                        <div class="mb-3">
                            <label for="gelar_sarjana" class="form-label fw-semibold">
                                <span data-translate="program_sarjana_terapan.fieldGelarSarjana">Gelar Sarjana</span> <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-sm @error('gelar_sarjana') is-invalid @enderror"
                                   id="gelar_sarjana"
                                   name="gelar_sarjana"
                                   value="{{ old('gelar_sarjana', $program->content['gelar_sarjana'] ?? '') }}"
                                   placeholder="Contoh: Sarjana Terapan (D4)"
                                   data-translate-placeholder="program_sarjana_terapan.degreePlaceholder"
                                   required>
                            @error('gelar_sarjana')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi Prodi --}}
                        <div class="mb-3">
                            <label for="deskripsi_prodi" class="form-label fw-semibold">
                                <span data-translate="program_sarjana_terapan.fieldDeskripsiProdi">Deskripsi Prodi</span> <span class="text-danger">*</span>
                            </label>
                            @include('admin.components.text-editor', [
                                'name' => 'deskripsi_prodi',
                                'value' => old('deskripsi_prodi', $program->content['deskripsi_prodi'] ?? ''),
                                'required' => true,
                                'label' => false
                            ])
                            @error('deskripsi_prodi')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Semester Fields --}}
                        @php
                            $semesterFields = [
                                'semester_1' => 'program_sarjana_terapan.fieldSemester1',
                                'semester_2' => 'program_sarjana_terapan.fieldSemester2',
                                'semester_3' => 'program_sarjana_terapan.fieldSemester3',
                                'semester_4' => 'program_sarjana_terapan.fieldSemester4',
                                'semester_5' => 'program_sarjana_terapan.fieldSemester5',
                                'semester_6' => 'program_sarjana_terapan.fieldSemester6',
                                'semester_7' => 'program_sarjana_terapan.fieldSemester7',
                                'semester_8' => 'program_sarjana_terapan.fieldSemester8',
                            ];
                        @endphp

                        @foreach($semesterFields as $field => $translationKey)
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label fw-semibold">
                                    <span data-translate="{{ $translationKey }}">{{ ucfirst(str_replace('_', ' ', $field)) }}</span> <span class="text-danger">*</span>
                                </label>
                                @include('admin.components.text-editor', [
                                    'name' => $field,
                                    'value' => old($field, $program->content[$field] ?? ''),
                                    'required' => true,
                                    'label' => false
                                ])
                                @error($field)
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        {{-- Gambar Prodi --}}
                        <div class="mb-4">
                            <label for="gambar_prodi_input" class="form-label fw-semibold">
                                <span data-translate="program_sarjana_terapan.fieldGambarProdi">Gambar Prodi</span>
                                @if(!isset($program))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            <input type="file"
                                   class="form-control form-control-sm @error('gambar_prodi.*') is-invalid @enderror"
                                   id="gambar_prodi_input"
                                   name="gambar_prodi[]"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   multiple
                                   {{ !isset($program) ? 'required' : '' }}>

                            <div class="form-text text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-info-circle"></i>
                                <span data-translate="program_sarjana_terapan.imageFormatHint">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar.</span>
                                @if(isset($program))
                                    <span data-translate="program_sarjana_terapan.imageDragHint">Seret thumbnail untuk mengubah urutan. Pilih gambar tambahan atau kosongkan untuk tidak menambah.</span>
                                @endif
                            </div>

                            <div id="image-preview-area" class="mt-3">
                                <div class="form-text text-muted mb-2" style="font-size: 0.78rem;">
                                    <i class="bi bi-arrows-move"></i> <span data-translate="program_sarjana_terapan.imageReorderHint">Seret gambar untuk mengatur ulang urutan.</span>
                                </div>
                                {{-- 
                                    PENTING: Semua card gambar (existing maupun baru) dirender via JavaScript
                                    agar event listener drag terpasang dengan benar di semua kondisi (create & update).
                                    Data existing images dikirim via data attribute di bawah.
                                --}}
                                <div id="image-preview-list"
                                    class="row g-2"
                                    data-storage-base="{{ Storage::url('') }}"
                                    data-existing-images='@if(isset($program) && isset($program->content['gambar_prodi']) && is_array($program->content['gambar_prodi'])){{ json_encode(array_values($program->content['gambar_prodi'])) }}@else[]@endif'
                                    data-existing-alts='@if(isset($program) && isset($program->content['image_alt']) && is_array($program->content['image_alt'])){{ json_encode(array_values($program->content['image_alt'])) }}@else[]@endif'>
                                </div>
                            </div>

                            @error('gambar_prodi.*')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                            @error('image_order')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.program_sarjana_terapan.index') }}"
                               class="btn btn-secondary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-arrow-left"></i>
                                <span data-translate="common.back">Kembali</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-check-circle"></i>
                                <span data-translate="{{ isset($program) ? 'program_sarjana_terapan.buttonUpdate' : 'program_sarjana_terapan.buttonSave' }}">
                                    {{ isset($program) ? 'Update Program' : 'Simpan Program' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-preview-item {
        cursor: grab;
        transition: transform 0.18s ease, opacity 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        touch-action: pan-y;
        user-select: none;
    }

    .image-preview-item.is-touching {
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
        z-index: 5;
    }

    .image-preview-item.order-changed {
        outline: 2px solid #2563eb;
        background: rgba(37, 99, 235, 0.08) !important;
        border-radius: 0.5rem;
    }

    [data-theme="dark"] .image-preview-item.order-changed {
        background: rgba(59, 130, 246, 0.16) !important;
    }
    .image-preview-item.dragging {
        opacity: 0.4;
        cursor: grabbing;
    }
    /* Placeholder hanya tampil saat drag melayang di atas target */
    .image-preview-item.drop-placeholder {
        border: 2px dashed #0d6efd;
        background: rgba(13, 110, 253, 0.05);
        min-height: 190px;
        border-radius: 0.375rem;
        pointer-events: none; /* biar tidak mengganggu dragover di list */
    }
    .image-preview-item.drop-placeholder .card {
        border: 1px dashed rgba(13, 110, 253, 0.6);
        background: transparent;
        min-height: 190px;
    }
    .image-preview-item.drop-placeholder .card-body {
        min-height: 150px;
    }
    #image-preview-list {
        min-height: 60px;
        padding-bottom: 8px;
    }
</style>
@endsection