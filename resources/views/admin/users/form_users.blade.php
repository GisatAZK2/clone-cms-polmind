@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-people"></i> <span data-translate="users.addNew">Tambah User Baru</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row">
        <div class="col-12 col-lg-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-people"></i> 
                        @if(isset($user))
                            <span data-translate="common.editForm">Edit User</span>
                        @else
                            <span data-translate="users.addNew">Tambah User Baru</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> <strong>Error!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label class="form-label" data-translate="common.name">Nama</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                value="{{ old('name', $user->name ?? '') }}"
                                required
                                placeholder="Masukkan nama user"
                                data-translate-placeholder="users.namePlaceholder"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select 
                                name="role" 
                                id="role"
                                class="form-select form-select-sm @error('role') is-invalid @enderror"
                                required
                            >
                                <option value="admin" {{ old('role', $user->role ?? 'admin') === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="superadmin" {{ old('role', $user->role ?? 'admin') === 'superadmin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label class="form-label" data-translate="common.email">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email ?? '') }}"
                                required
                                placeholder="Masukkan email"
                                data-translate-placeholder="users.emailPlaceholder"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label class="form-label" data-translate="common.password">Password</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control form-control-sm @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                data-translate-placeholder="users.passwordPlaceholder"
                                {{ isset($user) ? '' : 'required' }}
                            >
                            @if(isset($user))
                                <small class="text-muted" data-translate="common.passwordHint">Kosongkan jika tidak ingin mengubah password</small>
                            @endif
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="mb-3">
                            <label class="form-label" data-translate="common.passwordConfirm">Konfirmasi Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                placeholder="Ulangi password"
                                data-translate-placeholder="users.passwordConfirmPlaceholder"
                            >
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-arrow-left"></i> <span data-translate="common.back">Kembali</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 flex-md-grow-0">
                                <i class="bi bi-check-circle"></i> <span data-translate="common.save">Simpan</span>
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

    [data-theme="dark"] .alert-danger {
        background-color: #4a2a2a;
        border-color: #664444;
        color: #ff9999;
    }
</style>
@endsection
