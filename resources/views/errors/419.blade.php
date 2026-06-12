@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#ede9fe;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <div class="error-code" style="color:#7c3aed;">
            4<span style="color:#8b5cf6;">1</span>9
        </div>

        <h1 class="error-title" data-translate="error-419-title">
            Sesi Telah Berakhir
        </h1>

        <p class="error-desc" data-translate="error-419-desc">
            Halaman ini sudah kadaluarsa karena tidak ada aktivitas.<br>
            Muat ulang halaman untuk melanjutkan.
        </p>
        
    </div>
</div>
@endsection