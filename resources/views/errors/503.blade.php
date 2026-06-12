@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>

        <span class="maintenance-badge">
            <span class="ping-dot"></span>
            <span data-translate="error-503-badge">Sedang Pemeliharaan</span>
        </span>

        <div class="error-code" style="color:#d97706;">
            5<span style="color:#f59e0b;">0</span>3
        </div>

        <h1 class="error-title" data-translate="error-503-title">
            Layanan Tidak Tersedia
        </h1>

        <p class="error-desc" data-translate="error-503-desc">
            Sistem sedang dalam proses pemeliharaan terjadwal.<br>
            Kami akan segera kembali online. Terima kasih atas kesabaran Anda.
        </p>

    </div>
</div>
@endsection