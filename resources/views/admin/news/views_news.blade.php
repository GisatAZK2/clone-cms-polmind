@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-newspaper"></i> <span data-translate="news.list">Daftar Berita</span>
@endsection

@section('content')
@php
    $isSuperadmin = auth()->user()?->role === 'superadmin';

    function news_content_type_translate_key($type)
    {
        return match ($type) {
            'Umum' => 'news.categoryGeneral',
            'Prestasi' => 'news.categoryAchievement',
            'Kerjasama' => 'news.categoryCollaboration',
            default => 'news.previewUntitled',
        };
    }

    function news_status_translate_key($status)
    {
        return match ($status) {
            'published' => 'common.published',
            'draft' => 'common.draft',
            default => 'news.previewUntitled',
        };
    }
@endphp
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="news.addNew">Tambah Berita Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6 d-flex flex-column flex-md-row justify-content-md-end gap-2">
             @if($news->count() > 0)
                <button
                    type="submit"
                    form="bulkDeleteForm"
                    id="bulkDeleteBtn"
                    class="btn btn-danger btn-sm w-100 w-md-auto"
                    disabled
                >
                    <i class="bi bi-trash3"></i>
                    <span data-translate="common.bulkDelete">Hapus Massal</span>
                </button>
            @endif
            @if($isSuperadmin)
            <button id="saveNewsOrderBtn" type="button" class="btn btn-success w-100 w-md-auto">
                <i class="bi bi-save"></i> <span data-translate="common.saveOrder">Simpan Urutan</span>
            </button>
            @endif  
            <form action="{{ route('admin.news.index') }}" method="GET" class="d-flex gap-2 flex-grow-1 flex-md-grow-0">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    data-translate-placeholder="news.search"
                    placeholder="Cari berita..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    @if($isSuperadmin)
    <form id="newsOrderForm" action="{{ route('admin.news.reorder') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="order" id="newsOrderInput" value="">
    </form>
    @endif
    
   <form
        id="bulkDeleteForm"
        action="{{ route('admin.news.bulk-destroy') }}"
        method="POST"
        data-confirm-key="news.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus berita yang dipilih? Semua gambar terkait akan ikut terhapus.">
        @csrf
    </form>
    
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white border-bottom rounded-top" style="background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);">
                <h5 class="mb-0"><i class="bi bi-newspaper"></i> <span data-translate="news.list">Daftar Berita</span></h5>
            </div>
        <div class="card-body p-0">
            @if($news->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table id="newsReorderTable" class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="4%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                @if($isSuperadmin)
                                    <th width="8%" data-translate="news.order">Urutan</th>
                                @endif
                                <th width="23%"><span data-translate="news.titleCol">Judul</span></th>
                                <th width="15%" data-translate="news.contentType">Jenis Konten</th>
                                <th width="18%"><span data-translate="news.content">Konten</span></th>
                                <th width="12%"><span data-translate="news.status">Status</span></th>
                                <th width="15%"><span data-translate="news.publishDate">Tanggal Publikasi</span></th>
                                <th width="15%"><span data-translate="news.createdby">Dibuat Oleh</span></th>
                                <th width="15%"><span data-translate="news.changedby">Diubah Oleh</span></th>
                                <th width="10%"><span data-translate="news.action">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($news as $index => $item)
                               <tr class="{{ $isSuperadmin ? 'news-order-row' : '' }}" data-id="{{ $item->id }}" @if($isSuperadmin) draggable="true" @endif>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="ids[]"
                                            value="{{ $item->id }}"
                                            class="form-check-input bulk-item-checkbox"
                                            form="bulkDeleteForm"
                                        >
                                    </td>
                                    <td class="news-number">
                                        {{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}
                                    </td>
                                    @if($isSuperadmin)
                                    <td class="text-center align-middle">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <button type="button" class="btn btn-sm btn-order btn-move-up" aria-label="Pindah ke atas">
                                                <i class="bi bi-arrow-up"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-order btn-move-down" aria-label="Pindah ke bawah">
                                                <i class="bi bi-arrow-down"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-1">#{{ $item->urutan ?: ($index + 1) }}</small>
                                    </td>
                                    @endif
                                    <td>
                                    <strong>
                                        @if(!empty($item->content['title']))
                                            {{ $item->content['title'] }}
                                        @else
                                            <span data-translate="news.previewUntitled">Untitled</span>
                                        @endif
                                    </strong>
                                    </td>
                                    <td>
                                        @php
                                            $jenisContent = $item->jenis_content ?? 'Untitled';
                                            $jenisContentKey = news_content_type_translate_key($jenisContent);
                                        @endphp

                                        <strong data-translate="{{ $jenisContentKey }}">
                                            {{ $jenisContent }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ count($item->content['blocks'] ?? []) }} <span data-translate="news.blocks">blok konten</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusKey = news_status_translate_key($item->status);
                                            $statusText = $item->status === 'published' ? 'Terbit' : 'Draft';
                                        @endphp

                                        <span class="badge bg-{{ $item->status === 'published' ? 'success' : 'warning' }}"
                                            data-translate="{{ $statusKey }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>{{ $item->published_at
    ? $item->published_at->timezone(config('app.timezone'))->format('d M Y H:i') . ' WIB'
    : '-' }}</td>
                                    <td>
                                        @if(!empty($item->author))
                                            {{ $item->author }}
                                        @else
                                            <span data-translate="common.unknown">Unknown</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->changed_by ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                          <form action="{{ route('admin.news.destroy', $item->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                class="confirm-delete-form"
                                                data-confirm-key="news.confirmDelete"
                                                data-confirm-fallback="Yakin ingin menghapus berita ini? Semua gambar terkait akan ikut terhapus.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                   <td colspan="{{ $isSuperadmin ? 11 : 10 }}" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> <span data-translate="news.noData">Tidak ada berita</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div id="newsMobileList" class="d-md-none">
                    @forelse($news as $index => $item)
                        <div class="card border-0 mb-3 news-mobile-card {{ $isSuperadmin ? 'news-order-card' : '' }}" data-id="{{ $item->id }}" >
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-1"
                                        form="bulkDeleteForm"
                                    >
                                    <div class="flex-grow-1">
                                        <div class="fw-bold news-mobile-title mb-1">
                                            @if(!empty($item->content['title']))
                                                {{ $item->content['title'] }}
                                            @else
                                                <span data-translate="news.previewUntitled">Untitled</span>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                                            <span class="badge bg-secondary news-mobile-order">#{{ $item->urutan ?: ($index + 1) }}</span>
                                            @php
                                                $mobileJenisContent = $item->jenis_content ?? 'Umum';
                                                $mobileJenisContentKey = news_content_type_translate_key($mobileJenisContent);
                                            @endphp

                                            <span class="badge bg-info text-dark news-mobile-jenis"
                                                data-translate="{{ $mobileJenisContentKey }}">
                                                {{ $mobileJenisContent }}
                                            </span>
                                            <span class="badge bg-light text-dark news-mobile-blocks">
                                                {{ count($item->content['blocks'] ?? []) }}
                                                <span data-translate="news.blocks">blok konten</span>
                                            </span>
                                        </div>
                                    </div>
                                    @php
                                        $mobileStatusKey = news_status_translate_key($item->status);
                                        $mobileStatusText = $item->status === 'published' ? 'Terbit' : 'Draft';
                                    @endphp

                                    <span class="badge bg-{{ $item->status === 'published' ? 'success' : 'warning' }} ms-2 news-mobile-status"
                                        data-translate="{{ $mobileStatusKey }}">
                                        {{ $mobileStatusText }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2 news-mobile-actions">
                                    @if($isSuperadmin)
                                    <button type="button" class="btn btn-sm btn-order btn-move-up rounded-circle p-1" aria-label="Pindah ke atas">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-order btn-move-down rounded-circle p-1" aria-label="Pindah ke bawah">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                    @endif
                                    <span class="text-muted small ms-auto news-mobile-date"><i class="bi bi-calendar"></i> {{ $item->published_at
                                        ? $item->published_at->timezone(config('app.timezone'))->format('d M Y H:i') . ' WIB'
                                        : '-' }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-primary btn-sm flex-grow-1 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-pencil me-1"></i> <span data-translate="common.edit">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item->id) }}"
                                        method="POST"
                                        class="flex-grow-1 confirm-delete-form"
                                        data-confirm-key="news.confirmDelete"
                                        data-confirm-fallback="Yakin ingin menghapus berita ini? Semua gambar terkait akan ikut terhapus.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-trash me-1"></i> <span data-translate="common.delete">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info m-3">
                            <i class="bi bi-inbox"></i> <span data-translate="news.noData">Tidak ada berita</span>
                        </div>
                    @endforelse
                </div>
                
                @if($news->hasPages())
                    <nav aria-label="Page navigation" class="mt-3 mb-0">
                        {{ $news->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            @else
                <div class="empty">
                    <i class="bi bi-inbox"></i> 
                    <p>
                        <span data-translate="news.noData">Tidak ada berita</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>

    /* Mobile News Card Customization */
    @media (max-width: 768px) {
        .news-mobile-card {
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            margin: 0 0.1rem 1rem 0.1rem;
            border: none;
            background: #23272f;
        }
        .news-mobile-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }
        .news-mobile-order {
            background: #475569 !important;
            color: #fff !important;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .news-order-card {
            touch-action: pan-y;
            user-select: none;
            transition: transform 0.18s ease, opacity 0.18s ease, box-shadow 0.18s ease;
        }

        .news-order-card.is-touching {
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
            z-index: 5;
        }

        .news-order-card.order-changed {
            outline: 2px solid #2563eb;
            background: rgba(37, 99, 235, 0.08) !important;
        }

        .bulk-item-checkbox {
            cursor: pointer;
        }

        .news-mobile-card .bulk-item-checkbox {
            flex-shrink: 0;
        }

        [data-theme="dark"] .news-order-card.order-changed {
            background: rgba(59, 130, 246, 0.16) !important;
        }
        .news-mobile-jenis {
            background: #38bdf8 !important;
            color: #1e293b !important;
            font-size: 0.8rem;
        }
        .news-mobile-blocks {
            background: #f1f5f9 !important;
            color: #334155 !important;
            font-size: 0.8rem;
        }
        .news-mobile-status {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }
        .news-mobile-card .btn {
            font-size: 0.95rem;
            border-radius: 0.5rem;
        }
        .news-mobile-card .btn-primary {
            background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
            border: none;
        }
        .news-mobile-card .btn-danger {
            background: #ef4444;
            border: none;
        }
        .news-mobile-card .btn-outline-secondary {
            border: 1px solid #64748b;
            color: #64748b;
            background: #fff;
        }
        .news-mobile-card .btn-outline-secondary:hover {
            background: #e0e7ef;
        }
        .news-mobile-card .btn svg,
        .news-mobile-card .btn i {
            font-size: 1.1rem;
        }
        .news-mobile-card .btn-move-up,
        .news-mobile-card .btn-move-down {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .news-mobile-card .btn-move-up:active,
        .news-mobile-card .btn-move-down:active {
            background: #dbeafe;
        }
        .news-mobile-card .btn-primary:active {
            background: #1e40af;
        }
        .news-mobile-card .btn-danger:active {
            background: #b91c1c;
        }
        .news-mobile-card .btn:focus {
            box-shadow: 0 0 0 2px #2563eb33;
        }
        .news-mobile-card .text-muted {
            color: #cbd5e1 !important;
        }
    }

    @media (max-width: 576px) {
        .news-mobile-card {
            padding: 0.5rem !important;
        }
        .news-mobile-title {
            font-size: 1rem;
        }
        .news-mobile-card .btn {
            font-size: 0.9rem;
        }
        .news-mobile-status {
            font-size: 0.8rem;
        }
    }

    .news-order-row {
        cursor: grab;
    }
    .btn-order {
    background: #fff !important;
    color: #000 !important;
    border: 1px solid #ced4da !important;
}

[data-theme="dark"] .btn-order {
    background: #2d2d2d !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}

    .news-order-row.drag-over {
        outline: 2px dashed #0d6efd;
        background-color: rgba(13, 110, 253, 0.08);
    }

    .news-order-card {
        position: relative;
    }

    /* Dark theme */
    [data-theme="dark"] .card {
        background-color: #2d2d2d;
        color: #f0f0f0;
        border: none;
    }

    [data-theme="dark"] .card-header {
        background-color: #3a3a3a !important;
        color: #f0f0f0;
        border-bottom-color: #444;
    }

    [data-theme="dark"] .card-body {
        background-color: #2d2d2d;
        color: #f0f0f0;
    }

    [data-theme="dark"] .table-hover tbody tr:hover {
        background-color: #3a3a3a;
    }

    [data-theme="dark"] .table {
        color: #f0f0f0;
        border-color: #444;
    }

    [data-theme="dark"] .table thead {
        border-bottom-color: #444;
    }

    [data-theme="dark"] .table-light {
        background-color: #3a3a3a;
    }

    [data-theme="dark"] .table-light th {
        color: #f0f0f0;
        border-bottom-color: #444;
    }

    [data-theme="dark"] .alert-info {
        background-color: #3a3a3a;
        color: #f0f0f0;
        border-color: #555;
    }

    [data-theme="dark"] .text-muted {
        color: #999 !important;
    }

    [data-theme="dark"] .card.border-bottom {
        border-bottom-color: #444 !important;
    }
</style>
@endsection

