@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#e0f2fe;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
                <line x1="18" y1="8" x2="23" y2="13"/>
                <line x1="23" y1="8" x2="18" y2="13"/>
            </svg>
        </div>

        <div class="error-code" style="color:#0284c7;">
            4<span style="color:#0ea5e9;">0</span>1
        </div>

        <h1 class="error-title" data-translate="error-401-title">
            Tidak Terautentikasi
        </h1>

        <p class="error-desc" data-translate="error-401-desc">
            Anda perlu masuk terlebih dahulu untuk mengakses halaman ini.<br>
            Silakan login dengan akun Anda.
        </p>

    </div>
</div>
@endsection