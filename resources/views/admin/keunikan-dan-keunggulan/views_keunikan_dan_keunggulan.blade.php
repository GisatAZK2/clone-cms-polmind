@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-stars"></i>
    <span data-translate="keunikanKeunggulan.title">Keunikan &amp; Keunggulan</span>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4 keunikan-list-page">

    {{-- Header --}}
    <div class="keunikan-toolbar">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-stars me-1"></i>
            <span data-translate="keunikanKeunggulan.list">Daftar Keunikan &amp; Keunggulan</span>
        </h5>
        <a href="{{ route('admin.keunikan-dan-keunggulan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>
            <span data-translate="keunikanKeunggulan.addNew">Tambah Baru</span>
        </a>
    </div>

    <form
        id="bulkDeleteForm"
        action="{{ route('admin.keunikan-dan-keunggulan.bulk-destroy') }}"
        method="POST"
        data-confirm-key="keunikanKeunggulan.confirmBulkDelete"
        data-confirm-fallback="Yakin ingin menghapus data yang dipilih? Semua gambar terkait akan ikut terhapus.">
        @csrf
    </form>

    @if($items->count() > 0)
        <div class="keunikan-bulk-action">
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

    {{-- Table --}}
    <div class="card shadow-sm keunikan-list-card">
        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllItems" class="form-check-input">
                            </th>
                            <th width="50">#</th>
                            <th data-translate="keunikanKeunggulan.colTitle">Judul</th>
                            <th data-translate="keunikanKeunggulan.colBlocks">Blok Konten</th>
                            <th data-translate="keunikanKeunggulan.colDibuat">Dibuat</th>
                            <th width="120" data-translate="common.action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i => $item)
                            @php
                                $content = $item->content ?? [];
                                $blocks  = $content['blocks'] ?? [];
                                $title   = $content['title'] ?? '-';
                                $imgCount  = collect($blocks)->where('type','image')->count();
                                $paraCount = collect($blocks)->where('type','paragraph')->count();
                                $firstImg  = collect($blocks)->firstWhere('type','image');
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
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td>
                                    @if($firstImg && $firstImg['path'])
                                        <img src="{{ asset('storage/' . $firstImg['path']) }}"
                                             alt="{{ $firstImg['alt'] ?? '' }}"
                                             class="rounded preview-image me-2"
                                             style="width:48px;height:40px;object-fit:cover;">
                                    @endif
                                    <span class="fw-semibold">{{ Str::limit($title, 60) }}</span>
                                </td>
                                <td>
                                    @if($paraCount)
                                        <span class="badge bg-info text-dark me-1">
                                            <i class="bi bi-paragraph me-1"></i>{{ $paraCount }} paragraf
                                        </span>
                                    @endif
                                    @if($imgCount)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-image me-1"></i>{{ $imgCount }} gambar
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.keunikan-dan-keunggulan.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.keunikan-dan-keunggulan.destroy', $item->id) }}"
                                            method="POST"
                                            class="confirm-delete-form"
                                            data-confirm-key="keunikanKeunggulan.confirmDelete"
                                            data-confirm-fallback="Yakin ingin menghapus data ini? Semua gambar terkait akan ikut terhapus.">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                  
                <div class="empty">
                    <i class="bi bi-inbox"></i> 
                    <p>
                    <span data-translate="projects.noData">Tidak ada data</span>
                    </p>
                </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="d-md-none keunikan-mobile-list">
                @forelse ($items as $i => $item)
                    @php
                        $content = $item->content ?? [];
                        $blocks  = $content['blocks'] ?? [];
                        $title   = $content['title'] ?? '-';
                        $imgCount  = collect($blocks)->where('type','image')->count();
                        $paraCount = collect($blocks)->where('type','paragraph')->count();
                        $firstImg  = collect($blocks)->firstWhere('type','image');
                    @endphp

                    <div class="card keunikan-mobile-item">
                        <div class="card-body">
                            <div class="keunikan-mobile-head">
                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $item->id }}"
                                    class="form-check-input bulk-item-checkbox mt-2"
                                    form="bulkDeleteForm"
                                >
                                <div class="keunikan-mobile-thumb">
                                    @if($firstImg && !empty($firstImg['path']))
                                        <img src="{{ asset('storage/' . $firstImg['path']) }}"
                                            alt="{{ $firstImg['alt'] ?? '' }}">
                                    @else
                                        <i class="bi bi-stars"></i>
                                    @endif
                                </div>

                                <div class="keunikan-mobile-info">
                                    <h6 class="keunikan-mobile-title">
                                        {{ Str::limit($title, 70) }}
                                    </h6>

                                    <div class="keunikan-mobile-badges">
                                        @if($paraCount)
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-paragraph"></i>
                                                {{ $paraCount }} paragraf
                                            </span>
                                        @endif

                                        @if($imgCount)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-image"></i>
                                                {{ $imgCount }} gambar
                                            </span>
                                        @endif
                                    </div>

                                    <div class="keunikan-mobile-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="keunikan-mobile-actions">
                                <a href="{{ route('admin.keunikan-dan-keunggulan.edit', $item->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                    <span data-translate="common.edit">Edit</span>
                                </a>

                               <form action="{{ route('admin.keunikan-dan-keunggulan.destroy', $item->id) }}"
                                    method="POST"
                                    class="confirm-delete-form"
                                    data-confirm-key="keunikanKeunggulan.confirmDelete"
                                    data-confirm-fallback="Yakin ingin menghapus data ini? Semua gambar terkait akan ikut terhapus.">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger btn-sm">
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
                        <span data-translate="keunikanKeunggulan.noData">Belum ada data.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection