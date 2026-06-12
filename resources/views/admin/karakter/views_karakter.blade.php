@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-star"></i> <span data-translate="karakter.list">Daftar Karakter</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 karakter-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 karakter-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.karakter.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="karakter.addNew">Tambah Karakter Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.karakter.index') }}" method="GET" class="d-flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    data-translate-placeholder="karakter.searchPlaceholder"
                    placeholder="Cari karakter..."
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
        action="{{ route('admin.karakter.bulk-destroy') }}"
        method="POST"
        data-confirm-key="karakter.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus karakter yang dipilih?">
        @csrf
    </form>

    @if($karakters->count() > 0)
        <div class="karakter-bulk-action">
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

    <div class="card shadow-sm karakter-list-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0">
                <i class="bi bi-star"></i> <span data-translate="karakter.list">Daftar Karakter</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($karakters->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="5%"></th>
                                <th width="25%"><span data-translate="karakter.namaKonten">Nama Konten</span></th>
                                <th width="30%"><span data-translate="karakter.description">Deskripsi</span></th>
                                <th width="15%"><span data-translate="common.createdDate">Tanggal Buat</span></th>
                                <th width="15%"><span data-translate="common.action">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karakters as $index => $item)
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
                                    <td>{{ ($karakters->currentPage() - 1) * $karakters->perPage() + $loop->iteration }}</td>
                                    <td>
                                        @if($item->url_image)
                                            <img src="{{ asset('storage/' . $item->url_image) }}" 
                                                 alt="{{ $item->alt }}"
                                                 class="img-thumbnail rounded preview-image"
                                                 style="max-width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_konten }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block" style="max-height: 50px; overflow: hidden;">
                                            {!! strip_tags(substr($item->deskripsi, 0, 100)) !!}{{ strlen(strip_tags($item->deskripsi)) > 100 ? '...' : '' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.karakter.edit', $item->id) }}" 
                                               class="btn btn-info text-white"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.karakter.destroy', $item->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                class="confirm-delete-form"
                                                data-confirm-key="karakter.confirmDelete"
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> <span data-translate="karakter.noData">Tidak ada karakter</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none karakter-mobile-list">
                    @forelse($karakters as $index => $item)
                        <div class="karakter-mobile-item">
                            <div class="karakter-mobile-header">
                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $item->id }}"
                                    class="form-check-input bulk-item-checkbox karakter-mobile-check"
                                    form="bulkDeleteForm"
                                >

                                <div class="karakter-mobile-image-wrap">
                                    @if($item->url_image)
                                        <img src="{{ asset('storage/' . $item->url_image) }}"
                                            alt="{{ $item->alt }}"
                                            class="karakter-mobile-image preview-image">
                                    @else
                                        <div class="karakter-mobile-image-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="karakter-mobile-info">
                                    <h6 class="karakter-mobile-title">
                                        {{ $item->nama_konten }}
                                    </h6>

                                    <small class="karakter-mobile-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $item->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>

                            <div class="karakter-mobile-desc">
                                {!! strip_tags(substr($item->deskripsi, 0, 100)) !!}{{ strlen(strip_tags($item->deskripsi)) > 100 ? '...' : '' }}
                            </div>

                            <div class="karakter-mobile-actions">
                                <a href="{{ route('admin.karakter.edit', $item->id) }}"
                                class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-pencil"></i>
                                    <span data-translate="common.edit">Edit</span>
                                </a>

                                <form action="{{ route('admin.karakter.destroy', $item->id) }}"
                                    method="POST"
                                    class="confirm-delete-form"
                                    data-confirm-key="karakter.confirmDelete"
                                    data-confirm-fallback="Yakin ingin menghapus?">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                        <span data-translate="common.delete">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="karakter-mobile-empty">
                            <i class="bi bi-inbox"></i>
                            <p>
                                <span data-translate="karakter.noData">Tidak ada karakter</span>
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center py-3">
                    {{ $karakters->links() }}
                </div>

            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">
                        <span data-translate="karakter.noData">Tidak ada karakter</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection