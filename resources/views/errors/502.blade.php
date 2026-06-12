@extends('pages.layouts.app')

@section('content')
<div class="error-page">
    <div class="error-card">

        <div class="error-icon-wrap" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>

        <div class="error-code" style="color:#dc2626;">
            5<span style="color:#ef4444;">0</span>2
        </div>

        <h1 class="error-title" data-translate="error-502-title">
            Gateway Bermasalah
        </h1>

        <p class="error-desc" data-translate="error-502-desc">
            Server menerima respons tidak valid dari server upstream.<br>
            Masalah ini bersifat sementara, silakan coba beberapa saat lagi.
        </p>

    </div>
</div>
@endsection