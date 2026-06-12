@extends('admin.layout.layout')

@section('title', '429 - Terlalu Banyak Permintaan')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fce7f3; color:#db2777;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#db2777;">
            4<span style="color:#ec4899;">2</span>9
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-429-title">
            Terlalu Banyak Permintaan
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-429-desc">
            Anda mengirim terlalu banyak permintaan dalam waktu singkat.<br>
            Tunggu beberapa saat sebelum mencoba kembali.
        </p>

        <div class="admin-error-actions">
            <a href="{{ url()->previous() }}" class="admin-error-btn-primary">
                <i class="bi bi-arrow-left"></i>
                <span data-translate="admin-error-back-button">Kembali</span>
            </a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="admin-error-btn-secondary">
                    <i class="bi bi-speedometer2"></i>
                    <span data-translate="admin-error-dashboard-button">Dashboard</span>
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection