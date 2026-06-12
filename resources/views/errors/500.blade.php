@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        <div class="error-code" style="color:#dc2626;">
            5<span style="color:#ef4444;">0</span>0
        </div>

        <h1 class="error-title" data-translate="error-500-title">
            Kesalahan pada Server
        </h1>

        <p class="error-desc" data-translate="error-500-desc">
            Terjadi kesalahan internal pada server kami.<br>
            Tim teknis kami sedang menangani masalah ini. Silakan coba beberapa saat lagi.
        </p>

    </div>
</div>
@endsection