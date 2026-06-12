@extends('pages.layouts.app')

@section('content')

<div class="dok-page">

    {{-- Header halaman --}}
    <div class="dok-header">
        <div class="dok-header-shape"></div>
        <h1 data-translate="documentation-page-title">DOKUMENTASI</h1>
        <p data-translate="documentation-page-subtitle">
            Arsip foto dan dokumentasi berbagai kegiatan yang telah dilaksanakan
        </p>
    </div>

    {{-- Empty state --}}
    @if($dokumentasi->isEmpty())
        <div class="dok-block">
            <div class="dok-block-inner">
                <div class="dok-empty">
                    <div class="dok-empty-ring">
                        <i class="fa-regular fa-image"></i>
                    </div>

                    <h3 data-translate="documentation-empty-title">
                        Belum ada dokumentasi
                    </h3>

                    <p data-translate="documentation-empty-desc">
                        Dokumentasi kegiatan belum tersedia saat ini. Data akan muncul setelah ditambahkan.
                    </p>
                </div>
            </div>
        </div>

    @else
        @php $minId = $dokumentasi->min('id'); @endphp

        @foreach($dokumentasi as $index => $dokument)
            @php
                $content   = $dokument->content ?? [];
                $items     = $content['items'] ?? [];
                $title     = ($dokument->id == $minId) ? 'Dokumentasi' : ($content['title'] ?? '');
                $deskripsi = $content['deskripsi'] ?? '';
                $isAlt     = ($index % 2 !== 0);
                $isLatest  = ($index === 0 && $dokument->id !== $minId);
                $isDefaultDocumentationTitle = ($dokument->id == $minId);
            @endphp

            @if($index > 0)
                <div class="dok-divider"></div>
            @endif

            <div class="dok-block {{ $isAlt ? 'alt' : '' }}">
                <div class="dok-block-inner">

                    @if($isLatest)
                        <div class="dok-badge">
                            <i class="fa-solid fa-calendar-day" style="font-size:11px"></i>
                            <span data-translate="documentation-latest-badge">
                                Kegiatan Terbaru
                            </span>
                        </div>
                    @endif

                    @if($title)
                        <h2 class="dokumentasi-content-title"
                            @if($isDefaultDocumentationTitle) data-translate="documentation-default-title" @endif>
                            {{ $title }}
                        </h2>

                        <div class="dok-block-label">
                            <i class="fa-solid fa-folder-open"></i>
                            <span data-translate="documentation-activity-label">Kegiatan</span>
                            {{ $loop->iteration }}
                        </div>

                        <h2 class="dok-block-title"
                            @if($isDefaultDocumentationTitle) data-translate="documentation-default-title" @endif>
                            {{ $title }}
                        </h2>
                    @endif

                    @if($deskripsi)
                        <div class="dokumentasi-content-content tinymce-content">{!! $deskripsi !!}</div>
                        <div class="dok-block-desc tinymce-content">{!! $deskripsi !!}</div>
                    @endif

                    @if(!empty($items))
                        <div class="dok-gallery">
                            @foreach($items as $item)
                                @php
                                    $path = $item['gambar'] ?? null;
                                    $altText = $item['alt'] ?? 'Dokumentasi kegiatan';
                                @endphp

                                @if($path)
                                    <div class="dok-item">
                                        <img
                                            class="preview-image"
                                            src="{{ asset('storage/' . $path) }}"
                                            alt="{{ $item['alt'] ?? 'Dokumentasi kegiatan' }}"
                                            loading="lazy"
                                            >
                                        <div class="dok-item-overlay">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    @endif

</div>


@endsection