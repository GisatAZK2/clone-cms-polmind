@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-window"></i> <span data-translate="popup.list">Daftar Popup</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">

    {{-- Header Row --}}
    <div class="row mb-3 mb-md-4 align-items-center">
        <div class="col-12 d-flex justify-content-between align-items-center">
            @if($popups->count() == 0)
                <a href="{{ route('admin.popup.create') }}" class="btn btn-primary btn-sm ms-auto">
                    <i class="bi bi-plus-circle me-1"></i> <span data-translate="popup.addNew">Tambah Popup Baru</span>
                </a>
            @endif
        </div>
    </div>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.popup.bulk-destroy') }}"
        method="POST"
        data-confirm-key="popup.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus popup yang dipilih?">
        @csrf
    </form>

    @if($popups->count() > 0)
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

    {{-- Main Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header  text-white bg-white border-bottom d-flex align-items-center justify-content-between py-3 px-3 px-md-4">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-window me-2"></i>
                <span data-translate="popup.list">Daftar Popup</span>
            </h6>
            @if($popups->count() > 0)
                <span class="badge bg-primary rounded-pill">{{ $popups->count() }}</span>
            @endif
        </div>

        <div class="card-body p-0">

            @if($popups->count() > 0)

                {{-- Desktop Table --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="ps-4">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="25%" data-translate="popup.imageCol">Gambar</th>
                                <th width="35%" data-translate="popup.contentCol">Konten</th>
                                <th width="15%" data-translate="popup.altCol">Alt Text</th>
                                <th width="15%" class="text-center" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popups as $index => $item)
                                <tr>
                                    <td class="ps-4">
                                        <input
                                            type="checkbox"
                                            name="ids[]"
                                            value="{{ $item->id }}"
                                            class="form-check-input bulk-item-checkbox"
                                            form="bulkDeleteForm"
                                        >
                                    </td>
                                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($item->url_image)
                                            <img class="preview-image rounded-2"
                                                 src="{{ asset('storage/' . $item->url_image) }}"
                                                 alt="{{ $item->alt }}"
                                                 style="width: 72px; height: 56px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit(strip_tags($item->content), 60) }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->alt ?: '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.popup.edit', $item->id) }}"
                                               class="btn btn-info btn-sm text-white px-3"
                                               title="Edit">
                                                <i class="bi bi-pencil me-1"></i>
                                            </a>
                                            <form action="{{ route('admin.popup.destroy', $item->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                class="confirm-delete-form"
                                                data-confirm-key="popup.confirmDelete"
                                                data-confirm-fallback="Yakin ingin menghapus popup ini?">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm px-3" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        <span data-translate="popup.noData">Tidak ada popup</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="d-md-none px-3 py-3">
                    @forelse($popups as $index => $item)
                        <div class="popup-card rounded-3 border mb-3 overflow-hidden">
                            <div class="p-2 border-bottom">
                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $item->id }}"
                                    class="form-check-input bulk-item-checkbox"
                                    form="bulkDeleteForm"
                                >
                            </div>

                            {{-- Image --}}
                            @if($item->url_image)
                                <div class="popup-card-img-wrap">
                                    <img src="{{ asset('storage/' . $item->url_image) }}"
                                         alt="{{ $item->alt }}"
                                         class="preview-image w-100"
                                         style="height: 160px; object-fit: cover; display: block;">
                                </div>
                            @else
                                <div class="popup-card-img-placeholder d-flex align-items-center justify-content-center bg-light"
                                     style="height: 100px;">
                                    <i class="bi bi-image text-muted fs-3"></i>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="p-3">
                                {{-- Nomor urut --}}
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">#{{ $loop->iteration }}</span>
                                    @if($item->alt)
                                        <small class="text-muted text-truncate" style="max-width: 220px;">
                                            <i class="bi bi-tag me-1"></i>{{ $item->alt }}
                                        </small>
                                    @endif
                                </div>

                                {{-- Konten --}}
                                @if($item->content)
                                    <p class="text-muted mb-3" style="font-size: 0.8rem; line-height: 1.5;">
                                        {{ Str::limit(strip_tags($item->content), 100) }}
                                    </p>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.popup.edit', $item->id) }}"
                                       class="btn btn-info btn-sm text-white flex-grow-1">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.popup.destroy', $item->id) }}"
                                          method="POST"
                                          style="display:inline; flex-grow: 1;"
                                          onsubmit="return confirm(AdminTranslate.t('popup.confirmDelete') || 'Yakin ingin menghapus popup ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="bi bi-trash-fill me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            <span data-translate="popup.noData">Tidak ada popup</span>
                        </div>
                    @endforelse
                </div>

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

<style>
    /* ========================
       Mobile Card Styles
    ======================== */
    .popup-card {
        background: #fff;
        transition: box-shadow 0.2s ease;
    }

    .popup-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .popup-card:last-child {
        margin-bottom: 0 !important;
    }

    .bulk-item-checkbox {
        cursor: pointer;
    }

    /* ========================
       Dark Theme
    ======================== */
    [data-theme="dark"] .card,
    [data-theme="dark"] .popup-card {
        background-color: #2d2d2d;
        color: #f0f0f0;
        border-color: #444 !important;
    }

    [data-theme="dark"] .card-header {
        background-color: #2d2d2d !important;
        border-bottom-color: #444 !important;
    }

    [data-theme="dark"] .table {
        color: #f0f0f0;
        --bs-table-hover-bg: rgba(255, 255, 255, 0.04);
    }

    [data-theme="dark"] .table-light thead {
        background-color: #3a3a3a !important;
        color: #ccc;
    }

    [data-theme="dark"] .text-muted {
        color: #aaa !important;
    }

    [data-theme="dark"] .popup-card-img-placeholder {
        background-color: #3a3a3a !important;
    }

    [data-theme="dark"] .badge.bg-secondary {
        background-color: #555 !important;
    }

    /* ========================
       Responsive Tweaks
    ======================== */
    @media (max-width: 575.98px) {
        .popup-card .btn {
            font-size: 0.78rem;
        }
    }
</style>
@endsection