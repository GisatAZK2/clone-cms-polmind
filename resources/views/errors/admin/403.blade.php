@extends('admin.layout.layout')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fef3c7; color:#d97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#d97706;">
            4<span style="color:#f59e0b;">0</span>3
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-403-title">
            Akses Ditolak
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-403-desc">
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Silakan masuk dengan akun yang sesuai atau hubungi administrator.
        </p>

        <div class="admin-error-actions">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="admin-error-btn-primary">
                    <i class="bi bi-speedometer2"></i>
                    <span data-translate="common.admin-error-home-button">Kembali ke Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="admin-error-btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span data-translate="admin-error-login-button">Login Admin</span>
                </a>
            @endauth

            <a href="{{ url()->previous() }}" class="admin-error-btn-secondary">
                <i class="bi bi-arrow-left"></i>
                <span data-translate="admin-error-back-button">Kembali</span>
            </a>
        </div>

    </div>
</div>
@endsection