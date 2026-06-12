@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-person-lines-fill"></i> <span data-translate="profile_page.title">Daftar Profile Page</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6">
            @php
                $existingTypes = $profilePages->pluck('type')->toArray();
                $allTypes = ['cover', 'visi_misi', 'profile'];
                $allFilled = count(array_diff($allTypes, $existingTypes)) === 0;
            @endphp
                <a href="{{ route('admin.profile-page.create') }}" class="btn btn-primary w-100 w-md-auto">
                    <i class="bi bi-plus-circle"></i> <span data-translate="profile_page.addNew">Tambah Profile Page</span>
                </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="{{ route('admin.profile-page.index') }}" method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-sm"
                    placeholder="Cari type..."
                    data-translate-placeholder="profile_page.search"
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
        action="{{ route('admin.profile-page.bulk-destroy') }}"
        method="POST"
        data-confirm-key="profile_page.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus profile page yang dipilih?">
        @csrf
    </form>

    @if($profilePages->count() > 0)
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
            <h5 class="mb-0">
                <i class="bi bi-person-lines-fill"></i>
                <span data-translate="profile_page.list">Daftar Profile Page</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($profilePages->count() > 0)
                {{-- Desktop Table --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="selectAllItems" class="form-check-input">
                                </th>
                                <th width="5%">#</th>
                                <th width="12%" data-translate="profile_page.colType">Type</th>
                                <th width="18%" data-translate="profile_page.colGambar">Gambar</th>
                                <th width="35%" data-translate="profile_page.colKonten">Konten</th>
                                <th width="10%" data-translate="profile_page.colDibuat">Dibuat</th>
                                <th width="15%" data-translate="common.action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($profilePages as $item)
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
                                    <td>{{ ($profilePages->currentPage() - 1) * $profilePages->perPage() + $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $badgeColor = match($item->type) {
                                                'cover'     => 'primary',
                                                'visi_misi' => 'warning',
                                                'profile'   => 'success',
                                                default     => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }}">
                                            {{ strtoupper(str_replace('_', ' ', $item->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->url_images)
                                            <img src="{{ asset('storage/' . $item->url_images) }}"
                                                 alt="{{ $item->alt ?? '' }}"
                                                 class="img-thumbnail preview-image"
                                                 style="width:60px;height:60px;object-fit:cover;">
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->type === 'cover')
                                            <small class="text-muted">Alt: {{ $item->alt ?? '—' }}</small>
                                        @elseif($item->type === 'visi_misi')
                                            <small>
                                                <strong>Visi:</strong> {!! Str::limit(strip_tags($item->visi ?? ''), 60) !!}<br>
                                                <strong>Misi:</strong> {!! Str::limit(strip_tags($item->misi ?? ''), 60) !!}
                                            </small>
                                        @elseif($item->type === 'profile')
                                            <small>
                                                <strong>{{ $item->content['nama_profil'] ?? '—' }}</strong><br>
                                                {!! Str::limit(strip_tags($item->content['deskripsi_profile'] ?? ''), 60) !!}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="small">{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.profile-page.edit', $item->id) }}"
                                               class="btn btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.profile-page.destroy', $item->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                class="confirm-delete-form"
                                                data-confirm-key="profile_page.confirmDelete"
                                                data-confirm-fallback="Yakin ingin menghapus profile page ini? Gambar yang terkait juga akan dihapus.">
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
                                        <i class="bi bi-inbox"></i>
                                        <span data-translate="profile_page.noData">Tidak ada data</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="d-md-none">
                    @forelse($profilePages as $item)
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
                                    @if($item->url_images)
                                        <img src="{{ asset('storage/' . $item->url_images) }}"
                                             alt="{{ $item->alt ?? '' }}"
                                             class="img-thumbnail flex-shrink-0 preview-image"
                                             style="width:50px;height:50px;object-fit:cover;"
                                             onerror="this.src='https://via.placeholder.com/50'">
                                    @else
                                        <div class="flex-shrink-0 bg-secondary d-flex align-items-center justify-content-center rounded"
                                             style="width:50px;height:50px;">
                                            <i class="bi bi-image text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        @php
                                            $badgeColor = match($item->type) {
                                                'cover'     => 'primary',
                                                'visi_misi' => 'warning',
                                                'profile'   => 'success',
                                                default     => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }} mb-1">
                                            {{ strtoupper(str_replace('_', ' ', $item->type)) }}
                                        </span>
                                        @if($item->type === 'visi_misi')
                                            <p class="small mb-0">{!! Str::limit(strip_tags($item->visi ?? ''), 50) !!}</p>
                                        @elseif($item->type === 'profile')
                                            <p class="small mb-0 fw-semibold">{{ $item->content['nama_profil'] ?? '—' }}</p>
                                        @else
                                            <p class="small mb-0 text-muted">{{ $item->alt ?? '—' }}</p>
                                        @endif
                                        <small class="text-muted d-block">{{ $item->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.profile-page.edit', $item->id) }}"
                                       class="btn btn-info btn-sm text-white flex-grow-1">
                                        <i class="bi bi-pencil"></i> <span data-translate="common.edit">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.profile-page.destroy', $item->id) }}"
                                          method="POST" style="display:inline;flex-grow:1;"
                                          class="confirm-delete-form"
                                          data-confirm-key="profile_page.confirmDelete"
                                          data-confirm-fallback="Yakin ingin menghapus profile page ini? Gambar yang terkait juga akan dihapus." >
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
                            <i class="bi bi-inbox"></i>
                            <span data-translate="profile_page.noData">Tidak ada data</span>
                        </div>
                    @endforelse
                </div>

                @if($profilePages->hasPages())
                    <nav aria-label="Page navigation" class="mt-3 mb-0 px-3 pb-3">
                        {{ $profilePages->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            @else
                <div class="alert alert-info m-3 mb-0">
                    <i class="bi bi-info-circle"></i>
                    <span data-translate="profile_page.noData">Tidak ada data</span>.
                    @if(!$allFilled)
                        <a href="{{ route('admin.profile-page.create') }}">
                            <span data-translate="common.createNow">Buat sekarang</span>
                        </a>
                    @endif
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
    .bulk-item-checkbox { cursor: pointer; }
    .d-md-none .bulk-item-checkbox { flex-shrink: 0; }
</style>
@endsection