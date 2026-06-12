@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#f0fdf4;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
        </div>

        <div class="error-code" style="color:#16a34a;">
            4<span style="color:#22c55e;">0</span>5
        </div>

        <h1 class="error-title" data-translate="error-405-title">
            Metode Tidak Diizinkan
        </h1>

        <p class="error-desc" data-translate="error-405-desc">
            Permintaan yang Anda kirim tidak diizinkan untuk halaman ini.<br>
            Kembali dan coba cara yang berbeda.
        </p>

    </div>
</div>
@endsection