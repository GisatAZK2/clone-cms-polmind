@extends('pages.layouts.app')

@section('content')

    {{-- ============================================================
    HERO SECTION
    ============================================================ --}}
    <section class="pmb-hero"
        style="background-image: url('{{ asset('assets/images/pmb-1.png') }}'); display: block; position: relative; background-size: cover; background-position: center;">
        <div class="pmb-hero__overlay"></div>

        <div class="pmb-hero__content">
            <h1 class="pmb-hero__title" data-translate="pmb-hero-title">
                Lebih dari Sekadar Kuliah,<br>Kami adalah Inkubator Talenta Global!
            </h1>

            <p class="pmb-hero__subtitle" data-translate="pmb-hero-subtitle">
                Politeknik Mitra Industri hadir dengan konsep <em>Teaching Factory</em> yang revolusioner,
                mempersiapkanmu menjadi profesional handal dan membuka pintu karir impianmu di Jepang.
            </p>

            <div class="pmb-hero__buttons">
                <a href="https://siakad.polmind.ac.id/spmbfront" target="_blank" class="pmb-btn pmb-btn--primary"
                    data-translate="register-now-btn">
                    Daftar Sekarang
                </a>

                <a href="#selengkapnya" class="pmb-btn pmb-btn--outline" data-translate="learn-more">
                    Selengkapnya
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
    MAIN CONTENT
    ============================================================ --}}
    <div class="pmb-page" id="selengkapnya">

        @php
            $record = ($pmb ?? collect())->first();

            $default = [
                'title' => '',
                'deskripsi' => '',
                'gelombang' => [],
                'persyaratan_administrasi' => '',
                'kata_penutup' => '',
                'link_daftar' => '',
                'kalimat_bantuan' => '',
            ];

            if ($record) {
                $raw = $record->content ?? [];

                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $content = $decoded === null ? $default : array_merge($default, $decoded);
                } elseif (is_array($raw) || $raw instanceof \ArrayAccess) {
                    $content = array_merge($default, (array) $raw);
                } else {
                    $content = $default;
                }
            } else {
                $content = $default;
            }

            $hasCustomTitle = !empty(trim((string) ($content['title'] ?? '')));
            $hasCustomDesc = !empty(trim((string) ($content['deskripsi'] ?? '')));
            $hasCustomClosing = !empty(trim((string) ($content['kata_penutup'] ?? '')));
            $hasCustomHelp = !empty(trim((string) ($content['kalimat_bantuan'] ?? '')));

            function format_currency($value)
            {
                if ($value === null || $value === '' || $value === '0')
                    return '-';

                $clean = preg_replace('/[^0-9]/', '', $value);

                if (empty($clean))
                    return '-';

                return 'Rp ' . number_format((int) $clean, 0, ',', '.') . ',-';
            }
        @endphp

        {{-- PAGE TITLE + INTRO --}}
        <div class="pmb-intro">
            @if($hasCustomTitle)
                <h2 class="pmb-intro__title">
                    {{ $content['title'] }}
                </h2>
            @else
                <h2 class="pmb-intro__title" data-translate="pmb-page-title">
                    Pendaftaran Mahasiswa Baru
                </h2>
            @endif

            @if($hasCustomDesc)
                <p class="pmb-intro__desc">
                    {{ $content['deskripsi'] }}
                </p>
            @else
                <p class="pmb-intro__desc" data-translate="pmb-page-description">
                    Bergabunglah dengan Politeknik Mitra Industri dan wujudkan impian karir Anda bersama kami.
                </p>
            @endif
        </div>

        {{-- ========================
        JADWAL PMB
        ======================== --}}
        <div class="pmb-section-card">
            <div class="pmb-section-header">
                <h3 class="pmb-section-title" data-translate="pmb-schedule-title">
                    Jadwal Pendaftaran
                </h3>
            </div>

            @if(!empty($content['gelombang']) && count($content['gelombang']) > 0)
                <div class="pmb-gelombang-grid">
                    @foreach(($content['gelombang'] ?? []) as $g)
                        <div class="pmb-gelombang-card">
                            <div class="pmb-gelombang-card__head">
                                @if(!empty($g['nama_gelombang']))
                                    {{ $g['nama_gelombang'] }}
                                @else
                                    <span data-translate="pmb-wave-default">GELOMBANG</span>
                                @endif
                            </div>

                            <div class="pmb-gelombang-card__body">
                                <div class="pmb-jadwal-row">
                                    <span class="pmb-jadwal-label" data-translate="pmb-registration-label">
                                        Pendaftaran
                                    </span>
                                    <span class="pmb-jadwal-val">
                                        {{ $g['jadwal_pendaftaran'] ?? '-' }}
                                    </span>
                                </div>

                                <div class="pmb-jadwal-row">
                                    <span class="pmb-jadwal-label" data-translate="pmb-test-label">
                                        Ujian Seleksi
                                    </span>
                                    <span class="pmb-jadwal-val">
                                        {{ $g['jadwal_ujian'] ?? '-' }}
                                    </span>
                                </div>

                                <div class="pmb-jadwal-row">
                                    <span class="pmb-jadwal-label" data-translate="pmb-announcement-label">
                                        Pengumuman
                                    </span>
                                    <span class="pmb-jadwal-val">
                                        {{ $g['jadwal_pengumuman'] ?? '-' }}
                                    </span>
                                </div>

                                <div class="pmb-jadwal-row">
                                    <span class="pmb-jadwal-label" data-translate="pmb-reregistration-label">
                                        Daftar Ulang
                                    </span>
                                    <span class="pmb-jadwal-val">
                                        {{ $g['jadwal_daftar_ulang'] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="pmb-empty-state">
                    <div class="pmb-empty-state__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>

                    <strong data-translate="pmb-schedule-empty-title">
                        Jadwal PMB belum tersedia
                    </strong>

                    <p data-translate="pmb-schedule-empty-desc">
                        Informasi jadwal pendaftaran akan ditampilkan di sini setelah diunggah oleh admin.
                    </p>
                </div>
            @endif
        </div>

        {{-- ========================
        PERSYARATAN ADMINISTRASI
        ======================== --}}
        <div class="pmb-section-card">
            <div class="pmb-section-header">
                <h3 class="pmb-section-title" data-translate="pmb-requirements-title">
                    Persyaratan Administrasi
                </h3>
            </div>

            @if(!empty(trim((string) ($content['persyaratan_administrasi'] ?? ''))))
                <div class="pmb-content">
                    {!! $content['persyaratan_administrasi'] !!}
                </div>
            @else
                <div class="pmb-empty-state">
                    <div class="pmb-empty-state__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>

                    <strong data-translate="pmb-requirements-empty-title">
                        Persyaratan administrasi belum diatur
                    </strong>

                    <p data-translate="pmb-requirements-empty-desc">
                        Silakan cek kembali nanti atau hubungi admin untuk informasi lebih lanjut.
                    </p>
                </div>
            @endif
        </div>

        {{-- ========================
        BIAYA PERKULIAHAN
        ======================== --}}
        <div class="pmb-section-card">
            <div class="pmb-section-header">
                <h3 class="pmb-section-title" data-translate="pmb-tuition-title">
                    Biaya Perkuliahan
                </h3>
            </div>

            @php
                $hasBiaya = false;

                foreach (($content['gelombang'] ?? []) as $g) {
                    if (!empty($g['biaya'])) {
                        $hasBiaya = true;
                        break;
                    }
                }
            @endphp

            @if($hasBiaya)

                {{-- DESKTOP TABLE --}}
                <div class="pmb-table-wrap pmb-biaya-desktop">
                    <table class="pmb-biaya-table">
                        <thead>
                            <tr>
                                <th data-translate="pmb-wave-label">Gelombang</th>
                                <th data-translate="pmb-fee-name">Nama Biaya</th>
                                <th data-translate="pmb-financing-amount">Nominal</th>
                                <th data-translate="pmb-financing-period">Periode</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach(($content['gelombang'] ?? []) as $g)
                                @foreach($g['biaya'] ?? [] as $bIndex => $b)
                                    <tr>
                                        @if($bIndex === 0)
                                            <td rowspan="{{ count($g['biaya']) }}" class="pmb-biaya-table__wave-cell">
                                                <span class="pmb-wave-badge">
                                                    @if(!empty($g['nama_gelombang']))
                                                        {{ $g['nama_gelombang'] }}
                                                    @else
                                                        <span data-translate="pmb-wave-default">Gelombang</span>
                                                    @endif
                                                </span>
                                            </td>
                                        @endif

                                        <td>{{ $b['nama_biaya'] ?? '-' }}</td>

                                        <td>
                                            @if(!empty($b['harga_diskon']))
                                                <span class="pmb-price--orig">
                                                    {{ format_currency($b['nominal'] ?? null) }}
                                                </span>

                                                <span class="pmb-price--main">
                                                    {{ format_currency($b['harga_diskon']) }}
                                                </span>

                                                <span class="pmb-discount-badge">
                                                    @if(!empty($b['alasan_diskon']))
                                                        {{ $b['alasan_diskon'] }}
                                                    @else
                                                        <span data-translate="pmb-discount-default">Diskon</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="pmb-price--main">
                                                    {{ format_currency($b['nominal'] ?? null) }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>{{ $b['periode_bayar'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="pmb-biaya-mobile">
                    @foreach(($content['gelombang'] ?? []) as $g)
                        @if(!empty($g['biaya']))
                            <div class="pmb-biaya-wave-group">
                                <div class="pmb-biaya-wave-label">
                                    @if(!empty($g['nama_gelombang']))
                                        {{ $g['nama_gelombang'] }}
                                    @else
                                        <span data-translate="pmb-wave-default">Gelombang</span>
                                    @endif
                                </div>

                                @foreach($g['biaya'] as $b)
                                    <div class="pmb-biaya-item">
                                        <div class="pmb-biaya-item__name">
                                            {{ $b['nama_biaya'] ?? '-' }}
                                        </div>

                                        <div class="pmb-biaya-item__right">
                                            @if(!empty($b['harga_diskon']))
                                                <span class="pmb-price--orig">
                                                    {{ format_currency($b['nominal'] ?? null) }}
                                                </span>

                                                <span class="pmb-price--main">
                                                    {{ format_currency($b['harga_diskon']) }}
                                                </span>

                                                <span class="pmb-discount-badge">
                                                    @if(!empty($b['alasan_diskon']))
                                                        {{ $b['alasan_diskon'] }}
                                                    @else
                                                        <span data-translate="pmb-discount-default">Diskon</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="pmb-price--main">
                                                    {{ format_currency($b['nominal'] ?? null) }}
                                                </span>
                                            @endif

                                            <span class="pmb-biaya-item__periode">
                                                {{ $b['periode_bayar'] ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>

            @else
                <div class="pmb-empty-state">
                    <div class="pmb-empty-state__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>

                    <strong data-translate="pmb-tuition-empty-title">
                        Biaya perkuliahan belum ditetapkan
                    </strong>

                    <p data-translate="pmb-tuition-empty-desc">
                        Informasi biaya akan ditampilkan setelah admin menambahkan daftar biaya per gelombang.
                    </p>
                </div>
            @endif
        </div>

        {{-- ========================
        CTA / DAFTAR
        ======================== --}}
        <div class="pmb-cta-block" id="daftar">
            <div class="pmb-cta-block__text">
                @if($hasCustomClosing)
                    <h3>{{ $content['kata_penutup'] }}</h3>
                @else
                    <h3 data-translate="pmb-cta-title-default">
                        Siap bergabung bersama Polmind?
                    </h3>
                @endif

                @if($hasCustomHelp)
                    <p>{{ $content['kalimat_bantuan'] }}</p>
                @else
                    <p data-translate="pmb-help-text">
                        Jika ada pertanyaan, silakan hubungi admin kami di tombol WhatsApp di pojok kanan halaman.
                    </p>
                @endif
            </div>

            <a href="{{ $content['link_daftar'] ?: 'https://siakad.polmind.ac.id/spmbfront' }}" target="_blank"
                class="pmb-btn pmb-btn--cta" data-translate="pmb-register-arrow-button">
                DAFTAR POLMIND &rarr;
            </a>
        </div>

    </div>

@endsection