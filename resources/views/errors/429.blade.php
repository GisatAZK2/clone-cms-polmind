@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fce7f3;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
        </div>

        <div class="error-code" style="color:#db2777;">
            4<span style="color:#ec4899;">2</span>9
        </div>

        <h1 class="error-title" data-translate="error-429-title">
            Terlalu Banyak Permintaan
        </h1>

        <p class="error-desc" data-translate="error-429-desc">
            Anda mengirim terlalu banyak permintaan dalam waktu singkat.<br>
            Tunggu beberapa saat sebelum mencoba kembali.
        </p>

    </div>
</div>
@endsection