@extends('admin.layout.layout')

@section('title', '419 - Sesi Telah Berakhir')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#ede9fe; color:#7c3aed;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#7c3aed;">
            4<span style="color:#8b5cf6;">1</span>9
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-419-title">
            Sesi Telah Berakhir
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-419-desc">
            Halaman ini sudah kedaluwarsa karena tidak ada aktivitas.<br>
            Muat ulang halaman atau login ulang untuk melanjutkan.
        </p>

        <div class="admin-error-actions">
            <a href="{{ route('login') }}" class="admin-error-btn-primary">
                <i class="bi bi-box-arrow-in-right"></i>
                <span data-translate="admin-error-relogin-button">Login Ulang</span>
            </a>

            <a href="{{ url()->previous() }}" class="admin-error-btn-secondary">
                <i class="bi bi-arrow-clockwise"></i>
                <span data-translate="admin-error-try-again-button">Coba Lagi</span>
            </a>
        </div>

    </div>
</div>
@endsection