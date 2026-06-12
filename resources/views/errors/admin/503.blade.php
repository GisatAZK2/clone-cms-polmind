@extends('admin.layout.layout')

@section('title', '503 - Layanan Tidak Tersedia')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fef3c7; color:#d97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>

        <span class="admin-maintenance-badge">
            <span class="admin-ping-dot"></span>
            <span data-translate="admin-error-503-badge">Sedang Pemeliharaan</span>
        </span>

        <div class="admin-error-code" style="color:#d97706;">
            5<span style="color:#f59e0b;">0</span>3
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-503-title">
            Layanan Tidak Tersedia
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-503-desc">
            Sistem sedang dalam proses pemeliharaan terjadwal.<br>
            Kami akan segera kembali online. Terima kasih atas kesabaran Anda.
        </p>

        <div class="admin-error-actions">
            <a href="{{ url()->current() }}" class="admin-error-btn-primary">
                <i class="bi bi-arrow-clockwise"></i>
                <span data-translate="admin-error-try-again-button">Coba Lagi</span>
            </a>

            <a href="{{ url('/') }}" class="admin-error-btn-secondary">
                <i class="bi bi-house-door"></i>
                <span data-translate="common.admin-error-home-button">Ke Beranda</span>
            </a>
        </div>

    </div>
</div>
@endsection