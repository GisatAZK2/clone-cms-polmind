@extends('pages.layouts.app')

@section('content')

  <div class="dosen-page">

    {{-- ===== DOSEN INTERNAL ===== --}}
    <section class="container-dosen">

      <div class="section-header-dosen">
        <h2 class="judul-dosen-grid" data-translate="internal-lecturers">
          Dosen Internal
        </h2>

        <p class="subjudul-dosen" data-translate="lecturers-internal-subtitle">
          Akademisi berpengalaman yang mendampingi perjalanan studi Anda
        </p>
      </div>

      @php
        $dosenInternal = $dosens->where('type', 'Dosen_Internal');
      @endphp

      <div class="grid-dosen-wrapper" data-animate="slide-up" data-animate-delay="0.30s">

        @if ($dosenInternal->isEmpty())

          {{-- EMPTY STATE --}}
          <div class="kartu-dosen-grid empty-state">

            <div class="empty-ring">
              <i class="fa-regular fa-user"></i>
            </div>

            <div class="empty-text">
              <h3 data-translate="lecturers-internal-empty-title">Belum ada dosen internal</h3>

              <p data-translate="lecturers-internal-empty-desc">
                Data dosen internal belum tersedia saat ini.
                Data akan muncul setelah ditambahkan.
              </p>
            </div>

          </div>

        @else


          @foreach ($dosenInternal as $index => $dosen)

            <div class="kartu-dosen-grid js-open-person-modal" role="button" tabindex="0" data-name="{{ $dosen->name }}"
              data-photo="{{ asset('storage/' . $dosen->url_image) }}" data-alt="{{ $dosen->alt ?? $dosen->name }}"
              data-desc="{{ $dosen->deskripsi }}" data-type="{{ $dosen->type }}" data-animate="slide-right"
              data-animate-delay="{{ $index * 0.12 }}s">
              <div class="foto-dosen-wrap">

                <img src="{{ asset('storage/' . $dosen->url_image) }}" alt="{{ $dosen->alt ?? $dosen->name }}"
                  class="foto-dosen-bulat preview-image"
                  onerror="this.src='{{ asset('assets/images/placeholder-dosen.png') }}'">

                <div class="foto-dosen-overlay">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">

                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />

                  </svg>
                </div>

              </div>

              <div class="nama-dosen">
                {{ $dosen->name }}
              </div>

            </div>

          @endforeach

        @endif

      </div>

    </section>

    {{-- ===== EXPERT INDUSTRI ===== --}}
    <section class="container-dosen">

      <div class="section-header-dosen">
        <h2 class="judul-dosen-grid mb20">
          <span data-translate="industry-lecturers">Expert Industri</span>
        </h2>

        <p class="subjudul-dosen" data-translate="lecturers-expert-subtitle">
          Praktisi aktif dari industri yang membawa pengalaman nyata ke ruang kelas
        </p>
      </div>

      @php
        $expertIndustri = $dosens->where('type', 'Expert_industri');
      @endphp

      <div class="grid-dosen-wrapper" data-animate="slide-up" data-animate-delay="0.30s">

        @if ($expertIndustri->isEmpty())

          {{-- EMPTY STATE --}}
          <div class="kartu-dosen-grid empty-state">

            <div class="empty-ring">
              <i class="fa-regular fa-user"></i>
            </div>

            <div class="empty-text">
              <h3 data-translate="lecturers-expert-empty-title">Belum ada expert industri</h3>

              <p data-translate="lecturers-expert-empty-desc">
                Data expert industri belum tersedia saat ini.
                Data akan muncul setelah ditambahkan.
              </p>
            </div>

          </div>

        @else


          @foreach ($expertIndustri as $index => $dosen)

            <div class="kartu-dosen-grid js-open-person-modal" role="button" tabindex="0" data-name="{{ $dosen->name }}"
              data-photo="{{ asset('storage/' . $dosen->url_image) }}" data-alt="{{ $dosen->alt ?? $dosen->name }}"
              data-desc="{{ $dosen->deskripsi }}" data-type="{{ $dosen->type }}" data-animate="slide-right"
              data-animate-delay="{{ $index * 0.12 }}s">
              <div class="foto-dosen-wrap">

                <img src="{{ asset('storage/' . $dosen->url_image) }}" alt="{{ $dosen->alt ?? $dosen->name }}"
                  class="foto-dosen-bulat preview-image"
                  onerror="this.src='{{ asset('assets/images/placeholder-dosen.png') }}'">

                <div class="foto-dosen-overlay">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">

                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />

                  </svg>
                </div>

              </div>

              <div class="nama-dosen">
                {{ $dosen->name }}
              </div>

            </div>

          @endforeach

        @endif

      </div>

    </section>

  </div>

  {{-- ===== MODAL ===== --}}
  <div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">

    <div class="modal-dosen" id="modalDosen">

      <button class="modal-close" onclick="closeModal()" aria-label="Tutup" data-translate-attr="aria-label"
        data-translate-key="modal-close-label">

        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">

          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />

        </svg>

      </button>

      <div class="modal-inner">

        {{-- LEFT --}}
        <div class="modal-left">

          <div class="modal-foto-wrap">

            <img id="modalFoto" src="" alt="" class="modal-foto preview-image">

            <div class="modal-foto-bg"></div>

          </div>

          <div id="modalBadge" class="badge-dosen" style="margin-top:14px;">
          </div>

        </div>

        {{-- RIGHT --}}
        <div class="modal-right">

          <h3 class="modal-nama" id="modalNama">
            —
          </h3>

          <div class="modal-divider"></div>

          <div class="modal-bio-wrap" id="modalBioWrap" style="display:none;">

            <div class="modal-bio-label" data-translate="modal-about-label">
              Tentang
            </div>

            <div class="modal-bio" id="modalBio">
            </div>

          </div>

          <div class="modal-empty" id="modalEmpty" style="display:none;">

            <p data-translate="modal-lecturer-empty-desc">Belum ada deskripsi untuk dosen ini.</p>

          </div>

        </div>

      </div>

    </div>

  </div>

@endsection