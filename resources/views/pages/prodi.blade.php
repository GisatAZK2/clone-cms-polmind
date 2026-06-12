@extends('pages.layouts.app')

@section('content')
    <div class="ps-page">
        {{-- ── Hero + Navigasi ── --}}
        <div class="ps-hero">
            <div class="ps-hero-shape"></div>
            <h1 data-translate="prodi-page-title">PROGRAM STUDI</h1>

            <p class="ps-hero-sub" data-translate="prodi-page-subtitle">
                Pilih program studi yang sesuai dengan minat dan tujuan kariermu
            </p>

            @if($program_sarjana_terapan->isNotEmpty())
                <nav class="ps-nav">
                    @foreach($program_sarjana_terapan as $index => $program)
                        @php
                            $namaProdi = $program->content['nama_prodi'] ?? 'Program ' . ($index + 1);
                            $slugProdi = Str::slug($namaProdi);
                        @endphp

                        <a href="#{{ $slugProdi }}" class="ps-nav-btn {{ $index === 0 ? 'active' : '' }}">
                            {{ $namaProdi }}
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>

        {{-- ── Empty State ── --}}
        @if($program_sarjana_terapan->isEmpty())
            <div class="container">
                <div class="ps-empty">
                    <div class="ps-empty-ring">
                        <i class="fa-solid fa-book-open"></i>
                    </div>

                    <h3 data-translate="prodi-empty-title">
                        Belum ada program studi
                    </h3>

                    <p data-translate="prodi-empty-desc">
                        Data program studi belum tersedia saat ini. Silakan kembali lagi nanti atau hubungi bagian akademik.
                    </p>
                </div>
        @else

                {{-- ── Sections Prodi ── --}}
                @foreach($program_sarjana_terapan as $index => $program)
                    @php
                        $content = $program->content ?? [];
                        $nama = $content['nama_prodi'] ?? 'Program Studi';
                        $slugProdi = Str::slug($nama);
                        $gelar = $content['gelar_sarjana'] ?? '';
                        $deskripsi = $content['deskripsi_prodi'] ?? '';
                        $gambar = $content['gambar_prodi'] ?? [];
                        $isAlt = ($index % 2 !== 0);

                        $words = preg_split('/\s+/', trim($nama), 2);
                        $first = $words[0] ?? '';
                        $rest = $words[1] ?? '';
                    @endphp

                    <section id="{{ $slugProdi }}" class="ps-prodi {{ $isAlt ? 'alt' : '' }}">
                        <div style="position:relative;top:-80px;visibility:hidden"></div>
                        <div class="container">

                            {{-- Info prodi + gallery --}}
                            <div class="ps-prodi-head">
                                <div class="ps-prodi-info">
                                    @if($gelar)
                                        <div class="ps-gelar">
                                            <i class="fa-solid fa-certificate"></i> {{ $gelar }}
                                        </div>
                                    @endif

                                    <h2 class="ps-title">
                                        <span class="ps-title-accent">{{ $first }}</span>
                                        @if($rest) {{ $rest }} @endif
                                    </h2>

                                    @if($deskripsi)
                                        <div class="ps-desc prodi-content">{!! $deskripsi !!}</div>
                                    @endif

                                    <div class="ps-meta">
                                        <span class="ps-meta-item">
                                            <i class="fa-regular fa-clock"></i>
                                            <span data-translate="prodi-meta-semester">8 Semester</span>
                                        </span>

                                        <span class="ps-meta-item">
                                            <i class="fa-solid fa-medal"></i>
                                            <span data-translate="prodi-meta-accreditation">Akreditasi</span>
                                        </span>

                                        <span class="ps-meta-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span data-translate="prodi-meta-degree">Sarjana Terapan</span>
                                        </span>
                                    </div>
                                </div>

                                @if(!empty($gambar))
                                    <div class="ps-gallery">
                                        @foreach($gambar as $imgIndex => $img)
                                            <div class="ps-gal-item">
                                                <img src="{{ asset('storage/' . $img) }}"
                                                    alt="{{ $content['image_alt'][$imgIndex] ?? $nama }}" class="preview-image">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Kurikulum accordion --}}
                            <div class="ps-kurikulum">
                                <div class="ps-kurikulum-label">
                                    <i class="fa-solid fa-list-ul"></i>
                                    <span data-translate="prodi-curriculum-label">Outline kurikulum</span>
                                </div>

                                <p class="ps-kurikulum-intro">
                                    <span data-translate="prodi-curriculum-intro-prefix">
                                        Berikut ini adalah <em>outline</em> kurikulum untuk
                                    </span>
                                    {{ $nama }}.
                                </p>

                                {{-- Dua kolom independen — Ganjil | Genap --}}
                                <div class="ps-sem-grid">

                                    {{-- Kolom Kiri: Semester Ganjil (1, 3, 5, 7) --}}
                                    <div class="ps-sem-col">
                                        @for($s = 1; $s <= 8; $s += 2)
                                            @if(isset($content['semester_' . $s]))
                                                <div class="ps-sem-card">
                                                    <div class="ps-sem-hdr" onclick="togglePsSem(this)">
                                                        <span class="ps-sem-num">
                                                            <span data-translate="prodi-semester-label">Semester</span> {{ $s }}</span>
                                                        <i class="fa-solid fa-chevron-down ps-sem-chevron"></i>
                                                    </div>
                                                    <div class="ps-sem-body">
                                                        <div class="prodi-content">
                                                            {!! $content['semester_' . $s] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>

                                    {{-- Kolom Kanan: Semester Genap (2, 4, 6, 8) --}}
                                    <div class="ps-sem-col">
                                        @for($s = 2; $s <= 8; $s += 2)
                                            @if(isset($content['semester_' . $s]))
                                                <div class="ps-sem-card">
                                                    <div class="ps-sem-hdr" onclick="togglePsSem(this)">
                                                        <span class="ps-sem-num">
                                                            <span data-translate="prodi-semester-label">Semester</span> {{ $s }}
                                                        </span>
                                                        <i class="fa-solid fa-chevron-down ps-sem-chevron"></i>
                                                    </div>
                                                    <div class="ps-sem-body">
                                                        <div class="prodi-content">
                                                            {!! $content['semester_' . $s] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>

                                </div>{{-- /.ps-sem-grid --}}
                            </div>{{-- /.ps-kurikulum --}}

                        </div>
                    </section>
                @endforeach

            @endif

        </div>

        {{-- Modal lightbox --}}
        <div id="prodi-modal" class="prodi-modal" onclick="prodiCloseModal()"
            aria-label="Tutup preview gambar program studi" data-translate-attr="aria-label"
            data-translate-key="prodi-modal-close-label">

            <span class="prodi-close">&times;</span>

            <img class="prodi-modal-content preview-image" id="prodi-modal-img" alt="Preview gambar program studi"
                data-translate-attr="alt" data-translate-key="prodi-modal-image-alt">
        </div>

        <script>
            /* ── Toggle accordion semester ── */
            function togglePsSem(hdr) {
                var card = hdr.closest('.ps-sem-card');
                var isExpanded = card.classList.contains('expanded');

                /* Tutup semua card dalam kolom yang sama */
                var col = card.closest('.ps-sem-col');
                col.querySelectorAll('.ps-sem-card').forEach(function (c) {
                    c.classList.remove('expanded');
                    c.querySelector('.ps-sem-hdr').classList.remove('active');
                });

                /* Buka card yang diklik (toggle: jika sudah terbuka, tutup saja) */
                if (!isExpanded) {
                    card.classList.add('expanded');
                    hdr.classList.add('active');
                }
            }

            /* ── Modal lightbox ── */
            function prodiOpenModal(img) {
                var modal = document.getElementById('prodi-modal');
                var modalImg = document.getElementById('prodi-modal-img');
                modalImg.src = img.src;
                modalImg.alt = img.alt;
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function prodiCloseModal() {
                document.getElementById('prodi-modal').classList.remove('open');
                document.body.style.overflow = '';
            }

            /* Tutup modal dengan tombol Escape */
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') prodiCloseModal();
            });

            /* ── Active nav on scroll ── */
            (function () {
                var navBtns = document.querySelectorAll('.ps-nav-btn');
                var sections = document.querySelectorAll('.ps-prodi[id]');
                if (!navBtns.length || !sections.length) return;

                window.addEventListener('scroll', function () {
                    var scrollY = window.scrollY + 120;
                    var current = '';
                    sections.forEach(function (sec) {
                        if (sec.offsetTop <= scrollY) current = sec.id;
                    });
                    navBtns.forEach(function (btn) {
                        var href = btn.getAttribute('href');
                        btn.classList.toggle('active', href === '#' + current);
                    });
                }, { passive: true });
            })();
        </script>

@endsection