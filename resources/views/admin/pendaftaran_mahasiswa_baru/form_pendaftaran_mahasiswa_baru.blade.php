@extends('admin.layout.layout')

@php
    $isEdit = isset($content) && count($content) > 0;
    $pageTitle = $pageTitle ?? ($isEdit
        ? '<i class="bi bi-pencil"></i> <span data-translate="pendaftaran.editForm">Edit Pendaftaran Mahasiswa Baru</span>'
        : '<i class="bi bi-plus-circle"></i> <span data-translate="pendaftaran.addForm">Tambah Pendaftaran Mahasiswa Baru</span>');
@endphp

@section('page-title')
    {!! $pageTitle !!}
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ $action }}" method="POST">
                @csrf
                @if(isset($method) && strtoupper($method) !== 'POST')
                    @method($method)
                @endif

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldTitle">Title</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title', $content['title'] ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldDeskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $content['deskripsi'] ?? '') }}</textarea>
                </div>

                <hr>
                <h5 data-translate="pendaftaran.sectionGelombang">Gelombang</h5>

                @php
                    $gelombangs = old('gelombang', $content['gelombang'] ?? []);
                    if (!is_array($gelombangs) || count($gelombangs) === 0) {
                        $gelombangs = [[
                            'nama_gelombang'      => '',
                            'jadwal_pendaftaran'  => '',
                            'jadwal_ujian'        => '',
                            'jadwal_pengumuman'   => '',
                            'jadwal_daftar_ulang' => '',
                            'biaya' => [[
                                'nama_biaya'    => '',
                                'nominal'       => '',
                                'harga_diskon'  => '',
                                'alasan_diskon' => '',
                                'periode_bayar' => '',
                                'has_diskon'    => false,
                            ]],
                        ]];
                    }
                @endphp

                <div id="gelombang-list">
                    @foreach($gelombangs as $i => $g)
                        <div class="card mb-3 p-3 gelombang-item" data-index="{{ $i }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <strong class="gelombang-number">
                                    <span data-translate="pendaftaran.gelombangLabel">Gelombang</span> #{{ $i + 1 }}
                                </strong>
                                <button type="button" class="btn btn-sm btn-danger btn-delete-gelombang"
                                        data-translate="pendaftaran.hapusGelombang">Hapus Gelombang</button>
                            </div>

                            <div class="row gx-3 gy-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" data-translate="pendaftaran.fieldNamaGelombang">Nama Gelombang</label>
                                    <input type="text" name="gelombang[{{ $i }}][nama_gelombang]"
                                           class="form-control" value="{{ $g['nama_gelombang'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-translate="pendaftaran.fieldJadwalDaftarUlang">Jadwal Daftar Ulang</label>
                                    <input type="date" name="gelombang[{{ $i }}][jadwal_daftar_ulang]"
                                           class="form-control" value="{{ $g['jadwal_daftar_ulang'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" data-translate="pendaftaran.fieldJadwalPendaftaran">Jadwal Pendaftaran</label>
                                    <input type="date" name="gelombang[{{ $i }}][jadwal_pendaftaran]"
                                           class="form-control" value="{{ $g['jadwal_pendaftaran'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" data-translate="pendaftaran.fieldJadwalUjian">Jadwal Ujian Seleksi</label>
                                    <input type="date" name="gelombang[{{ $i }}][jadwal_ujian]"
                                           class="form-control" value="{{ $g['jadwal_ujian'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" data-translate="pendaftaran.fieldJadwalPengumuman">Jadwal Pengumuman</label>
                                    <input type="date" name="gelombang[{{ $i }}][jadwal_pengumuman]"
                                           class="form-control" value="{{ $g['jadwal_pengumuman'] ?? '' }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" data-translate="pendaftaran.sectionBiaya">Biaya</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-add-biaya"
                                            data-translate="pendaftaran.tambahBiaya">Tambah Biaya</button>
                                </div>
                                <div class="biaya-list">
                                    @php
                                        $biayas = $g['biaya'] ?? [[
                                            'nama_biaya'    => '',
                                            'nominal'       => '',
                                            'harga_diskon'  => '',
                                            'alasan_diskon' => '',
                                            'periode_bayar' => '',
                                            'has_diskon'    => false,
                                        ]];
                                    @endphp
                                    @foreach($biayas as $j => $b)
                                        <div class="biaya-item border rounded p-2 mb-2" data-biaya-index="{{ $j }}">
                                            <div class="row gx-2 gy-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label" data-translate="pendaftaran.fieldNamaBiaya">Nama Biaya</label>
                                                    <input type="text" name="gelombang[{{ $i }}][biaya][{{ $j }}][nama_biaya]"
                                                           class="form-control" value="{{ $b['nama_biaya'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label" data-translate="pendaftaran.fieldNominal">Nominal</label>
                                                    <input type="text" name="gelombang[{{ $i }}][biaya][{{ $j }}][nominal]"
                                                           class="form-control" value="{{ $b['nominal'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label" data-translate="pendaftaran.fieldPeriodeBayar">Periode Bayar</label>
                                                    <input type="text" name="gelombang[{{ $i }}][biaya][{{ $j }}][periode_bayar]"
                                                           class="form-control" value="{{ $b['periode_bayar'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input btn-toggle-diskon" type="checkbox"
                                                               value="1" id="diskon-{{ $i }}-{{ $j }}"
                                                               name="gelombang[{{ $i }}][biaya][{{ $j }}][has_diskon]"
                                                               {{ !empty($b['has_diskon']) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="diskon-{{ $i }}-{{ $j }}"
                                                               data-translate="pendaftaran.checkboxDiskon">Ingin memberi diskon?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-biaya"
                                                            data-translate="common.delete">Hapus</button>
                                                </div>
                                            </div>
                                            <div class="row gx-2 gy-2 mt-1 biaya-diskon-group"
                                                 style="{{ !empty($b['has_diskon']) ? '' : 'display:none;' }}">
                                                <div class="col-md-2">
                                                    <label class="form-label" data-translate="pendaftaran.fieldHargaDiskon">Harga Diskon</label>
                                                    <input type="text" name="gelombang[{{ $i }}][biaya][{{ $j }}][harga_diskon]"
                                                           class="form-control" value="{{ $b['harga_diskon'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" data-translate="pendaftaran.fieldAlasanDiskon">Alasan Diskon</label>
                                                    <input type="text" name="gelombang[{{ $i }}][biaya][{{ $j }}][alasan_diskon]"
                                                           class="form-control" value="{{ $b['alasan_diskon'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <button type="button" id="add-gelombang" class="btn btn-sm btn-secondary"
                            data-translate="pendaftaran.tambahGelombang">Tambah Gelombang</button>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldPersyaratanAdministrasi">
                        Persyaratan Administrasi
                    </label>

                    @include('admin.components.text-editor', [
                        'name'     => 'persyaratan_administrasi',
                        'label'    => false,
                        'value'    => old('persyaratan_administrasi', $content['persyaratan_administrasi'] ?? ''),
                        'required' => true
                    ])
                </div>

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldKataPenutup">Kata Penutup</label>
                    <input type="text" name="kata_penutup" class="form-control"
                           value="{{ old('kata_penutup', $content['kata_penutup'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldLinkDaftar">Link Daftar</label>
                    <input type="text" name="link_daftar" class="form-control"
                           value="{{ old('link_daftar', $content['link_daftar'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" data-translate="pendaftaran.fieldKalimatBantuan">Kalimat Bantuan</label>
                    <input type="text" name="kalimat_bantuan" class="form-control"
                           value="{{ old('kalimat_bantuan', $content['kalimat_bantuan'] ?? '') }}">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-translate="common.save">Simpan</button>
                    <a href="{{ route('admin.pendaftaran-mahasiswa-baru.index') }}"
                       class="btn btn-secondary" data-translate="common.cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
