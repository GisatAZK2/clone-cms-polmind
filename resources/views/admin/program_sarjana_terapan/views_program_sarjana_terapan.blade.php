@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-book"></i> <span data-translate="program_sarjana_terapan.list">Daftar Program Sarjana Terapan</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 program-list-page">
    <div class="row g-2 g-md-3 mb-3 mb-md-4 program-toolbar">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.program_sarjana_terapan.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> <span data-translate="program_sarjana_terapan.addNew">Tambah Program Baru</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.program_sarjana_terapan.index') }}" method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-sm"
                    data-translate-placeholder="program_sarjana_terapan.search"
                    placeholder="Cari program..."
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
        action="{{ route('admin.program_sarjana_terapan.bulk-destroy') }}"
        method="POST"
        data-confirm-key="prodi.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus prodi yang dipilih?">
        @csrf
    </form>

    @if($programs->count() > 0)
        <div class="program-bulk-action">
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

    <div class="card shadow-sm program-list-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0"><i class="bi bi-book"></i> <span data-translate="program_sarjana_terapan.list">Daftar Program Sarjana Terapan</span></h5>
        </div>
        <div class="card-body p-0">
            @if($programs->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="28%" data-translate="program_sarjana_terapan.fieldNamaProdi">Nama Prodi</th>
                                <th width="18%" data-translate="program_sarjana_terapan.fieldGelarSarjana">Gelar</th>
                                <th width="18%" data-translate="program_sarjana_terapan.fieldGambarProdi">Gambar</th>
                                <th width="16%" data-translate="common.createdDate">Tanggal Buat</th>
                                <th width="10%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programs as $index => $program)
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="ids[]"
                                            value="{{ $program->id }}"
                                            class="form-check-input bulk-item-checkbox"
                                            form="bulkDeleteForm"
                                        >
                                    </td>
                                    <td>{{ ($programs->currentPage() - 1) * $programs->perPage() + $loop->iteration }}</td>
                                    <td>{{ $program->content['nama_prodi'] ?? '-' }}</td>
                                    <td>{{ $program->content['gelar_sarjana'] ?? '-' }}</td>
                                    <td>
                                        @if(isset($program->content['gambar_prodi']) && is_array($program->content['gambar_prodi']) && count($program->content['gambar_prodi']) > 0)
                                            <span class="badge bg-success">{{ count($program->content['gambar_prodi']) }} gambar</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>{{ $program->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.program_sarjana_terapan.edit', $program->id) }}" class="btn btn-info text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.program_sarjana_terapan.destroy', $program->id) }}" method="POST" style="display:inline;" class="confirm-delete-form" data-confirm-key="program_sarjana_terapan.confirmDelete"
                                                data-confirm-fallback="Yakin ingin menghapus Program Sarjana Terapan ini?">
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
                                        <i class="bi bi-inbox"></i> <span data-translate="program_sarjana_terapan.noData">Tidak ada program</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <!-- Mobile Card View -->
<div class="d-md-none program-mobile-list">
    @forelse($programs as $index => $program)
        @php
            $namaProdi = $program->content['nama_prodi'] ?? '-';
            $gelar = $program->content['gelar_sarjana'] ?? '-';
            $gambarCount = (
                isset($program->content['gambar_prodi']) &&
                is_array($program->content['gambar_prodi'])
            ) ? count($program->content['gambar_prodi']) : 0;
        @endphp

        <div class="program-mobile-item">
            <div class="program-mobile-head">
                <input
                    type="checkbox"
                    name="ids[]"
                    value="{{ $program->id }}"
                    class="form-check-input bulk-item-checkbox program-mobile-check"
                    form="bulkDeleteForm"
                >

                <div class="program-mobile-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div class="program-mobile-info">
                    <h6 class="program-mobile-title">
                        {{ $namaProdi }}
                    </h6>

                    <div class="program-mobile-degree">
                        <span data-translate="program_sarjana_terapan.fieldGelarSarjana">Gelar</span>:
                        <strong>{{ $gelar }}</strong>
                    </div>

                    <div class="program-mobile-meta">
                        <span class="program-image-badge">
                            <i class="bi bi-image"></i>
                            {{ $gambarCount > 0 ? $gambarCount . ' gambar' : 'Tidak ada gambar' }}
                        </span>

                        <span class="program-mobile-date">
                            <i class="bi bi-calendar3"></i>
                            {{ $program->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="program-mobile-actions">
                <a href="{{ route('admin.program_sarjana_terapan.edit', $program->id) }}"
                   class="btn btn-info btn-sm text-white">
                    <i class="bi bi-pencil"></i>
                    <span data-translate="common.edit">Edit</span>
                </a>

                <form action="{{ route('admin.program_sarjana_terapan.destroy', $program->id) }}"
                      method="POST"
                      class="confirm-delete-form"
                      data-confirm-key="program_sarjana_terapan.confirmDelete"
                      data-confirm-fallback="Yakin ingin menghapus Program Sarjana Terapan ini? Semua gambar terkait akan ikut terhapus.">
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
        <div class="program-mobile-empty">
            <i class="bi bi-inbox"></i>
            <p>
                <span data-translate="program_sarjana_terapan.noData">Tidak ada program</span>
            </p>
        </div>
    @endforelse
</div>

                <!-- Pagination -->
                @if($programs->hasPages())
                    <div class="card-footer bg-light">
                        {{ $programs->appends(request()->query())->links() }}
                    </div>
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