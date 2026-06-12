@extends('admin.layout.layout')

@section('title', '502 - Gateway Bermasalah')

@section('content')
<div class="admin-error-page">
    <div class="admin-error-card">

        <div class="admin-error-icon-wrap" style="background:#fee2e2; color:#dc2626;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>

        <div class="admin-error-code" style="color:#dc2626;">
            5<span style="color:#ef4444;">0</span>2
        </div>

        <h1 class="admin-error-title" data-translate="admin-error-502-title">
            Gateway Bermasalah
        </h1>

        <p class="admin-error-desc" data-translate="admin-error-502-desc">
            Server menerima respons tidak valid dari server upstream.<br>
            Masalah ini bersifat sementara, silakan coba beberapa saat lagi.
        </p>

        <div class="admin-error-actions">
            <a href="{{ url()->current() }}" class="admin-error-btn-primary">
                <i class="bi bi-arrow-clockwise"></i>
                <span data-translate="admin-error-reload-button">Muat Ulang</span>
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