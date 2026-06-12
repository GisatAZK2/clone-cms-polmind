@extends('admin.layout.layout')

@section('style')
<style>
    .home-stat-list-page {
        max-width: 100%;
    }

    .home-stat-toolbar .btn,
    .home-stat-toolbar .form-control {
        min-height: 38px;
    }

    .home-stat-list-card {
        overflow: hidden;
    }

    .home-stat-list-card .btn-group .btn,
    .home-stat-list-card .btn-group form .btn {
        width: 32px;
        height: 32px;
        padding: 0 !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px !important;
    }

    .home-stat-mobile-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 12px;
    }

    .home-stat-mobile-card {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px;
        box-shadow: var(--shadow-xs);
    }

    .home-stat-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .home-stat-mobile-head .min-w-0 {
        min-width: 0;
    }

    .home-stat-mobile-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--icon-blue-bg);
        color: var(--icon-blue-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .home-stat-mobile-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.35;
        word-break: break-word;
    }

    .home-stat-mobile-subtitle {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .home-stat-mobile-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding: 10px 0;
    }

    .home-stat-mobile-info {
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 10px;
        min-width: 0;
    }

    .home-stat-mobile-info span {
        display: block;
        font-size: 0.68rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 3px;
    }

    .home-stat-mobile-info strong {
        display: block;
        font-size: 0.9rem;
        color: var(--text-heading);
        line-height: 1.25;
        word-break: break-word;
    }

    .home-stat-mobile-actions {
        display: flex;
        gap: 8px;
        padding-top: 10px;
        border-top: 1px solid var(--border);
    }

    .home-stat-mobile-actions .btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
    }

    @media (max-width: 767.98px) {
        .home-stat-list-page {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .home-stat-toolbar {
            margin-bottom: 12px !important;
        }

        .home-stat-toolbar .btn,
        .home-stat-toolbar .form-control {
            min-height: 36px;
            font-size: 0.8rem !important;
        }

        .home-stat-toolbar form {
            width: 100%;
        }

        .home-stat-list-page > .d-flex.justify-content-end {
            justify-content: stretch !important;
            margin-bottom: 12px !important;
        }

        .home-stat-list-page > .d-flex.justify-content-end #bulkDeleteBtn {
            width: 100%;
            min-height: 36px;
            font-size: 0.78rem !important;
        }

        .home-stat-list-card .card-header {
            padding: 12px 14px !important;
        }

        .home-stat-list-card .card-header h5 {
            font-size: 0.82rem !important;
            line-height: 1.3;
        }

        .home-stat-list-card .card-body {
            padding: 0 !important;
        }
    }

    @media (max-width: 380px) {
        .home-stat-mobile-body {
            grid-template-columns: 1fr;
        }

        .home-stat-mobile-actions {
            flex-direction: column;
        }
    }

    
</style>
@endsection

