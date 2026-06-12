@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <div class="error-code" style="color:#d97706;">
            4<span style="color:#f59e0b;">0</span>3
        </div>

        <h1 class="error-title" data-translate="error-403-title">
            Akses Ditolak
        </h1>

        <p class="error-desc" data-translate="error-403-desc">
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Silakan masuk dengan akun yang sesuai atau hubungi administrator.
        </p>

    </div>
</div>
@endsection