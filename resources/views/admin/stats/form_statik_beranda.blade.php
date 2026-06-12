@extends('admin.layout.layout')

@section('page-title')
    <i class="bi bi-graph-up"></i>
    @if(isset($homeStat))
        <span data-translate="homeStat.editForm">Edit Statistik Beranda</span>
    @else
        <span data-translate="homeStat.addForm">Tambah Statistik Beranda</span>
    @endif
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5>
                @if(isset($homeStat))
                    <span data-translate="homeStat.editForm">Edit</span>
                @else
                    <span data-translate="homeStat.addForm">Tambah</span>
                @endif
                <span data-translate="homeStat.cardTitle">Statistik Beranda</span>
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($homeStat) ? route('admin.home-stat.update', $homeStat->id) : route('admin.home-stat.store') }}"
                  method="POST">
                @csrf
                @if(isset($homeStat))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <!-- Icon Picker -->
                    <div class="col-md-6">
                        <label class="form-label" data-translate="homeStat.fieldIcon">Icon Bootstrap</label>
                        <div class="position-relative">
                            <input type="text" id="iconInput" name="icon" class="form-control"
                                   value="{{ old('icon', $homeStat->icon ?? '') }}"
                                   data-translate="homeStat.iconPlaceholder"
                                   placeholder="Ketik nama icon (contoh: whatsapp, graph, trophy)"
                                   data-translate-placeholder="homeStat.iconPlaceholder"
                                   autocomplete="off">
                            <div id="iconSuggestions" class="list-group position-absolute w-100 shadow-sm"
                                 style="max-height: 320px; overflow-y: auto; z-index: 1050; display: none; margin-top: 2px; border-radius: 0.375rem;">
                            </div>
                        </div>
                        <small class="text-muted" data-translate="homeStat.iconHint">Ketik untuk mencari icon Bootstrap</small>
                    </div>

                   <div class="col-md-6">
                    <label class="form-label" data-translate="homeStat.fieldOrder">Urutan Tampil</label>

                    @if(isset($homeStat))
                        <input type="number"
                            name="order"
                            class="form-control"
                            min="0"
                            value="{{ old('order', $homeStat->order) }}">
                        <small class="text-muted">
                            Jika nomor urutan sama dengan data lain, posisinya akan otomatis ditukar.
                        </small>
                    @else
                        <input type="number"
                            class="form-control"
                            value="{{ old('order', $nextOrder ?? 1) }}"
                            disabled>
                        <input type="hidden"
                            name="order"
                            value="{{ old('order', $nextOrder ?? 1) }}">
                        <small class="text-muted" data-translate="homeStat.orderHint">
                            Urutan otomatis mengikuti data terakhir.
                        </small>
                    @endif
                </div>

                    <div class="col-12">
                        <label class="form-label" data-translate="homeStat.fieldLabel">Label</label>
                        <input type="text" name="label" class="form-control" required
                               value="{{ old('label', $homeStat->label ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label" data-translate="homeStat.fieldValue">Value / Angka</label>
                        <input type="number" name="value" class="form-control" required
                               value="{{ old('value', $homeStat->value ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label" data-translate="homeStat.fieldDescription">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $homeStat->description ?? '') }}</textarea>
                    </div>

                    <!-- CHECKBOX AKTIF -->
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                   value="1"
                                   {{ old('is_active', $homeStat->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active" data-translate="homeStat.checkboxActive">
                                Aktif (Tampil di Beranda)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.home-stat.index') }}" class="btn btn-secondary" data-translate="common.cancel">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        @if(isset($homeStat))
                            <span data-translate="common.update">Update</span>
                        @else
                            <span data-translate="common.save">Simpan</span>
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection