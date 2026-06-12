@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-person-lines-fill"></i>
    @if(isset($profilePage))
        <span data-translate="profile_page.editTitle">Edit Profile Page</span>
    @else
        <span data-translate="profile_page.addNew">Tambah Profile Page Baru</span>
    @endif
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-3 g-md-4 justify-content-center">

        <!-- FORM COLUMN -->
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        @if(isset($profilePage))
                            <i class="bi bi-pencil-square me-1"></i>
                            <span data-translate="profile_page.editTitle">Edit Profile Page</span>
                        @else
                            <i class="bi bi-file-plus me-1"></i>
                            <span data-translate="profile_page.addNew">Tambah Profile Page Baru</span>
                        @endif
                    </h5>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> <strong>Error!</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="profilePageForm"
                          action="{{ isset($profilePage) ? route('admin.profile-page.update', $profilePage->id) : route('admin.profile-page.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          novalidate>
                        @csrf
                        @if(isset($profilePage))
                            @method('PUT')
                        @endif

                        <!-- TYPE SELECT -->
                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">
                                <span data-translate="profile_page.type">Tipe Profile</span>
                                <span class="text-danger">*</span>
                            </label>

                            @if(isset($profilePage))
                                <!-- EDIT MODE -->
                                @php
                                    $badgeColor = match($profilePage->type) {
                                        'cover' => 'primary',
                                        'visi_misi' => 'warning',
                                        'profile' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge bg-{{ $badgeColor }} fs-6 px-3 py-2">
                                        {{ strtoupper(str_replace('_', ' ', $profilePage->type)) }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-lock-fill"></i>
                                        <span data-translate="profile_page.typeLocked">Type tidak dapat diubah</span>
                                    </small>
                                </div>
                                <input type="hidden" name="type" value="{{ $profilePage->type }}">
                            @else
                                <!-- CREATE MODE -->
                                <select class="form-select form-select-sm @error('type') is-invalid @enderror"
                                            id="type" name="type" required>
                                    <option value="" disabled selected>
                                        <span data-translate="profile_page.selectType">-- Pilih Tipe Profile --</span>
                                    </option>

                                    @php $usedTypes = $usedTypes ?? []; @endphp

                                    <option value="cover"
                                            {{ old('type') === 'cover' ? 'selected' : '' }}
                                            {{ in_array('cover', $usedTypes) ? 'disabled' : '' }}>
                                        Cover {{ in_array('cover', $usedTypes) ? '(sudah ada)' : '' }}
                                    </option>

                                    <option value="visi_misi"
                                            {{ old('type') === 'visi_misi' ? 'selected' : '' }}
                                            {{ in_array('visi_misi', $usedTypes) ? 'disabled' : '' }}>
                                        Visi & Misi {{ in_array('visi_misi', $usedTypes) ? '(sudah ada)' : '' }}
                                    </option>

                                    <!-- PROFILE BISA MULTIPLE -->
                                    <option value="profile"
                                            {{ old('type') === 'profile' ? 'selected' : '' }}>
                                        Profile (Bisa Ditambah Berkali-kali)
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- COVER FIELDS -->
                        <div id="coverFields" class="type-fields d-none">
                            <div class="mb-3">
                                <label for="url_images" class="form-label fw-semibold">
                                    <span data-translate="profile_page.gambar">Gambar</span>
                                    <span class="text-danger">*</span>
                                </label>
                               <input type="file" name="url_images" id="url_images"
                                        class="form-control form-control-sm"
                                        accept="image/jpeg,image/png,image/jpg,image/gif"
                                        data-preview-target="previewCover">
                                <small class="text-muted" data-translate="profile_page.imageFormat">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                @if(isset($profilePage) && $profilePage->type === 'cover' && $profilePage->url_images)
                                    <img id="previewCover" src="{{ Storage::url($profilePage->url_images) }}"
                                         class="img-thumbnail preview-image mt-2" style="max-width:180px;">
                                @else
                                    <img id="previewCover" src="#" class="img-thumbnail preview-image mt-2 d-none" style="max-width:180px;">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="alt" class="form-label fw-semibold">
                                    <span data-translate="common.altText">Alt Text (Untuk SEO)</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="alt" id="alt" class="form-control form-control-sm"
                                       data-translate-placeholder="profile_page.altPlaceholder"
                                       placeholder="Deskripsi gambar cover"
                                       value="{{ old('alt', isset($profilePage) && $profilePage->type === 'cover' ? $profilePage->alt : '') }}">
                            </div>
                        </div>

                        <!-- VISI MISI FIELDS -->
                        <div id="visiMisiFields" class="type-fields d-none">
                            <div class="mb-3">
                                @include('admin.components.text-editor', [
                                    'name' => 'visi',
                                    'label' => 'Visi',
                                    'labelTranslate' => 'profile_page.visi',
                                    'value' => old('visi', isset($profilePage) && $profilePage->type === 'visi_misi' ? $profilePage->visi : ''),
                                    'required' => true
                                ])
                            </div>
                            <div class="mb-3">
                                @include('admin.components.text-editor', [
                                    'name' => 'misi',
                                    'label' => 'Misi',
                                    'labelTranslate' => 'profile_page.misi',
                                    'value' => old('misi', isset($profilePage) && $profilePage->type === 'visi_misi' ? $profilePage->misi : ''),
                                    'required' => true
                                ])
                            </div>
                        </div>

                        <!-- PROFILE FIELDS (MULTIPLE) -->
                        <div id="profileFields" class="type-fields d-none">
                            <div class="mb-3">
                                <label for="profile_url_images" class="form-label fw-semibold">
                                    <span data-translate="profile_page.foto">Foto</span>
                                    <span class="text-danger">*</span>
                                </label>
                               <input type="file" name="profile_url_images" id="profile_url_images"
                                            class="form-control form-control-sm"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            data-preview-target="previewProfile">
                                <small class="text-muted" data-translate="profile_page.imageFormat">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                @if(isset($profilePage) && $profilePage->type === 'profile' && $profilePage->url_images)
                                    <img id="previewProfile" src="{{ Storage::url($profilePage->url_images) }}"
                                         class="img-thumbnail preview-image mt-2" style="max-width:180px;">
                                @else
                                    <img id="previewProfile" src="#" class="img-thumbnail preview-image mt-2 d-none" style="max-width:180px;">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="profile_alt" class="form-label fw-semibold">
                                    <span data-translate="common.altText">Alt Text (Untuk SEO)</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="profile_alt" id="profile_alt" class="form-control form-control-sm"
                                       data-translate-placeholder="profile_page.altPlaceholder"
                                       placeholder="Deskripsi foto profil"
                                       value="{{ old('profile_alt', isset($profilePage) && $profilePage->type === 'profile' ? $profilePage->alt : '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="nama_profil" class="form-label fw-semibold">
                                    <span data-translate="profile_page.namaProfile">Nama</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_profil" id="nama_profil" class="form-control form-control-sm"
                                       data-translate-placeholder="profile_page.namaProfilePlaceholder"
                                       placeholder="Masukkan nama profil"
                                       value="{{ old('nama_profil', isset($profilePage) && $profilePage->type === 'profile' ? ($profilePage->content['nama_profil'] ?? '') : '') }}">
                            </div>
                            <div class="mb-3">
                                @include('admin.components.text-editor', [
                                    'name' => 'deskripsi_profile',
                                    'label' => 'Deskripsi Profil',
                                    'labelTranslate' => 'profile_page.deskripsiProfile',
                                    'value' => old('deskripsi_profile', isset($profilePage) && $profilePage->type === 'profile' ? ($profilePage->content['deskripsi_profile'] ?? '') : ''),
                                    'required' => true
                                ])
                            </div>
                        </div>

                        <!-- SUBMIT -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.profile-page.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i>
                                <span data-translate="common.cancel">Batal</span>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                @if(isset($profilePage))
                                    <span data-translate="common.update">Update</span>
                                @else
                                    <span data-translate="common.save">Simpan</span>
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SIDEBAR INFO -->
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i>
                        <span data-translate="profile_page.infoLabel">Panduan</span>
                    </h6>
                </div>
                <div class="card-body p-3 small">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-primary" data-translate="profile_page.typeCover">COVER</span>
                            <span data-translate="profile_page.infoCover">Gambar utama halaman profil</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning" data-translate="profile_page.typeVisiMisi">VISI MISI</span>
                            <span data-translate="profile_page.infoVisiMisi">Visi dan Misi kampus</span>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-success" data-translate="profile_page.typeProfile">PROFILE</span>
                            <span data-translate="profile_page.infoProfile">Data individu (bisa multiple)</span>
                        </li>
                    </ul>

                    @if(isset($profilePage))
                        <hr>
                        <p class="text-muted mb-1 fw-semibold" data-translate="profile_page.infoTimestamp">Informasi</p>
                        <p class="text-muted mb-1">
                            <span data-translate="profile_page.infoCreated">Dibuat:</span>
                            {{ $profilePage->created_at->format('d M Y') }}
                        </p>
                        <p class="text-muted mb-0">
                            <span data-translate="profile_page.infoUpdated">Diperbarui:</span>
                            {{ $profilePage->updated_at->format('d M Y') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFormFields() {
    const type = document.getElementById('type').value;
    if (!type) return;

    document.querySelectorAll('.type-fields').forEach(section => {
        section.classList.add('d-none');
        section.querySelectorAll('input, textarea').forEach(el => el.disabled = true);
    });

    const map = {
        'cover': 'coverFields',
        'visi_misi': 'visiMisiFields',
        'profile': 'profileFields'
    };

    if (map[type]) {
        const active = document.getElementById(map[type]);
        active.classList.remove('d-none');
        active.querySelectorAll('input, textarea').forEach(el => el.disabled = false);
    }
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateFormFields();
});
</script>

<style>
.type-fields { transition: all 0.3s ease; }
</style>
@endsection