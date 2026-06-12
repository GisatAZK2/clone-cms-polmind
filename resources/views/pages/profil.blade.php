@extends('pages.layouts.app')

@section('content')

  @php
    $cover = \App\Models\Profile_Page::where('type', 'cover')->first();
    $visiMisi = \App\Models\Profile_Page::where('type', 'visi_misi')->first();
    $jajarans = \App\Models\Profile_Page::where('type', 'profile')->get();
  @endphp

  {{-- ==================== HERO COVER ==================== --}}
  <div class="profil-hero">
    @if($cover && $cover->url_images)
      <img class="profil-hero-img preview-image" src="{{ Storage::url($cover->url_images) }}"
        alt="{{ $cover->alt ?? 'Politeknik Mitra Industri' }}" data-translate="profil-banner-alt">
    @else
      <img class="profil-hero-img preview-image" src="{{ asset('assets/images/b_profil.png') }}"
        alt="Politeknik Mitra Industri menerapkan 5 nilai dan budaya industri 6S" data-translate="profil-banner-alt">
    @endif
    <div class="profil-hero-overlay" data-animate="fade-in" data-animate-delay="0.22s"></div>
    <div class="profil-hero-label" data-animate="slide-right" data-animate-delay="0.32s">
      <span data-translate="profil-hero-label">Profil Institusi</span>
    </div>
  </div>


  {{-- ==================== DECREE STRIP ==================== --}}
  <div class="profil-decree-strip">
    <svg class="decree-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
      <polyline points="14 2 14 8 20 8" />
      <line x1="16" y1="13" x2="8" y2="13" />
      <line x1="16" y1="17" x2="8" y2="17" />
      <polyline points="10 9 9 9 8 9" />
    </svg>
    <p data-translate="profil-decree">
      Berdasarkan Keputusan Menteri DIKTI SAINTEK No 324/B/O/2025
    </p>
  </div>


  <div class="baseColor">
    {{-- Visi Misi Section --}}
    <section class="vm-section">
      <div class="vm-inner" data-animate="slide-up" data-animate-delay="0.50s">

        <div class="vm-left">

          {{-- Logo + Judul VISI MISI --}}
          <div class="vm-heading-wrap" data-animate="slide-right" data-animate-delay="0.62s">
            <img src="/assets/images/logo-white.png" alt="POLITEKNIK MITRA INDUSTRI" class="vm-logo preview-image">
            <div class="vm-heading">
              <div><span class="vm-visi-word" data-translate="profil-visi-heading">VISI</span></div>
              <div><span class="vm-misi-word" data-translate="profil-misi-heading">MISI</span></div>
            </div>
          </div>

          <p class="vm-faculty-name" data-animate="fade-in" data-animate-delay="0.74s">
            Politeknik<br>Mitra Industri
          </p>

          @php $visiMisi = \App\Models\Profile_Page::where('type', 'visi_misi')->first(); @endphp
          @if($visiMisi)
            <div class="vm-visi-card" data-animate="slide-up" data-animate-delay="0.82s">
              <div class="vm-visi-card-label">
                <i class="fa-solid fa-eye"></i>
                <span data-translate="profil-visi-label">Visi</span>
              </div>
              <p class="vm-visi-text">{!! $visiMisi->visi !!}</p>
            </div>
          @endif

        </div>

        {{-- Kolom Kanan: Misi --}}
        @if($visiMisi)
          <div class="vm-right" data-animate="slide-left" data-animate-delay="0.88s">
            <div class="vm-misi-heading">
              <div>
                <div class="vm-misi-heading-text">MISI</div>
              </div>
            </div>
            <hr class="vm-misi-divider">
            <ol class="vm-misi-list">
              {!! $visiMisi->misi !!}
            </ol>
          </div>
        @endif

      </div>
  </div>
  </section>

  {{-- ==================== JAJARAN PENDIRI ==================== --}}
  <section class="profil-jajaran" id="jajaran">
    <div class="jajaran-inner">

      <div class="jajaran-header">
        <h2 class="jajaran-title" data-animate="slide-right" data-animate-delay="1.00s">
          <span data-translate="profil-founders">Jajaran Pendiri</span>
          &amp; <em data-translate="profil-experts">Experts</em>
        </h2>
        <p class="jajaran-sub" data-translate="profil-desc" data-animate="fade-in" data-animate-delay="1.08s">
          Politeknik Mitra Industri didirikan oleh jajaran pimpinan dan <em>expert</em> dari Industri, bersama Praktisi
          Pendidikan Vokasi.
        </p>
      </div>

      @if($jajarans->isNotEmpty())
        <div class="jajaran-grid">
          @foreach($jajarans as $index => $item)
            <div class="jajaran-card" data-animate="slide-up" data-animate-delay="{{ $index * 0.12 }}s"
              style="--delay: {{ $index * 0.12 }}s">
              <div class="jajaran-card-foto-wrap">
                @if(!empty($item->url_images))
                  <img class="jajaran-card-foto preview-image" src="{{ Storage::url($item->url_images) }}"
                    alt="{{ $item->content['nama_profil'] ?? '' }}"
                    onerror="this.src='{{ asset('assets/images/default-person.jpg') }}'">
                @else
                  <img class="jajaran-card-foto preview-image" src="{{ asset('assets/images/default-person.jpg') }}" alt="">
                @endif
                <div class="jajaran-card-foto-shine"></div>
              </div>
              <div class="jajaran-card-body">
                <h3 class="jajaran-card-nama">
                  {{ $item->content['nama_profil'] ?? 'Nama tidak tersedia' }}
                </h3>
                <div class="jajaran-card-desc">
                  {!! $item->content['deskripsi_profile'] ?? '<em>Tidak ada deskripsi</em>' !!}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="jajaran-empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
          </svg>
          <p data-translate="profil-founders-empty">Data Jajaran Pendiri &amp; Experts sedang disiapkan.</p>
        </div>
      @endif

    </div>
  </section>

@endsection