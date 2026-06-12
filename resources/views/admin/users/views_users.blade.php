@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-people"></i> <span data-translate="users.list">Daftar User</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 users-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 users-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="users.addNew">Tambah User Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    data-translate-placeholder="users.search"
                    placeholder="Cari user..."
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
        action="{{ route('admin.users.bulk-destroy') }}"
        method="POST"
        data-confirm-key="users.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus user yang dipilih?">
        @csrf
    </form>

    @if($users->count() > 0)
        <div class="users-bulk-action">
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
    
    <div class="card shadow-sm users-list-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0"><i class="bi bi-people"></i> <span data-translate="users.list">Daftar User</span></h5>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="22%" data-translate="users.name">Nama</th>
                                <th width="28%">Email</th>
                                <th width="10%" data-translate="users.role">Peran</th>
                                <th width="15%" data-translate="common.createdDate">Tanggal Buat</th>
                                <th width="15%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $item)
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
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td>
                                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.users.edit', $item->id) }}" class="btn btn-info text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" style="display:inline;" class="confirm-delete-form" data-confirm-key="users.confirmDelete" data-confirm-fallback="Yakin ingin menghapus user ini?">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> <span data-translate="users.noData">Tidak ada user</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none users-mobile-list">
                    @forelse($users as $index => $item)
                        <div class="card users-mobile-item">
                            <div class="card-body">
                                <div class="users-mobile-head">
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $item->id }}"
                                        class="form-check-input bulk-item-checkbox mt-2"
                                        form="bulkDeleteForm"
                                    >
                                    <div class="users-mobile-avatar">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>

                                    <div class="users-mobile-info">
                                        <h6 class="users-mobile-title">{{ $item->name }}</h6>

                                        <div class="users-mobile-email">
                                            <i class="bi bi-envelope"></i>
                                            <span>{{ $item->email }}</span>
                                        </div>

                                        <div class="users-mobile-meta">
                                            <span class="users-role-badge">
                                                {{ ucfirst($item->role) }}
                                            </span>

                                            <span class="users-mobile-date">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="users-mobile-actions">
                                    <a href="{{ route('admin.users.edit', $item->id) }}" class="btn btn-info btn-sm text-white">
                                        <i class="bi bi-pencil"></i>
                                        <span data-translate="common.edit">Edit</span>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $item->id) }}"
                                        method="POST"
                                        class="confirm-delete-form"
                                        data-confirm-key="users.confirmDelete"
                                        data-confirm-fallback="Yakin ingin menghapus user ini?">
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
                            <span data-translate="users.noData">Tidak ada user</span>
                        </div>
                    @endforelse
                </div>
                
                @if($users->hasPages())
                    <nav aria-label="Page navigation" class="mt-3 mb-0">
                        {{ $users->links('pagination::bootstrap-5') }}
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