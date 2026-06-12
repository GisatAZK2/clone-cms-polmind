@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-people"></i> <span data-translate="dosen.list">Daftar Dosen</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 dosen-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 dosen-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="dosen.addNew">Tambah Dosen Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.dosen.index') }}" method="GET" class="d-flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    data-translate-placeholder="dosen.search"
                    placeholder="Cari dosen..."
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
        action="{{ route('admin.dosen.bulk-destroy') }}"
        method="POST"
        data-confirm-key="dosen.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus dosen yang dipilih?">
        @csrf
    </form>

    @if($dosens->count() > 0)
        <div class="dosen-bulk-action">
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

    @php
        $dosenTypeTranslateKeys = [
            'Dosen_Internal'  => 'dosen.typeInternal',
            'Expert_industri' => 'dosen.typeExpert',
            'Tenaga_Pendidik' => 'sen.typeEducator',
        ];

        $dosenTypeFallbacks = [
            'Dosen_Internal'  => 'Dosen Internal',
            'Expert_industri' => 'Expert Industri',
            'Tenaga_Pendidik' => 'Tenaga Pendidik',
        ];
    @endphp

    <div class="card shadow-sm dosen-list-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0"><i class="bi bi-people"></i> <span data-translate="dosen.list">Daftar Dosen</span></h5>
        </div>
        <div class="card-body p-0">
            @if($dosens->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="25%" data-translate="dosen.name">Nama</th>
                                <th width="25%" data-translate="dosen.type">Tipe Dosen</th>
                                <th width="15%" data-translate="common.createdDate">Tanggal Buat</th>
                                <th width="10%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosens as $index => $item)
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
                                    <td>{{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <span
                                            class="dosen-type-badge"
                                            data-translate="{{ $dosenTypeTranslateKeys[$item->type] ?? '' }}"
                                        >
                                            {{ $dosenTypeFallbacks[$item->type] ?? str_replace('_', ' ', $item->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.dosen.edit', $item->id) }}" class="btn btn-info text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.dosen.destroy', $item->id) }}"
                                                    method="POST"
                                                    style="display:inline;"
                                                    class="confirm-delete-form"
                                                    data-confirm-key="dosen.confirmDelete"
                                                    data-confirm-fallback="Yakin ingin menghapus?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> <span data-translate="dosen.noData">Tidak ada dosen</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none dosen-mobile-list">
                    @forelse($dosens as $index => $item)
                        <div class="card dosen-mobile-item">
                            <div class="card-body">
                                <div class="dosen-mobile-head">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-2"
                                        form="bulkDeleteForm"
                                    >

                                    <div class="dosen-mobile-avatar">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <div class="dosen-mobile-info">
                                        <h6 class="dosen-mobile-title">{{ $item->name }}</h6>

                                        <div class="dosen-mobile-meta">
                                            <span data-translate="dosen.type">Tipe Dosen</span>:
                                            <strong
                                                class="dosen-type-badge"
                                                data-translate="{{ $dosenTypeTranslateKeys[$item->type] ?? '' }}"
                                            >
                                                {{ $dosenTypeFallbacks[$item->type] ?? str_replace('_', ' ', $item->type) }}
                                            </strong>
                                        </div>

                                        <div class="dosen-mobile-date">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $item->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="dosen-mobile-actions">
                                    <a href="{{ route('admin.dosen.edit', $item->id) }}" class="btn btn-info btn-sm text-white">
                                        <i class="bi bi-pencil"></i>
                                        <span data-translate="common.edit">Edit</span>
                                    </a>

                                    <form action="{{ route('admin.dosen.destroy', $item->id) }}"
                                            method="POST"
                                            class="confirm-delete-form"
                                            data-confirm-key="dosen.confirmDelete"
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
                        </div>
                    @empty
                        <div class="alert alert-info m-3">
                            <i class="bi bi-inbox"></i>
                            <span data-translate="dosen.noData">Tidak ada dosen</span>
                        </div>
                    @endforelse
                </div>
                
                @if($dosens->hasPages())
                    <nav aria-label="Page navigation" class="mt-3 mb-0">
                        {{ $dosens->links('pagination::bootstrap-5') }}
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

@endsection
