@extends('admin.layout.layout')

@section('title', '504 - Gateway Timeout')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fef3c7; color:#d97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#d97706;">
            5<span style="color:#f59e0b;">0</span>4
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-504-title">
            Gateway Timeout
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-504-desc">
            Server tidak merespons dalam waktu yang ditentukan.<br>
            Koneksi mungkin lambat atau server sedang sibuk. Coba lagi.
        </p>

        <div class="admin-error-actions">
            <a href="{{ url()->current() }}" class="admin-error-btn-primary">
                <i class="bi bi-arrow-clockwise"></i>
                <span data-translate="admin-error-reload-button">Muat Ulang</span>
            </a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="admin-error-btn-secondary">
                    <i class="bi bi-speedometer2"></i>
                    <span data-translate="admin-error-dashboard-short-button">Dashboard</span>
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection