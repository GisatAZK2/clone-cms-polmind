@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-person-circle"></i>
    <span data-translate="profileAdmin.title">Profil Admin</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-person-circle"></i>
                        <span data-translate="profileAdmin.infoTitle">Informasi Profil</span>
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label" data-translate="profileAdmin.fullName">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ auth()->user()->name }}"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label" data-translate="common.email">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ auth()->user()->email }}"
                                    required
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">
                            <i class="bi bi-key"></i>
                            <span data-translate="profileAdmin.changePassword">Ubah Password</span>
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label" data-translate="profileAdmin.currentPassword">
                                    Password Saat Ini
                                </label>

                                <input
                                    type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    data-translate-placeholder="common.passwordHint"
                                >

                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label" data-translate="profileAdmin.newPassword">
                                    Password Baru
                                </label>

                                <input
                                    type="password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    id="new_password"
                                    name="new_password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    data-translate-placeholder="common.passwordHint"
                                >

                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="new_password_confirmation" class="form-label" data-translate="profileAdmin.confirmNewPassword">
                                    Konfirmasi Password Baru
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="new_password_confirmation"
                                    name="new_password_confirmation"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    data-translate-placeholder="common.passwordHint"
                                >
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <span data-translate="common.cancel">Batal</span>
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i>
                                <span data-translate="profileAdmin.saveChanges">Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection