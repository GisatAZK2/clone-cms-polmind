@extends('admin.layout.layout')

@section('title', '500 - Kesalahan Server')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fee2e2; color:#dc2626;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#dc2626;">
            5<span style="color:#ef4444;">0</span>0
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-500-title">
            Kesalahan pada Server
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-500-desc">
            Terjadi kesalahan internal pada server kami.<br>
            Tim teknis sedang menangani masalah ini. Silakan coba beberapa saat lagi.
        </p>

        <div class="admin-error-actions">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="admin-error-btn-primary">
                    <i class="bi bi-speedometer2"></i>
                    <span data-translate="admin-error-dashboard-button">Kembali ke Dashboard</span>
                </a>
            @endauth

            <a href="{{ url()->current() }}" class="admin-error-btn-secondary">
                <i class="bi bi-arrow-clockwise"></i>
                <span data-translate="admin-error-reload-button">Muat Ulang</span>
            </a>
        </div>

    </div>
</div>
@endsection