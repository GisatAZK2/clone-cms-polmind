@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
                <path d="M8.5 8.5l5 5M13.5 8.5l-5 5"/>
            </svg>
        </div>

        <div class="error-code">
            4<span>0</span>4
        </div>

        <h1 class="error-title" data-translate="error-404-title">
            Halaman Tidak Ditemukan
        </h1>

        <p class="error-desc" data-translate="error-404-desc">
            Halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.<br>
            Periksa kembali alamat URL atau kembali ke beranda.
        </p>

    </div>
</div>
@endsection