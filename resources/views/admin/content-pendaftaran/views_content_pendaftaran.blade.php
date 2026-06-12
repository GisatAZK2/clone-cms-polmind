@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-card-list"></i> <span data-translate="contentPendaftaran.title">Daftar Content Pendaftaran</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 content-pendaftaran-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 content-pendaftaran-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.content-pendaftaran.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="contentPendaftaran.addNew">Tambah Content Pendaftaran</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.content-pendaftaran.index') }}" method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-sm"
                    placeholder="Cari berdasarkan type..."
                    data-translate-placeholder="contentPendaftaran.search"
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
        action="{{ route('admin.content-pendaftaran.bulk-destroy') }}"
        method="POST"
        data-confirm-key="contentPendaftaran.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus data yang dipilih?">
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

    <div class="card shadow-sm content-pendaftaran-list-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0"><i class="bi bi-card-list"></i> <span data-translate="contentPendaftaran.list">Daftar Content Pendaftaran</span></h5>
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
                                <th width="20%" data-translate="contentPendaftaran.colType">Type</th>
                                <th width="40%" data-translate="contentPendaftaran.colItems">Items</th>
                                <th width="20%" data-translate="contentPendaftaran.colDibuat">Dibuat</th>
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
                                        <span class="badge bg-{{ $item->type === 'atas' ? 'primary' : 'secondary' }}">
                                            {{ strtoupper($item->type) }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        {{ count($item->content ?? []) }} item(s)
                                        @if(count($item->content ?? []) > 0)
                                            <div class="d-flex gap-1 mt-1">
                                                @foreach(array_slice($item->content, 0, 3) as $img)
                                                    <img src="{{ isset($img['url_images']) && $img['url_images'] ? asset('storage/' . $img['url_images']) : 'https://via.placeholder.com/40' }}"
                                                        alt="{{ $img['alt'] ?? 'Image' }}"
                                                        class="img-thumbnail preview-image"
                                                        style="width:40px;height:40px;object-fit:cover;">
                                                @endforeach
                                                @if(count($item->content) > 3)
                                                    <div class="img-thumbnail" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;font-size:12px;">
                                                        +{{ count($item->content) - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.content-pendaftaran.edit', $item->id) }}" class="btn btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.content-pendaftaran.destroy', $item->id) }}" method="POST" style="display:inline;"
                                                onsubmit="return confirm(AdminTranslate.t('contentPendaftaran.confirmDelete'))">
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
                                        <i class="bi bi-inbox"></i> <span data-translate="contentPendaftaran.noData">Tidak ada data</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none content-pendaftaran-mobile-list">
                    @forelse($items as $item)
                        <div class="card content-pendaftaran-mobile-item mb-2">
                            <div class="card-body p-2 p-sm-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-start gap-2 flex-grow-1">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-1"
                                        form="bulkDeleteForm"
                                    >
                                    <div class="flex-grow-1">
                                        <span class="badge bg-{{ $item->type === 'atas' ? 'primary' : 'secondary' }} mb-1">
                                            {{ strtoupper($item->type) }}
                                        </span>
                                        <p class="mb-0 small text-muted">{{ count($item->content ?? []) }} item(s)</p>
                                        @if(count($item->content ?? []) > 0)
                                            <div class="d-flex gap-1 mt-1">
                                                @foreach(array_slice($item->content, 0, 3) as $img)
                                                    <img src="{{ isset($img['url_images']) && $img['url_images'] ? asset('storage/' . $img['url_images']) : 'https://via.placeholder.com/40' }}"
                                                        alt="{{ $img['alt'] ?? 'Image' }}"
                                                        class="img-thumbnail preview-image"
                                                        style="width:40px;height:40px;object-fit:cover;">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    </div>
                                    <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                </div>
                                <div class="content-pendaftaran-mobile-actions">
                                    <a href="{{ route('admin.content-pendaftaran.edit', $item->id) }}" class="btn btn-info btn-sm text-white flex-grow-1">
                                        <i class="bi bi-pencil"></i> <span data-translate="common.edit">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.content-pendaftaran.destroy', $item->id) }}" method="POST" style="display:inline; flex-grow:1;"
                                        onsubmit="return confirm(AdminTranslate.t('contentPendaftaran.confirmDelete'))">
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
                            <i class="bi bi-inbox"></i> <span data-translate="contentPendaftaran.noData">Tidak ada data</span>
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
</style>
@endsection