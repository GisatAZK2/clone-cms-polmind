@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-file-earmark-text"></i> <span data-translate="dokumentasi.title">Daftar Dokumentasi</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.dokumentasi.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="dokumentasi.addNew">Tambah Dokumentasi</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.dokumentasi.index') }}" method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-sm"
                    placeholder="Cari dokumentasi..."
                    data-translate-placeholder="dokumentasi.search"
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.dokumentasi.bulk-destroy') }}"
        method="POST"
        data-confirm-key="dokumentasi.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus dokumentasi yang dipilih?">
        @csrf
    </form>

    @if($items->count() > 0)
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
    <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> <span data-translate="dokumentasi.list">Daftar Dokumentasi</span></h5>
        </div>
        <div class="card-body p-0">
            @if($items->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5">#</th>
                                <th width="25%" data-translate="dokumentasi.titleField">Title</th>
                                <th width="40%" data-translate="dokumentasi.deskripsiField">Deskripsi</th>
                                <th width="15%" data-translate="dokumentasi.colImages">Jumlah Gambar</th>
                                <th width="15%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="ids[]"
                                            value="{{ $item->id }}"
                                            class="form-check-input bulk-item-checkbox"
                                            form="bulkDeleteForm"
                                        >
                                    </td>
                                    <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                    <td>{{ Str::limit($item->content['title'] ?? '-', 50) }}</td>
                                    <td>{{ Str::limit(strip_tags($item->content['deskripsi'] ?? ''), 80) }}</td>
                                    <td>{{ count($item->content['items'] ?? []) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.dokumentasi.edit', $item->id) }}" class="btn btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.dokumentasi.destroy', $item->id) }}" method="POST" style="display:inline;"
                                                onsubmit="return confirm(AdminTranslate.t('dokumentasi.confirmDelete'))">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none">
                    @foreach($items as $item)
                        <div class="card border-0 border-bottom mb-2">
                            <div class="card-body p-2 p-sm-3">
                                <div class="d-flex gap-3 align-items-start mb-2">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-1"
                                        form="bulkDeleteForm"
                                    >

                                    <div>
                                        <p class="mb-1 small fw-semibold">{{ Str::limit($item->content['title'] ?? '-', 50) }}</p>
                                        <p class="mb-1 small">{{ Str::limit(strip_tags($item->content['deskripsi'] ?? ''), 70) }}</p>
                                        <small class="text-muted">{{ count($item->content['items'] ?? []) }} gambar</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.dokumentasi.edit', $item->id) }}" class="btn btn-info btn-sm text-white flex-grow-1">
                                        <i class="bi bi-pencil"></i> <span data-translate="common.edit">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.dokumentasi.destroy', $item->id) }}" method="POST" style="display:inline;flex-grow:1;"
                                        onsubmit="return confirm(AdminTranslate.t('dokumentasi.confirmDelete'))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="bi bi-trash"></i> <span data-translate="common.delete">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($items->hasPages())
                    <nav aria-label="Page navigation" class="mt-3 mb-0 px-3 pb-3">
                        {{ $items->links('pagination::bootstrap-5') }}
                    </nav>
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

<style>
    [data-theme="dark"] .card { background-color: #2d2d2d; color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .card-header { background-color: #3a3a3a !important; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .table { color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .table-light th { background-color: #3a3a3a; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: #3a3a3a; }
    [data-theme="dark"] .text-muted { color: #999 !important; }
    [data-theme="dark"] .alert-info { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .card.border-bottom { border-bottom-color: #444 !important; }
</style>
@endsection
