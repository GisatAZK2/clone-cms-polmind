@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <div class="error-code" style="color:#d97706;">
            5<span style="color:#f59e0b;">0</span>4
        </div>

        <h1 class="error-title" data-translate="error-504-title">
            Gateway Timeout
        </h1>

        <p class="error-desc" data-translate="error-504-desc">
            Server tidak merespons dalam waktu yang ditentukan.<br>
            Koneksi mungkin lambat atau server sedang sibuk. Coba lagi.
        </p>

    </div>
</div>
@endsection