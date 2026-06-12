@extends('admin.layout.layout')

@section('title', '405 - Metode Tidak Diizinkan')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#f0fdf4; color:#16a34a;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#16a34a;">
            4<span style="color:#22c55e;">0</span>5
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-405-title">
            Metode Tidak Diizinkan
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-405-desc">
            Permintaan yang Anda kirim tidak diizinkan untuk halaman ini.<br>
            Kembali dan coba cara yang berbeda.
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