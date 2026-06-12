@extends('admin.layout.layout')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
                <path d="M8.5 8.5l5 5M13.5 8.5l-5 5"/>
            </svg>
        </div>

        <div class="admin-error-code">
            4<span>0</span>4
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-404-title">
            Halaman Tidak Ditemukan
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-404-desc">
            Halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.<br>
            Periksa kembali alamat URL atau kembali ke dashboard.
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

            <a href="{{ url('/') }}" class="admin-error-btn-secondary">
                <i class="bi bi-house-door"></i>
                <span data-translate="admin-error-back-button">Ke Beranda</span>
            </a>
        </div>

    </div>
</div>
@endsection