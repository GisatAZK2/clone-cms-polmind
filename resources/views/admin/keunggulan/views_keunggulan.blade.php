@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-star"></i> <span data-translate="keunggulan.title">Daftar Keunggulan</span>
@endsection

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3 py-md-4">
        <div class="row g-2 g-md-3 mb-3 mb-md-4">
            <div class="col-12 col-md-6">
                <a href="{{ route('admin.keunggulan.create') }}" class="btn btn-primary w-100 w-md-auto">
                    <i class="bi bi-plus-circle"></i> <span data-translate="keunggulan.addNew">Tambah Keunggulan</span>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('admin.keunggulan.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        data-translate-placeholder="keunggulan.search" placeholder="Cari keunggulan..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <form id="bulkDeleteForm" action="{{ route('admin.keunggulan.bulk-destroy') }}" method="POST"
            data-confirm-key="keunggulan.confirmBulkDelete"
            data-confirm-fallback="Yakin ingin menghapus keunggulan yang dipilih?">
            @csrf
        </form>

        @if($items->count() > 0)
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" form="bulkDeleteForm" id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled>
                    <i class="bi bi-trash3"></i>
                    <span data-translate="common.bulkDelete">Hapus Massal</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="bi bi-star"></i> <span data-translate="keunggulan.list">Daftar Keunggulan</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($items->count() > 0)
                    <!-- Desktop Table -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover mb-0">
                            <!-- Desktop Table thead -->
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="selectAllItems" class="form-check-input">
                                    </th>
                                    <th width="5%">#</th>
                                    <th width="15%" data-translate="keunggulan.image">Gambar</th>
                                    <th width="45%" data-translate="keunggulan.content">Keunggulan</th>
                                    <th width="15%" data-translate="keunggulan.altText">Alt</th>
                                    <th width="15%" data-translate="common.action">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                class="form-check-input bulk-item-checkbox" form="bulkDeleteForm">
                                        </td>
                                        <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $item->url_images ? asset('storage/' . $item->url_images) : 'https://via.placeholder.com/60' }}"
                                                alt="{{ $item->alt }}" class="img-thumbnail preview-image"
                                                style="width:60px;height:60px;object-fit:cover;">
                                        </td>
                                        <td>
                                            <strong>
                                                {{ Str::limit(trim(html_entity_decode(strip_tags($item->keunggulan))), 80) }}
                                            </strong>
                                        </td>
                                        <td class="text-muted small">{{ $item->alt ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.keunggulan.edit', $item->id) }}"
                                                    class="btn btn-info text-white" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.keunggulan.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;" class="confirm-delete-form"
                                                    data-confirm-key="keunggulan.confirmDelete"
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> <span data-translate="keunggulan.noData">Tidak ada
                                                data</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @forelse($items as $item)
                            <div class="card border-0 border-bottom mb-2">
                                <div class="card-body p-2 p-sm-3">
                                    <div class="d-flex gap-3 align-items-start mb-2">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="form-check-input bulk-item-checkbox mt-2" form="bulkDeleteForm">
                                        <img src="{{ $item->url_images ? asset('storage/' . $item->url_images) : 'https://via.placeholder.com/50' }}"
                                            alt="{{ $item->alt }}" class="img-thumbnail flex-shrink-0 preview-image"
                                            style="width:50px;height:50px;object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <p class="mb-0 small fw-semibold">
                                                {{ Str::limit(trim(html_entity_decode(strip_tags($item->keunggulan))), 60) }}
                                            </p>
                                            <small class="text-muted">{{ $item->alt ?? '-' }}</small>
                                        </div>
                                    </div>
                                    <!-- Mobile Card View — tombol & empty state -->
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.keunggulan.edit', $item->id) }}"
                                            class="btn btn-info btn-sm text-white flex-grow-1">
                                            <i class="bi bi-pencil"></i> <span data-translate="common.edit">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.keunggulan.destroy', $item->id) }}" method="POST"
                                            style="display:inline;flex-grow:1;" class="confirm-delete-form"
                                            data-confirm-key="keunggulan.confirmDelete"
                                            data-confirm-fallback="Yakin ingin menghapus?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="bi bi-trash"></i> <span data-translate="common.delete">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info m-3">
                                <i class="bi bi-inbox"></i> <span data-translate="keunggulan.noData">Tidak ada data</span>
                            </div>
                        @endforelse
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

        [data-theme="dark"] .table {
            color: #f0f0f0;
            border-color: #444;
        }

        [data-theme="dark"] .table-light th {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border-bottom-color: #444;
        }

        [data-theme="dark"] .table-hover tbody tr:hover {
            background-color: #3a3a3a;
        }

        [data-theme="dark"] .text-muted {
            color: #999 !important;
        }

        [data-theme="dark"] .alert-info {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border-color: #555;
        }

        [data-theme="dark"] .card.border-bottom {
            border-bottom-color: #444 !important;
        }

        [data-theme="dark"] .img-thumbnail {
            background-color: #3a3a3a;
            border-color: #555;
        }
    </style>
@endsection