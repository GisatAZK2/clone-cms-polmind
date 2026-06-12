@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-card-list"></i>
    @if(isset($item))
        <span data-translate="contentPendaftaran.editForm">Edit Content Pendaftaran</span>
    @else
        <span data-translate="contentPendaftaran.addForm">Tambah Content Pendaftaran</span>
    @endif
@endsection

@section('content')
<div class="editor-header px-2 px-md-3 py-2 py-md-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.content-pendaftaran.index') }}" class="text-muted text-decoration-none me-1">
                <i class="bi bi-chevron-left"></i>
                <span data-translate="contentPendaftaran.title">Content Pendaftaran</span>
            </a>
        </div>
    </div>
</div>

<div class="editor-container px-2 px-md-3">
    <div class="editor-main">
        <form action="{{ isset($item) ? route('admin.content-pendaftaran.update', $item->id) : route('admin.content-pendaftaran.store') }}"
              method="POST"
              id="mainForm"
              enctype="multipart/form-data"
              novalidate>
            @csrf

            @if(isset($item))
                @method('PUT')
            @endif

            {{-- ==================== TIPE KONTEN ==================== --}}
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="contentPendaftaran.tipeKonten">
                    Tipe Konten
                </div>

                <div class="editor-card-body">
                    <select name="type"
                        class="form-select @error('type') is-invalid @enderror"
                        {{ isset($item) ? 'disabled' : '' }}>
                        <option value="" selected disabled data-translate="contentPendaftaran.pilihtType">
                            -- Pilih Type --
                        </option>

                        <option value="atas" {{ old('type', $item->type ?? '') === 'atas' ? 'selected' : '' }}
                            data-translate="contentPendaftaran.typeAtas">
                            Atas
                        </option>

                        <option value="bawah" {{ old('type', $item->type ?? '') === 'bawah' ? 'selected' : '' }}
                            data-translate="contentPendaftaran.typeBawah">
                            Bawah
                        </option>
                    </select>

                    @if(isset($item))
                        <input type="hidden" name="type" value="{{ $item->type }}">
                        <small class="text-muted d-block mt-2" data-translate="contentPendaftaran.typeLocked">
                            Type tidak dapat diubah setelah dibuat.
                        </small>
                    @endif

                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ==================== INFORMASI TAMBAHAN ==================== --}}
            <div class="editor-card mb-3">
                <div class="editor-card-header" data-translate="contentPendaftaran.additionalInfo">
                    Informasi Tambahan
                </div>

                <div class="editor-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label" data-translate="contentPendaftaran.sloganLabel">
                                Kata-kata / Slogan
                            </label>

                            <textarea name="kata_kata"
                                class="form-control @error('kata_kata') is-invalid @enderror"
                                rows="4"
                                placeholder="Masukkan kata-kata atau slogan..."
                                data-translate-placeholder="contentPendaftaran.sloganPlaceholder">{{ old('kata_kata', $item->kata_kata ?? '') }}</textarea>

                            @error('kata_kata')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label" data-translate="contentPendaftaran.openDate">
                                Tanggal Buka
                            </label>

                            <input type="date"
                                name="tahun_buka"
                                class="form-control @error('tahun_buka') is-invalid @enderror"
                                value="{{ old('tahun_buka', isset($item) && $item->tahun_buka ? $item->tahun_buka->format('Y-m-d') : '') }}">

                            @error('tahun_buka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== ITEMS GAMBAR ==================== --}}
            @php
                $existingItems = old('items', isset($item) ? ($item->content ?? []) : []);

                // Mode create: minimal harus ada 1 block.
                // Block pertama tidak boleh dihapus.
                if (count($existingItems) < 1) {
                    $existingItems = [[]];
                }

                $itemKeys = array_keys($existingItems);
                $nextIndex = count($itemKeys) > 0 ? (max(array_map('intval', $itemKeys)) + 1) : 0;
            @endphp

            <div class="editor-card mb-3">
                <div class="editor-card-header d-flex justify-content-between align-items-center">
                    <span data-translate="contentPendaftaran.itemGambar">Item Gambar</span>

                    <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                        <i class="bi bi-plus-circle"></i>
                        <span data-translate="contentPendaftaran.tambahItem">Tambah Item</span>
                    </button>
                </div>

                <div class="editor-card-body">
                    @error('items')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div id="itemsContainer" data-next-index="{{ $nextIndex }}">
                        @foreach($existingItems as $idx => $row)
                            @php
                                $imagePath = $row['url_images'] ?? null;
                                $hasImage = !empty($imagePath);
                            @endphp

                            <div class="item-row card mb-3 border" data-index="{{ $idx }}">
                                <div class="card-header d-flex justify-content-between align-items-center py-2">
                                    <strong>
                                        <span data-translate="contentPendaftaran.itemLabel">Item</span>
                                        #<span class="item-number">{{ $loop->iteration }}</span>
                                    </strong>

                                    <button type="button" class="btn btn-sm btn-link text-danger remove-item">
                                        <i class="bi bi-trash"></i>
                                        <span data-translate="contentPendaftaran.hapusItem">Hapus</span>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-5">
                                            <label class="form-label">
                                                <span data-translate="contentPendaftaran.uploadGambar">Upload Gambar</span>
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="file"
                                                name="items[{{ $idx }}][url_images]"
                                                class="form-control form-control-sm url-images-input"
                                                accept="image/*"
                                                {{ $hasImage ? '' : 'required' }}>

                                            <input type="hidden"
                                                name="items[{{ $idx }}][old_url_images]"
                                                value="{{ $imagePath }}">

                                            <small class="text-muted d-block mt-1" data-translate="contentPendaftaran.imageFormat">
                                                Format: JPG, PNG. Maksimal 2MB.
                                            </small>

                                            <div class="item-image-preview-wrap mt-2 {{ $hasImage ? '' : 'd-none' }}">
                                                <img
                                                    src="{{ $hasImage ? asset('storage/' . $imagePath) : '' }}"
                                                    alt="{{ $row['alt'] ?? 'Preview gambar' }}"
                                                    class="img-thumbnail item-image-preview preview-image"
                                                    style="width: 120px; height: 90px; object-fit: cover;">
                                            </div>

                                            @if($hasImage)
                                                <small class="text-success d-block mt-1">
                                                    <i class="bi bi-check-circle"></i>
                                                    <span data-translate="contentPendaftaran.fileTersimpan">File tersimpan</span>
                                                </small>
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label class="form-label" data-translate="contentPendaftaran.altText">
                                                Alt Text
                                            </label>

                                            <input type="text"
                                                name="items[{{ $idx }}][alt]"
                                                class="form-control form-control-sm"
                                                placeholder="Deskripsi gambar"
                                                data-translate-placeholder="contentPendaftaran.altPlaceholder"
                                                value="{{ $row['alt'] ?? '' }}">
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label" data-translate="contentPendaftaran.linkUrl">
                                                Link URL
                                            </label>

                                            <input type="text"
                                                name="items[{{ $idx }}][link_url]"
                                                class="form-control form-control-sm"
                                                placeholder="https://example.com"
                                                data-translate-placeholder="contentPendaftaran.linkPlaceholder"
                                                value="{{ $row['link_url'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ==================== ACTION BUTTONS ==================== --}}
            <div class="text-end mt-4">
                <a href="{{ route('admin.content-pendaftaran.index') }}" class="btn btn-secondary">
                    <span data-translate="common.cancel">Batal</span>
                </a>

                <button type="submit" class="btn btn-primary">
                    @if(isset($item))
                        <span data-translate="common.update">Perbarui</span>
                    @else
                        <span data-translate="common.save">Simpan</span>
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== TEMPLATE JS DYNAMIC ITEM ==================== --}}
<template id="itemTemplate">
    <div class="item-row card mb-3 border" data-index="__INDEX__">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <strong>
                <span data-translate="contentPendaftaran.itemLabel">Item</span>
                #<span class="item-number">__NUM__</span>
            </strong>

            <button type="button" class="btn btn-sm btn-link text-danger remove-item">
                <i class="bi bi-trash"></i>
                <span data-translate="contentPendaftaran.hapusItem">Hapus</span>
            </button>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-5">
                    <label class="form-label">
                        <span data-translate="contentPendaftaran.uploadGambar">Upload Gambar</span>
                        <span class="text-danger">*</span>
                    </label>

                    <input type="file"
                        name="items[__INDEX__][url_images]"
                        class="form-control form-control-sm url-images-input"
                        accept="image/*"
                        required>

                    <input type="hidden"
                        name="items[__INDEX__][old_url_images]"
                        value="">

                    <small class="text-muted d-block mt-1" data-translate="contentPendaftaran.imageFormat">
                        Format: JPG, PNG. Maksimal 2MB.
                    </small>

                    <div class="item-image-preview-wrap mt-2 d-none">
                        <img
                            src=""
                            alt="Preview gambar"
                            class="img-thumbnail item-image-preview preview-image"
                            style="width: 120px; height: 90px; object-fit: cover;">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label" data-translate="contentPendaftaran.altText">
                        Alt Text
                    </label>

                    <input type="text"
                        name="items[__INDEX__][alt]"
                        class="form-control form-control-sm"
                        placeholder="Deskripsi gambar"
                        data-translate-placeholder="contentPendaftaran.altPlaceholder">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label" data-translate="contentPendaftaran.linkUrl">
                        Link URL
                    </label>

                    <input type="text"
                        name="items[__INDEX__][link_url]"
                        class="form-control form-control-sm"
                        placeholder="https://example.com"
                        data-translate-placeholder="contentPendaftaran.linkPlaceholder">
                </div>
            </div>
        </div>
    </div>
</template>

@endsection