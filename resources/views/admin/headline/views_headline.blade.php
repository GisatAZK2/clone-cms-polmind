@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-megaphone"></i> <span data-translate="headline.list">Daftar Headline</span>
@endsection

@section('content')
<div class="hl-wrapper">

    {{-- Top Action Bar --}}
    <div class="hl-action-bar">
        <a href="{{ route('admin.headline.create') }}" class="btn btn-primary hl-btn-add">
            <i class="bi bi-plus-circle"></i>
            <span data-translate="headline.addNew">Tambah Headline Baru</span>
        </a>

        <div class="hl-save-wrap">
                @if($headlines->count() > 0)
                    <button
                        type="submit"
                        form="bulkDeleteForm"
                        id="bulkDeleteBtn"
                        class="btn btn-danger hl-btn-save"
                        disabled
                    >
                        <i class="bi bi-trash3"></i>
                        <span data-translate="common.bulkDelete">Hapus Massal</span>
                    </button>
                @endif

                <button id="saveHeadlineOrderBtn" type="button" class="btn btn-success hl-btn-save">
                    <i class="bi bi-save"></i> <span data-translate="common.saveOrder">Simpan Urutan</span>
                </button>

                <small class="hl-hint" data-translate="headline.orderHint">
                    Geser baris atau gunakan tombol panah untuk mengubah urutan, lalu simpan.
                </small>
        </div>
    </div>

    <form id="headlineOrderForm" action="{{ route('admin.headline.reorder') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="order" id="headlineOrderInput" value="">
    </form>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.headline.bulk-destroy') }}"
        method="POST"
        class="d-none"
        data-confirm-key="headline.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus headline yang dipilih?">
        @csrf
    </form>

    <div class="hl-card">
        <div class="hl-card-header">
            <i class="bi bi-megaphone"></i>
            <span data-translate="headline.list">Daftar Headline</span>
        </div>

        @if($headlines->count() > 0)

            {{-- ===== DESKTOP TABLE ===== --}}
            <div class="table-responsive d-none d-md-block">
                <table id="headlineReorderTable" class="table table-hover mb-0">
                    <thead class="table-light">
                       <tr>
                            <th style="width:4%">
                                <input type="checkbox" id="selectAllItems" class="form-check-input">
                            </th>
                            <th style="width:4%">#</th>
                            <th style="width:8%" data-translate="headline.order">Urutan</th>
                            <th style="width:11%" data-translate="headline.image">Gambar</th>
                            <th style="width:40%" data-translate="headline.title">Judul</th>
                            <th style="width:9%" data-translate="headline.status">Status</th>
                            <th style="width:9%" data-translate="common.createdDate">Tanggal</th>
                            <th style="width:15%" data-translate="common.action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($headlines as $index => $item)
                            <tr class="headline-order-row" data-id="{{ $item->id }}" draggable="true">
                                <td class="align-middle">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox"
                                        form="bulkDeleteForm"
                                    >
                                </td>
                                <td class="align-middle headline-number">
                                    {{ ($headlines->currentPage() - 1) * $headlines->perPage() + $loop->iteration }}
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-move-up" aria-label="Pindah ke atas">
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-move-down" aria-label="Pindah ke bawah">
                                            <i class="bi bi-arrow-down"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">#{{ $item->urutan ?: ($index + 1) }}</small>
                                </td>
                                <td class="align-middle">
                                    @if($item->url_image)
                                        <img src="{{ asset($item->url_image) }}" 
                                            alt="{{ $item->alt }}"
                                            style="width:80px;height:50px;object-fit:cover;border-radius:6px;cursor:pointer;"
                                            class="preview-image">
                                    @else
                                        <span class="badge bg-secondary">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="headline-tinymce-content">
                                        {!! $item->title ?? '<span class="text-muted">No Title</span>' !!}
                                    </div>
                                    @if($item->alt)
                                        <small class="text-muted">Alt: {{ $item->alt }}</small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($item->status == 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="align-middle">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.headline.edit', $item->id) }}" class="btn btn-info btn-sm text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.headline.destroy', $item->id) }}"
                                                method="POST"
                                                class="confirm-delete-form"
                                                data-confirm-key="headline.confirmDelete"
                                                data-confirm-fallback="Yakin ingin menghapus?">
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    <span data-translate="headline.noData">Tidak ada headline</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===== MOBILE CARD LIST ===== --}}
            <div id="headlineMobileList" class="d-md-none hl-mobile-list">
                @forelse($headlines as $index => $item)
                    <div class="hl-mobile-card headline-order-card" data-id="{{ $item->id }}">

                        {{-- Card Top: image + meta --}}
                        <div class="hl-mc-top">
                            <input
                                type="checkbox"
                                name="ids[]"
                                value="{{ $item->id }}"
                                class="form-check-input bulk-item-checkbox mt-2"
                                form="bulkDeleteForm"
                            >

                            @if($item->url_image)
                                <img src="{{ asset($item->url_image) }}" alt="{{ $item->alt }}" class="hl-mc-thumb preview-image">
                            @else
                                <div class="hl-mc-thumb-placeholder">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif

                            <div class="hl-mc-meta">
                                <div class="headline-tinymce-content hl-mc-title">
                                    {!! $item->title ?? '<span class="text-muted">No Title</span>' !!}
                                </div>
                                @if($item->alt)
                                    <small class="text-muted">Alt: {{ $item->alt }}</small>
                                @endif
                                <div class="hl-mc-badges">
                                    @if($item->status == 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Nonaktif</span>
                                    @endif
                                    <span class="badge bg-secondary">Urutan #{{ $item->urutan ?: ($index + 1) }}</span>
                                </div>
                                <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                            </div>
                        </div>

                        {{-- Card Bottom: order buttons + actions --}}
                        <div class="hl-mc-footer">
                            <div class="hl-mc-order-btns">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-move-up" aria-label="Pindah ke atas">
                                    <i class="bi bi-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-move-down" aria-label="Pindah ke bawah">
                                    <i class="bi bi-arrow-down"></i>
                                </button>
                            </div>
                            <div class="hl-mc-actions">
                                <a href="{{ route('admin.headline.edit', $item->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.headline.destroy', $item->id) }}"
                                        method="POST"
                                        class="confirm-delete-form"
                                        data-confirm-key="headline.confirmDelete"
                                        data-confirm-fallback="Yakin ingin menghapus?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="alert alert-info m-3">
                        <i class="bi bi-inbox"></i>
                        <span data-translate="headline.noData">Tidak ada headline</span>
                    </div>
                @endforelse
            </div>

            @if($headlines->hasPages())
                <div class="hl-pagination">
                    {{ $headlines->links('pagination::bootstrap-5') }}
                </div>
            @endif

        @else
            <div class="empty">
                <i class="bi bi-info-circle"></i>
                <p>
                <span data-translate="headline.noData">Tidak ada headline</span>
                </p>
            </div>
        @endif
    </div>
</div>

<style>
/* ============================================================
   WRAPPER & ACTION BAR
   ============================================================ */
.hl-wrapper {
    padding: 1rem;
    max-width: 100%;
    box-sizing: border-box;
}

@media (min-width: 768px) {
    .hl-wrapper { padding: 1.5rem 1.75rem; }
}

.hl-action-bar {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

@media (min-width: 768px) {
    .hl-action-bar {
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-between;
    }
}

.hl-btn-add,
.hl-btn-save {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.9rem;
    padding: 0.55rem 1rem;
    border-radius: 8px;
    white-space: nowrap;
}

@media (min-width: 768px) {
    .hl-btn-add,
    .hl-btn-save { width: auto; }
}

.hl-save-wrap {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

@media (min-width: 768px) {
    .hl-save-wrap { align-items: flex-end; }
}

.hl-hint {
    font-size: 0.75rem;
    color: #6c757d;
    line-height: 1.4;
}

/* ============================================================
   CARD CONTAINER
   ============================================================ */
.hl-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    overflow: hidden;
}

.hl-card-header {
    background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
    color: #fff;
    border-bottom: none;
    padding: 0.85rem 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.hl-pagination {
    padding: 0.75rem 1rem 1rem;
}

/* ============================================================
   MOBILE CARD LIST
   ============================================================ */
.hl-mobile-list {
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.hl-mobile-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    transition: box-shadow 0.2s;
}

.hl-mobile-card:active {
    box-shadow: 0 3px 10px rgba(0,0,0,.12);
}

/* Top section: thumbnail + meta side by side */
.hl-mc-top {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem;
    align-items: flex-start;
}

.hl-mc-thumb {
    width: 80px;
    min-width: 80px;
    height: 64px;
    object-fit: cover;
    border-radius: 7px;
    flex-shrink: 0;
}

.hl-mc-thumb-placeholder {
    width: 80px;
    min-width: 80px;
    height: 64px;
    border-radius: 7px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.hl-mc-meta {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.headline-order-card {
    touch-action: pan-y;
    user-select: none;
    transition: transform 0.18s ease, opacity 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}

.headline-order-card.is-touching {
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
    z-index: 5;
}

.headline-order-card.order-changed {
    outline: 2px solid #2563eb;
    background: rgba(37, 99, 235, 0.08) !important;
}

[data-theme="dark"] .headline-order-card.order-changed {
    background: rgba(59, 130, 246, 0.16) !important;
}

.hl-mc-title {
    font-size: 0.88rem;
    font-weight: 600;
    line-height: 1.4;
    word-break: break-word;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.hl-mc-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
    align-items: center;
}

.hl-mc-badges .badge {
    font-size: 0.7rem;
    padding: 0.25em 0.55em;
}

/* Footer: order arrows + action buttons */
.hl-mc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.6rem 0.75rem;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
    gap: 0.5rem;
}

.hl-mc-order-btns {
    display: flex;
    gap: 0.4rem;
}

.hl-mc-order-btns .btn {
    padding: 0.3rem 0.6rem;
    font-size: 0.78rem;
    border-radius: 6px;
}

.hl-mc-actions {
    display: flex;
    gap: 0.4rem;
}

.hl-mc-actions .btn {
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* ============================================================
   TINYMCE CONTENT
   ============================================================ */
.headline-tinymce-content {
    font-size: 0.88rem;
    line-height: 1.5;
    color: inherit;
    word-break: break-word;
}

.headline-tinymce-content p { margin: 0 0 0.35em; }
.headline-tinymce-content p:last-child { margin-bottom: 0; }

.headline-tinymce-content h1,
.headline-tinymce-content h2,
.headline-tinymce-content h3,
.headline-tinymce-content h4,
.headline-tinymce-content h5,
.headline-tinymce-content h6 {
    margin: 0.3em 0 0.2em;
    font-weight: 600;
    line-height: 1.3;
}
.headline-tinymce-content h1 { font-size: 1.05rem; }
.headline-tinymce-content h2 { font-size: 0.98rem; }
.headline-tinymce-content h3 { font-size: 0.92rem; }

.headline-tinymce-content ul,
.headline-tinymce-content ol {
    margin: 0.3em 0 0.35em 1.2em;
    padding: 0;
}
.headline-tinymce-content li { margin-bottom: 0.15em; }
.headline-tinymce-content strong, .headline-tinymce-content b { font-weight: 700; }
.headline-tinymce-content em, .headline-tinymce-content i { font-style: italic; }
.headline-tinymce-content a { color: #0d6efd; text-decoration: underline; }

.headline-tinymce-content blockquote {
    margin: 0.4em 0;
    padding: 0.3em 0.7em;
    border-left: 3px solid #ccc;
    color: #555;
    background: #f9f9f9;
    border-radius: 0 4px 4px 0;
    font-size: 0.82rem;
}

/* ============================================================
   DRAG & DROP (desktop)
   ============================================================ */
.headline-order-row { cursor: grab; }
.headline-order-row.dragging { opacity: 0.5; }
.headline-order-row.drag-over {
    outline: 2px dashed #0d6efd;
    background-color: rgba(13, 110, 253, 0.07);
}

/* ============================================================
   DARK THEME
   ============================================================ */
[data-theme="dark"] .hl-card,
[data-theme="dark"] .hl-mobile-card {
    background-color: #2d2d2d;
    color: #f0f0f0;
    border-color: #444;
}

[data-theme="dark"] .hl-card-header {
    background-color: #3a3a3a;
    border-bottom-color: #444;
}

[data-theme="dark"] .hl-mc-footer {
    background: #333;
    border-top-color: #444;
}

[data-theme="dark"] .hl-mc-thumb-placeholder {
    background: #3a3a3a;
}

[data-theme="dark"] .table { color: #f0f0f0; border-color: #444; }
[data-theme="dark"] .table-light { background-color: #3a3a3a; color: #f0f0f0; }
[data-theme="dark"] .text-muted { color: #999 !important; }
[data-theme="dark"] .hl-hint { color: #888; }

[data-theme="dark"] .headline-tinymce-content { color: #f0f0f0; }
[data-theme="dark"] .headline-tinymce-content a { color: #7aadff; }
[data-theme="dark"] .headline-tinymce-content h1,
[data-theme="dark"] .headline-tinymce-content h2,
[data-theme="dark"] .headline-tinymce-content h3 { color: #ffffff; }
[data-theme="dark"] .headline-tinymce-content blockquote {
    background: #3a3a3a; border-left-color: #666; color: #ccc;
}

[data-theme="dark"] .modal-content { background-color: #2d2d2d; color: #f0f0f0; }
[data-theme="dark"] .modal-header { border-bottom-color: #444; }
[data-theme="dark"] .btn-close { filter: invert(1); }
</style>

@endsection