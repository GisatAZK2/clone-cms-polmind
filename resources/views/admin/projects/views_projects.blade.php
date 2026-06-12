@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-folder2-open"></i> <span data-translate="projects.title">Daftar Projects</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="projects.addNew">Tambah Project</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.projects.index') }}" method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-sm"
                    placeholder="Cari deskripsi..."
                    data-translate-placeholder="projects.search"
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
        action="{{ route('admin.projects.bulk-destroy') }}"
        method="POST"
        data-confirm-key="prodi.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus prodi yang dipilih?">
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
            <h5 class="mb-0"><i class="bi bi-folder2-open"></i> <span data-translate="projects.list">Daftar Projects</span></h5>
        </div>
        <div class="card-body p-0">
            @if($items->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="15%" data-translate="projects.colGambar">Gambar</th>
                                <th width="25%" data-translate="projects.colJudul">Judul</th>
                                <th width="30%" data-translate="projects.colDeskripsi">Deskripsi</th>
                                <th width="15%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
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
                                    <td>
                                        <img src="{{ $item->url_images ? asset('storage/' . $item->url_images) : 'https://via.placeholder.com/60' }}"
                                            alt="{{ $item->alt }}"
                                            class="img-thumbnail preview-image" style="width:60px;height:60px;object-fit:cover;">
                                    </td>
                                    <td>{{ Str::limit($item->title, 50) }}</td>
                                    <td>{{ Str::limit(strip_tags($item->deskripsi), 60) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.projects.edit', $item->id) }}" class="btn btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.projects.destroy', $item->id) }}" method="POST" style="display:inline;"
                                                class="confirm-delete-form" data-confirm-key="projects.confirmDelete" data-confirm-fallback="Yakin ingin menghapus project ini?">
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
                                        <i class="bi bi-inbox"></i> <span data-translate="projects.noData">Tidak ada data</span>
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
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-2"
                                        form="bulkDeleteForm"
                                    >
                                    <img src="{{ $item->url_images ? asset('storage/' . $item->url_images) : 'https://via.placeholder.com/50' }}"
                                        alt="{{ $item->alt }}"
                                        class="img-thumbnail flex-shrink-0 preview-image" style="width:50px;height:50px;object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small fw-semibold">{{ Str::limit($item->title, 50) }}</p>
                                        <p class="mb-0 small">{{ Str::limit(strip_tags($item->deskripsi), 60) }}</p>
                                        <small class="text-muted">{{ $item->alt ?? '-' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.projects.edit', $item->id) }}" class="btn btn-info btn-sm text-white flex-grow-1">
                                        <i class="bi bi-pencil"></i> <span data-translate="common.edit">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $item->id) }}" method="POST"
                                        style="display:inline;flex-grow:1;" class="confirm-delete-form"
                                        data-confirm-key="projects.confirmDelete" data-confirm-fallback="Yakin ingin menghapus project ini?">
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
                            <i class="bi bi-inbox"></i> <span data-translate="projects.noData">Tidak ada data</span>
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
    [data-theme="dark"] .card { background-color: #2d2d2d; color: #f0f0f0; border: none; }
    [data-theme="dark"] .card-header { background-color: #3a3a3a !important; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .table { color: #f0f0f0; border-color: #444; }
    [data-theme="dark"] .table-light th { background-color: #3a3a3a; color: #f0f0f0; border-bottom-color: #444; }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: #3a3a3a; }
    [data-theme="dark"] .text-muted { color: #999 !important; }
    [data-theme="dark"] .alert-info { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
    [data-theme="dark"] .card.border-bottom { border-bottom-color: #444 !important; }
    [data-theme="dark"] .img-thumbnail { background-color: #3a3a3a; border-color: #555; }
</style>
@endsection