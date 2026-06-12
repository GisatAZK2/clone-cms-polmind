@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-speedometer2"></i> <span data-translate="dashboard.title">Dashboard</span>
@endsection

@section('content')
<div class="dash-wrap px-2 px-md-3">

    @php
        $authUser = auth()->user();
        $isSuperadmin = $authUser->role === 'superadmin';
    @endphp

    {{-- ── Stat Cards ── --}}
    <div class="stat-grid {{ $isSuperadmin ? 'stat-grid--five' : 'stat-grid--auto' }}">

        <div class="stat-card">
            <div class="stat-icon stat-icon--navy">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label" data-translate="dashboard.totalNews">Total Berita</span>
                <span class="stat-value">{{ $totalNews ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.news.index') }}" class="stat-link" title="Lihat berita">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($isSuperadmin)
        <div class="stat-card">
            <div class="stat-icon stat-icon--blue">
                <i class="bi bi-megaphone"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label" data-translate="dashboard.totalHeadlines">Total Headline</span>
                <span class="stat-value">{{ $totalHeadlines ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.headline.index') }}" class="stat-link" title="Lihat headline">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--slate">
                <i class="bi bi-window"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label" data-translate="dashboard.totalPopup">Total Popup</span>
                <span class="stat-value">{{ $totalPopup ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.popup.index') }}" class="stat-link" title="Lihat popup">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--steel">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label" data-translate="dashboard.totalUser">Total User</span>
                <span class="stat-value">{{ $totalUsers ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.users.index') }}" class="stat-link" title="Lihat user">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--navy">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label" data-translate="dashboard.totalDosen">Total Dosen</span>
                <span class="stat-value">{{ $totalDosen ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.dosen.index') }}" class="stat-link" title="Lihat dosen">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        @endif

    </div>

    {{-- ── Main content grid ── --}}
    <div class="dash-grid">

        {{-- Berita Terbaru --}}
        <div class="panel panel-news">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-newspaper"></i> <span data-translate="dashboard.latestNews">Berita Terbaru</span></span>
                <a href="{{ route('admin.news.index') }}" class="panel-action" data-translate="dashboard.viewAll">Lihat semua</a>
            </div>
            <div class="panel-body">
                @if(isset($latestNews) && $latestNews->count() > 0)
                    <ul class="item-list">
                        @foreach($latestNews as $news)
                        <li class="item-row">
                            <div class="item-info">
                                <span class="item-title">{{ $news->getTitle() }}</span>
                                <span class="item-meta">{{ $news->published_at->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="pill {{ $news->status === 'published' ? 'pill--green' : 'pill--amber' }}">
                                {{ $news->status === 'published' ? 'Terbit' : 'Draft' }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <span data-translate="dashboard.noNews">Belum ada berita</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Headline Terbaru --}}
        @if($isSuperadmin)
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-megaphone"></i> <span data-translate="dashboard.latestHeadlines">Headline Terbaru</span></span>
                <a href="{{ route('admin.headline.index') }}" class="panel-action" data-translate="dashboard.viewAll">Lihat semua</a>
            </div>
            <div class="panel-body">
                @if(isset($latestHeadlines) && $latestHeadlines->count() > 0)
                    <ul class="item-list">
                        @foreach($latestHeadlines as $headline)
                        <li class="item-row">
                            <div class="item-info">
                                {{--
                                    Judul headline disimpan sebagai HTML dari TinyMCE.
                                    Gunakan strip_tags() agar teks bersih tanpa tag HTML
                                    saat ditampilkan di dashboard list.
                                --}}
                                <span class="item-title">
                                    {{
                                        Str::limit(
                                            preg_replace(
                                                '/\s+/',
                                                ' ',
                                                trim(
                                                    html_entity_decode(
                                                        strip_tags($headline->title ?? 'No Title')
                                                    )
                                                )
                                            ),
                                            80
                                        )
                                    }}
                                </span>
                                <span class="item-meta">{{ $headline->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <span data-translate="dashboard.noHeadline">Belum ada headline</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Informasi Sistem --}}
        <div class="panel panel--full">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-info-circle"></i> <span data-translate="dashboard.sysInfo">Informasi Sistem</span></span>
            </div>
            <div class="panel-body">
                <div class="sysinfo-grid">
                    <div class="sysinfo-row">
                        <span class="sysinfo-key" data-translate="dashboard.admin">Admin</span>
                        <span class="sysinfo-val">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="sysinfo-row">
                        <span class="sysinfo-key" data-translate="dashboard.email">Email</span>
                        <span class="sysinfo-val">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="sysinfo-row">
                        <span class="sysinfo-key" data-translate="dashboard.lastLogin">Terakhir Login</span>
                        <span class="sysinfo-val">{{ \Carbon\Carbon::parse(auth()->user()->updated_at)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="sysinfo-row">
                        <span class="sysinfo-key" data-translate="dashboard.appVersion">Versi Aplikasi</span>
                        <span class="sysinfo-val">1.0</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        .dash-wrap {
            padding: 0.75rem 0.5rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-card {
            padding: 0.75rem;
            flex-direction: row;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .stat-body {
            flex: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .stat-link {
            display: none;
        }

        .dash-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .panel {
            margin: 0;
            padding: 0;
            border-radius: 0.375rem;
        }

        .panel-head {
            padding: 0.75rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .panel-title {
            font-size: 0.9rem;
        }

        .panel-action {
            align-self: flex-end;
            font-size: 0.75rem;
            margin-top: -1.5rem;
        }

        .panel-body {
            padding: 0.75rem;
        }

        .item-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .item-row {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-info {
            width: 100%;
        }

        .item-title {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .item-meta {
            font-size: 0.7rem;
            color: #999;
        }

        .pill {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            align-self: flex-start;
        }

        .sysinfo-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .sysinfo-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .sysinfo-row:last-child {
            border-bottom: none;
        }

        .sysinfo-key {
            font-size: 0.75rem;
            font-weight: 600;
            color: #666;
        }

        .sysinfo-val {
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
        }

        .empty-state {
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 2rem;
        }

        .empty-state span {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 0.5rem;
        }

        .stat-icon {
            width: 2rem;
            height: 2rem;
            font-size: 0.875rem;
        }

        .stat-value {
            font-size: 1rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }

        .panel-title {
            font-size: 0.8rem;
        }

        .item-title {
            font-size: 0.75rem;
        }

        .item-meta {
            font-size: 0.6rem;
        }
    }

    /* ── Dark theme support ── */
    [data-theme="dark"] .stat-card {
        background-color: #2d2d2d;
        color: #f0f0f0;
    }

    /* stat-value: teks angka yang mungkin inherit warna biru navy dari light mode */
    [data-theme="dark"] .stat-value {
        color: #f0f0f0;
    }

    /* stat-label: teks kecil di bawah angka */
    [data-theme="dark"] .stat-label {
        color: #aaa;
    }

    /* stat-link: tombol panah kanan */
    [data-theme="dark"] .stat-link {
        color: #7aadff;
    }
    [data-theme="dark"] .stat-link:hover {
        color: #aecfff;
    }

    [data-theme="dark"] .panel {
        background-color: #2d2d2d;
        color: #f0f0f0;
    }

    [data-theme="dark"] .panel-head {
        background-color: #3a3a3a;
        border-bottom-color: #444;
    }

    /* panel-title: heading tiap panel */
    [data-theme="dark"] .panel-title {
        color: #f0f0f0;
    }

    /* panel-action: link "Lihat semua" — dari biru gelap → biru muda */
    [data-theme="dark"] .panel-action {
        color: #7aadff !important;
    }
    [data-theme="dark"] .panel-action:hover {
        color: #aecfff !important;
    }

    [data-theme="dark"] .item-row,
    [data-theme="dark"] .sysinfo-row {
        border-bottom-color: #444;
    }

    [data-theme="dark"] .sysinfo-key {
        color: #aaa;
    }

    [data-theme="dark"] .sysinfo-val {
        color: #f0f0f0;
    }

    [data-theme="dark"] .item-meta {
        color: #777;
    }

    [data-theme="dark"] .item-title {
        color: #f0f0f0;
    }

    /* empty-state teks di panel kosong */
    [data-theme="dark"] .empty-state {
        color: #888;
    }
    [data-theme="dark"] .empty-state i {
        color: #666;
    }

    /* pill badges tetap terbaca di dark mode */
    [data-theme="dark"] .pill--green {
        filter: brightness(0.85);
    }
    [data-theme="dark"] .pill--amber {
        filter: brightness(0.85);
    }
</style>
@endsection