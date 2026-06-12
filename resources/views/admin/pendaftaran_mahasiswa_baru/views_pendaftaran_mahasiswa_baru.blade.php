@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-card-list"></i> <span data-translate="pendaftaran.title">Pendaftaran Mahasiswa Baru</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row mb-3">
        <div class="col-12 col-md-6">
            @if ($items->count() == 0)
              <a href="{{ route('admin.pendaftaran-mahasiswa-baru.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> <span data-translate="pendaftaran.addNew">Tambah Pendaftaran</span>
            </a>
            @endif  
        </div>
    </div>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.pendaftaran-mahasiswa-baru.bulk-destroy') }}"
        method="POST"
        data-confirm-key="pendaftaran.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus data pendaftaran yang dipilih?">
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
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th data-translate="common.number">#</th>
                                <th data-translate="pendaftaran.colTitle">Title</th>
                                <th data-translate="pendaftaran.colDibuat">Dibuat</th>
                                <th data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                @php
                                    $c = $item->content ?? [];
                                @endphp
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
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $c['title'] ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.pendaftaran-mahasiswa-baru.edit', $item->id) }}"
                                           class="btn btn-sm btn-info text-white"
                                           data-translate="common.edit">Edit</a>
                                        <form action="{{ route('admin.pendaftaran-mahasiswa-baru.destroy', $item->id) }}"
                                            method="POST"
                                            style="display:inline-block;"
                                            class="confirm-delete-form"
                                            data-confirm-key="pendaftaran.confirmDelete"
                                            data-confirm-fallback="Yakin ingin menghapus data ini?">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger" data-translate="common.delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i>
                                        <span data-translate="pendaftaran.noData">Belum ada data.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
@endsection