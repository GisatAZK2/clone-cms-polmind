@extends('admin.layout.layout')

@section('title', '401 - Tidak Terautentikasi')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#e0f2fe; color:#0284c7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
                <line x1="18" y1="8" x2="23" y2="13"/>
                <line x1="23" y1="8" x2="18" y2="13"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#0284c7;">
            4<span style="color:#0ea5e9;">0</span>1
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-401-title">
            Tidak Terautentikasi
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-401-desc">
            Anda perlu masuk terlebih dahulu untuk mengakses halaman ini.<br>
            Silakan login dengan akun Anda.
        </p>

        <div class="admin-error-actions">
            <a href="{{ route('login') }}" class="admin-error-btn-primary">
                <i class="bi bi-box-arrow-in-right"></i>
                <span data-translate="admin-error-login-button">Login Admin</span>
            </a>

            <a href="{{ url('/') }}" class="admin-error-btn-secondary">
                <i class="bi bi-house-door"></i>
                <span data-translate="common.admin-error-home-button">Kembali ke Dashboard</span>
            </a>
        </div>

    </div>
</div>
@endsection