@section('page-title')
    <i class="bi bi-graph-up"></i> <span data-translate="homeStat.title">Statistik Beranda</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 home-stat-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 home-stat-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.home-stat.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="homeStat.addNew">Tambah Statistik Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.home-stat.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control"
                       data-translate-placeholder="homeStat.search"
                       placeholder="Cari label atau nilai..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.home-stat.bulk-destroy') }}"
        method="POST"
        data-confirm-key="homeStat.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus statistik yang dipilih?">
        @csrf
    </form>

    @if($stats->count() > 0)
        <div class="d-flex justify-content-end mb-3">
            <button
                type="submit"
                form="bulkDeleteForm"
                id="bulkDeleteBtn"
                class="btn btn-danger btn-sm"
                disabled
            >
                <i class="bi bi-trash3"></i>
                <span data-translate="common.bulkDelete">Hapus Massal</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm home-stat-list-card">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-graph-up"></i>
                <span data-translate="homeStat.list">Daftar Statistik Beranda</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($stats->count() > 0)
               <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="selectAllItems" class="form-check-input">
                            </th>
                            <th width="5%" data-translate="common.number">#</th>
                            <th width="10%" data-translate="homeStat.colIcon">Icon</th>
                            <th data-translate="homeStat.colLabel">Label</th>
                            <th data-translate="homeStat.colValue">Value</th>
                            <th width="15%" data-translate="homeStat.colOrder">Urutan</th>
                            <th width="10%" data-translate="homeStat.colStatus">Status</th>
                            <th width="12%" data-translate="common.action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats as $index => $stat)
                            <tr>
                                <td data-label="Pilih">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $stat->id }}"
                                        class="form-check-input bulk-item-checkbox"
                                        form="bulkDeleteForm"
                                    >
                                </td>
                                <td data-label="#">{{ ($stats->currentPage() - 1) * $stats->perPage() + $loop->iteration }}</td>
                                <td data-label="Icon">
                                    @if($stat->icon)
                                        <i class="{{ $stat->icon }} fs-4"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td data-label="Label"><strong>{{ $stat->label }}</strong></td>
                                <td data-label="Value"><span class="badge bg-primary">{{ $stat->value }}</span></td>
                                <td data-label="Urutan">{{ $stat->order }}</td>
                                <td data-label="Status">
                                    @if($stat->is_active)
                                        <span class="badge bg-success" data-translate="homeStat.statusActive">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary" data-translate="homeStat.statusInactive">Nonaktif</span>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.home-stat.edit', $stat->id) }}" class="btn btn-info text-white" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.home-stat.destroy', $stat->id) }}"
                                            method="POST"
                                            style="display:inline;"
                                            class="confirm-delete-form"
                                            data-confirm-key="homeStat.confirmDelete"
                                            data-confirm-fallback="Yakin ingin menghapus statistik ini?">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <span data-translate="homeStat.noData">Tidak ada data statistik</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="home-stat-mobile-list d-md-none">
                @foreach($stats as $index => $stat)
                    <div class="home-stat-mobile-card">
                        <div class="home-stat-mobile-head">
                            <div class="d-flex align-items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $stat->id }}"
                                    class="form-check-input bulk-item-checkbox"
                                    form="bulkDeleteForm"
                                >

                                <div class="home-stat-mobile-icon">
                                    @if($stat->icon)
                                        <i class="{{ $stat->icon }}"></i>
                                    @else
                                        <i class="bi bi-bar-chart"></i>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <div class="home-stat-mobile-title">
                                        {{ $stat->label }}
                                    </div>
                                    <div class="home-stat-mobile-subtitle">
                                        #{{ ($stats->currentPage() - 1) * $stats->perPage() + $loop->iteration }}
                                    </div>
                                </div>
                            </div>

                            @if($stat->is_active)
                                <span class="badge bg-success" data-translate="homeStat.statusActive">Aktif</span>
                            @else
                                <span class="badge bg-secondary" data-translate="homeStat.statusInactive">Nonaktif</span>
                            @endif
                        </div>

                        <div class="home-stat-mobile-body">
                            <div class="home-stat-mobile-info">
                                <span data-translate="homeStat.colValue">Value</span>
                                <strong>{{ $stat->value }}</strong>
                            </div>

                            <div class="home-stat-mobile-info">
                                <span data-translate="homeStat.colOrder">Urutan</span>
                                <strong>{{ $stat->order }}</strong>
                            </div>
                        </div>

                        <div class="home-stat-mobile-actions">
                            <a href="{{ route('admin.home-stat.edit', $stat->id) }}"
                                class="btn btn-info btn-sm text-white">
                                <i class="bi bi-pencil"></i>
                                <span data-translate="common.edit">Edit</span>
                            </a>

                            <form action="{{ route('admin.home-stat.destroy', $stat->id) }}"
                                method="POST"
                                class="confirm-delete-form"
                                data-confirm-key="homeStat.confirmDelete"
                                data-confirm-fallback="Yakin ingin menghapus statistik ini?"
                                onsubmit="return confirm(window.AdminTranslate?.t?.('homeStat.confirmDelete') || 'Yakin ingin menghapus statistik ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-trash"></i>
                                    <span data-translate="common.delete">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>


                @if($stats->hasPages())
                    <div class="card-footer bg-light">
                        {{ $stats->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="empty">
                    <i class="bi bi-inbox"></i> 
                    <p>
                    <span data-translate="projects.noData">Tidak ada data</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection