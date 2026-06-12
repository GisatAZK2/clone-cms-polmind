@extends('pages.layouts.app')

@section('content')

  <!-- HERO SLIDER -->
  <section class="hero">
    <div class="hero-slider" id="heroSlider">
      <div class="hero-slides" id="heroSlides">
        @forelse($slides ?? [] as $idx => $slide)
          <div class="hero-slide">
            @if($slide->url_image && file_exists(public_path($slide->url_image)))
              <img class="hero-slide-img preview-image" src="{{ asset($slide->url_image) }}"
                alt="{{ $slide->alt ?? 'Slider image' }}" />
            @else
              <div class="hero-slide-placeholder" style="background:linear-gradient(160deg,#142d54,#1b3e76,#254e8c);">
                <div
                  style="position:absolute;inset:0;background-image:radial-gradient(circle at 75% 70%,rgba(48,101,171,.45) 0%,transparent 50%);">
                </div>
              </div>
            @endif
            <div class="hero-content">
              @if(!empty($slide->subtitle))
                <div class="hero-eyebrow">{{ $slide->subtitle }}</div>
              @endif
              <div class="hero-title-wrap">
                <div class="hero-title hero-title-tinymce">
                    {!! $slide->title !!}
                </div>
              </div>
              <div class="hero-actions">
                @if(isset($idx) && $idx === 0)
                  <a href="{{ route('pmb') }}" class="btn-headline" data-translate="btn-register">Daftar Sekarang</a>
                  <a href="#dosen" class="btn-hero-ghost" data-translate="btn-lecturers">Tim Dosen</a>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="hero-slide">
              <div class="hero-slide-placeholder" style="background:var(--grey-muted);">
              </div>
              <div class="hero-content">
                  <div class="hero-eyebrow" data-translate="home-welcome">
                      Selamat Datang di
                  </div>
                  <div class="hero-title hero-title-tinymce">
                      <h1>Politeknik Mitra Industri</h1>
                  </div>
                  <div class="hero-actions">
                      <a href="{{ route('pmb') }}" class="btn-primary">
                          Daftar Sekarang
                      </a>
                      <a href="#dosen" class="btn-outline">
                          Tim Dosen
                      </a>
                  </div>
              </div>
          </div>
        @endforelse
        </div>

        @if(($slides ?? collect())->count() > 1)
  <!-- Dots -->
  <div class="slider-dots" id="sliderDots">
    @foreach($slides as $idx => $slide)
      <div class="slider-dot {{ $idx === 0 ? 'active' : '' }}" data-idx="{{ $idx }}"></div>
    @endforeach
  </div>

  <!-- Controls -->
  <div class="slider-controls">
    <button class="slider-btn" id="heroPrev" aria-label="Slide sebelumnya">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="15 18 9 12 15 6" />
      </svg>
    </button>

    <button class="slider-btn" id="heroNext" aria-label="Slide berikutnya">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="9 18 15 12 9 6" />
      </svg>
    </button>
  </div>
@endif
        
      
  </section>

  @php
    $topPmbItem = null;
    $bottomPmbItem = null;
    $topPmbLink = route('pmb');
    $bottomPmbLink = route('pmb');

    $showTopPMB = false;
    $showBottomPMB = false;

    // === TOP (Type = Atas) ===
    if (!empty($contentPendaftaranAtas)) {
      $topPmbItem = $contentPendaftaranAtas->content[0] ?? null;

      if ($topPmbItem && $contentPendaftaranAtas->tahun_buka) {
        if (now()->gte($contentPendaftaranAtas->tahun_buka)) {
          $showTopPMB = true;
        }
      }
      $topPmbLink = $topPmbItem['link_url'] ?? route('pmb');
    }

    // === BOTTOM (Type = Bawah) ===
    if (!empty($contentPendaftaranBawah)) {
      $bottomPmbItem = $contentPendaftaranBawah->content[0] ?? null;

      if ($bottomPmbItem && $contentPendaftaranBawah->tahun_buka) {
        if (now()->gte($contentPendaftaranBawah->tahun_buka)) {
          $showBottomPMB = true;
        }
      }
      $bottomPmbLink = $bottomPmbItem['link_url'] ?? route('pmb');
    }
  @endphp

  @if($showTopPMB && $topPmbItem)
    <section class="pmb-top-section">
      <div class="pmb-strip pmb-strip--wide">
        <div class="pmb-strip-body">
          <div class="pmb-strip-content">
            <p class="pmb-strip-text">
              {!! $contentPendaftaranAtas->kata_kata ?? 'Pendaftaran Mahasiswa Baru — Klik di sini untuk mendaftar' !!}
            </p>
            <a href="{{ $topPmbLink }}" class="pmb-link">
              Lanjutkan Pendaftaran
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="9 18 15 12 9 6" />
              </svg>
            </a>
          </div>

          @if(isset($topPmbItem['url_images']) && $topPmbItem['url_images'])
            <div class="pmb-strip-image">
              <img src="{{ asset('storage/' . $topPmbItem['url_images']) }}" alt="{{ $topPmbItem['alt'] ?? 'PMB Image' }}"
                class="preview-image" />
              <span class="pmb-badge">PMB {{ $contentPendaftaranAtas->tahun_buka?->format('Y') ?? '' }}</span>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  @php
    $homeStats = App\Models\HomeStat::where('is_active', true)
      ->orderBy('order')
      ->orderBy('id')
      ->get();
  @endphp

  <!-- STATS BAR - DINAMIS -->
  @if($homeStats->count() > 0)
    <div class="stats-bar">
      @foreach($homeStats as $stat)
        <div class="stat-item" data-animate="fade-in" data-animate-delay="{{ $loop->index * 0.08 }}s">
          @php
            $iconClass = trim($stat->icon ?? '');

            if ($iconClass) {
              // Kalau admin isi "alarm-fill", jadikan "bi bi-alarm-fill"
              if (!\Illuminate\Support\Str::startsWith($iconClass, 'bi-') && !str_contains($iconClass, ' bi-')) {
                $iconClass = 'bi-' . $iconClass;
              }

              // Pastikan selalu punya base class "bi"
              if (!preg_match('/(^|\s)bi(\s|$)/', $iconClass)) {
                $iconClass = 'bi ' . $iconClass;
              }
            }
          @endphp
          @if($iconClass)
            <i class="{{ $iconClass }} stat-icon mb-2" aria-hidden="true"></i>
          @endif

          <div class="stat-num">{{ $stat->label }}</div>
          <div class="stat-label">{{ $stat->value }}</div>
        </div>
      @endforeach
    </div>
  @else
    <div class="stats-bar">
      @for($i = 0; $i < 4; $i++)
        <div class="stat-item stat-item--placeholder">
          <div class="stat-label" data-translate="stats-placeholder">Belum tersedia</div>
        </div>
      @endfor
    </div>
  @endif

  <!-- KEUNGGULAN -->
  <section class="section">

    <div class="anim-slide-down">
      <div class="section-title-group " data-delay="0">
        <div class="section-tag" data-translate="banner-why">Mengapa Polmind</div>
        <h2 class="section-heading" data-translate="advantages-title">
          Keunggulan yang Membedakan Kami
        </h2>
        <p class="section-sub" data-translate="advantages-subtitle">
          Pendekatan pendidikan berbasis industri nyata untuk
          mencetak lulusan yang siap kerja dan berdaya saing global.
        </p>
      </div>
    </div>

    @if($keunggulan->isNotEmpty())
      <div class="keunggulan-grid keunggulan-grid--cards">
        @foreach($keunggulan as $item)
          <article class="keunggulan-card keunggulan-card--media" data-animate="slide-right"
            data-animate-delay="{{ $loop->index * 0.10 }}s">
            @if($item->url_images)
              <div class="keunggulan-card-image">
                <img class="preview-image" src="{{ asset('storage/' . $item->url_images) }}"
                  alt="{{ $item->alt ?? 'Keunggulan Polmind' }}" />
              </div>
            @endif
            <div class="keunggulan-card-body">
              <div class="keunggulan-card-content">{!! $item->keunggulan !!}</div>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <div class="keunggulan-grid keunggulan-grid--cards">
        @for($i = 0; $i < 3; $i++)
          <article class="keunggulan-card keunggulan-card--media keunggulan-card--placeholder" data-animate="slide-right"
            data-animate-delay="{{ $i * 0.10 }}s">
            <div class="keunggulan-card-image">
              <div class="image-placeholder">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
              </div>
            </div>
            <div class="keunggulan-card-body">
              <div class="keunggulan-card-content">
                <div class="placeholder-title" data-translate="uniq-empty-title">Belum Diisi</div>
                <div class="placeholder-desc" data-translate="uniq-empty-desc">Konten belum tersedia untuk saat ini.</div>
              </div>
            </div>
          </article>
        @endfor
      </div>
    @endif
  </section>
  </div>

  <!-- FEATURE: PROJECT -->
  <section class="section-sm" id="project" style="background: var(--grey-bg);">
    <span class="projects-tag" data-translate="project-tag">Project</span>
    @forelse($projects ?? [] as $index => $project)
      @php
        $isReverse = $index % 2 === 1;
      @endphp
      <div class="feature-row {{ $isReverse ? 'reverse' : '' }}" style="margin-bottom: {{ $loop->last ? '0' : '32px' }};">
        <div class="feature-img-wrap" data-animate="slide-right" data-animate-delay="{{ $loop->index * 0.06 }}s">
          @if(!empty($project->url_images))
            <img class="preview-image" src="{{ asset('storage/' . $project->url_images) }}"
              alt="{{ $project->alt ?? $project->title ?? 'Project Polmind' }}"
              style="border-radius:24px; width:100%; height:100%; object-fit:cover;" />
          @else
            <div class="feature-img-placeholder" style="border-radius:24px;">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <polyline points="21 15 16 10 5 21" />
              </svg>
            </div>
          @endif
        </div>
        <div class="feature-text" data-animate="slide-left" data-animate-delay="{{ ($loop->index * 0.06) + 0.12 }}s">
          <span class="feature-tag" data-translate="real-projects-title">Real Industry Projects</span>
          <h3 class="feature-title">{{ $project->title ?? 'Project dari Perusahaan di Dalam & Luar Kawasan MM2100' }}</h3>
          <div class="feature-body">
            @if(!empty($project->deskripsi))
              {!! $project->deskripsi !!}
            @else
              <span data-translate="projects-body">Mahasiswa mengerjakan proyek nyata yang ditugaskan langsung oleh perusahaan
                mitra — bukan simulasi. Pengalaman ini membangun portofolio profesional dan koneksi industri sebelum
                lulus.</span>
            @endif
          </div>
          <div class="feature-detail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6" />
            </svg>
            <span data-translate="pjbl-label">Project-Based Learning (PjBL) kontekstual</span>
          </div>
        </div>
      </div>
    @empty
      <div class="feature-row">
        <div class="feature-img-wrap" data-animate="slide-right" data-animate-delay="0s">
          <div class="feature-img-placeholder" style="border-radius:24px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
        </div>
        <div class="feature-text" data-animate="slide-left" data-animate-delay="0.12s">
          <span class="feature-tag" data-translate="real-projects-title">Real Industry Projects</span>
          <h3 class="feature-title" data-translate="projects-desc">Project dari Perusahaan di Dalam & Luar Kawasan MM2100
          </h3>
          <div class="feature-body" data-translate="projects-body">
            Mahasiswa mengerjakan proyek nyata yang ditugaskan langsung oleh perusahaan mitra — bukan simulasi. Pengalaman
            ini membangun portofolio profesional dan koneksi industri sebelum lulus.
          </div>
          <div class="feature-detail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6" />
            </svg>
            <span data-translate="pjbl-label">Project-Based Learning (PjBL) kontekstual</span>
          </div>
        </div>
      </div>
    @endforelse
  </section>

  <!-- FEATURE: DOSEN -->
  <section class="section-sm" id="dosen">
    @php
      $homeProdiItems = $prodis ?? collect();
    @endphp

    @if($homeProdiItems->isNotEmpty())
      @foreach($homeProdiItems as $index => $prodi)
        @php
          $prodiContent = is_array($prodi->content) ? $prodi->content : [];
          $prodiTitle = $prodiContent['title'] ?? $prodiContent['alt'] ?? 'Program Studi';
          $prodiAlt = $prodiContent['alt'] ?? $prodiTitle;
          $prodiImagePath = !empty($prodiContent['url_images']) ? 'storage/' . $prodiContent['url_images'] : null;
          $prodiImageExists = $prodiImagePath && file_exists(public_path($prodiImagePath));

          $prodiDescriptions = array_values(array_filter($prodiContent['deskripsi'] ?? [], function ($item) {
            return !empty(trim((string) $item));
          }));

          $isLatestCard = $prodi->type === 'card'
            && $prodi->id === $homeProdiItems->where('type', 'card')->max('id');

          $showProdiText = $prodi->type === 'card';
          $isImageOnlyRow = $prodi->type === 'image';
        @endphp

        <div class="feature-row {{ $isImageOnlyRow ? 'feature-row-image-only' : 'reverse' }}" data-animate="slide-up"
          data-animate-delay="{{ $loop->index * 0.08 }}s" style="margin-bottom: {{ $loop->last ? '0' : '32px' }};">
          <div class="feature-img-wrap">
            @if($prodiImageExists)
              <img class="preview-image" src="{{ asset($prodiImagePath) }}" alt="{{ $prodiAlt }}"
                style="border-radius:24px; width:100%; height:100%; object-fit:cover;" />
            @else
              <div class="feature-img-placeholder" style="border-radius:24px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
              </div>
            @endif
          </div>

          @if($showProdiText)
            <div class="feature-text">
              @if($isLatestCard)
                <span class="feature-tag" data-translate="teaching-team">Tim Pengajar</span>
              @endif
              <h3 class="feature-title">{{ $prodiTitle }}</h3>

              @if($prodi->type === 'card' && $prodiDescriptions)
                @foreach($prodiDescriptions as $description)
                  <div class="feature-body home-prodi-content">{!! $description !!}</div>
                @endforeach
              @elseif(!empty($prodiContent['alt']))
                <p class="feature-body">{{ $prodiContent['alt'] }}</p>
              @else
                <p class="feature-body" data-translate="professionals-desc">Perpaduan dosen akademisi berpengalaman dengan expert
                  dan praktisi industri aktif. Mahasiswa belajar dari orang-orang yang benar-benar bekerja di bidangnya.</p>
              @endif

              @if($isLatestCard)
                <div class="feature-detail">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                  </svg>
                  <span data-translate="professionals-label">Mentoring langsung dari industri</span>
                </div>
              @endif
            </div>
          @endif
        </div>
      @endforeach
    @else
      <div class="feature-row reverse">
        <div class="feature-img-wrap">
          <div class="feature-img-placeholder" style="border-radius:24px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
        </div>
        <div class="feature-text">
          <span class="feature-tag" data-translate="teaching-team">Tim Pengajar</span>
          <h3 class="feature-title" data-translate="professionals-title">Dosen Profesional & Praktisi Industri Terbaik</h3>
          <p class="feature-body" data-translate="professionals-desc">Perpaduan dosen akademisi berpengalaman dengan expert
            dan praktisi industri aktif. Mahasiswa belajar dari orang-orang yang benar-benar bekerja di bidangnya.</p>
          <div class="feature-detail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6" />
            </svg>
            <span data-translate="professionals-label">Mentoring langsung dari industri</span>
          </div>
        </div>
      </div>
    @endif
  </section>

  <!-- FEATURE: KARAKTER -->
  <section class="section-sm" style="background: var(--grey-bg);">
    <div class="section-title-group" data-animate="slide-down">
      <div class="section-tag" data-translate="character-formation">Pembentukan Karakter</div>
      <h2 class="section-heading" data-translate="character-skills-title">Attitude & Life Skills Siap Kerja</h2>
      <p class="section-sub" data-translate="character-skills-desc">Tidak hanya hard skills teknis — kami mengutamakan
        pembentukan karakter, etika profesional, dan kemampuan hidup yang kuat sejak dini untuk menghadapi dunia kerja
        nyata.</p>
    </div>

    @if(!empty($karakters) && $karakters->isNotEmpty())
      @foreach($karakters as $index => $item)
        @php $isReverse = $index % 2 === 1; @endphp
        <div class="feature-row {{ $isReverse ? 'reverse' : '' }}" data-animate="slide-up"
          data-animate-delay="{{ $loop->index * 0.08 }}s"
          style="margin-bottom: {{ $loop->last ? '0' : '32px' }}; align-items: center;">
          <div class="feature-img-wrap">
            @if(!empty($item->url_image) && file_exists(public_path('storage/' . $item->url_image)))
              <img class="preview-image" src="{{ asset('storage/' . $item->url_image) }}"
                alt="{{ $item->alt ?? $item->nama_konten }}"
                style="border-radius:24px; width:100%; height:100%; object-fit:cover;" />
            @else
              <div class="feature-img-placeholder" style="border-radius:24px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
              </div>
            @endif
          </div>

          <div class="feature-text">
            <span class="feature-tag">{{ $item->nama_konten }}</span>
            <h3 class="feature-title">{{ $item->nama_konten }}</h3>
            @if(!empty($item->deskripsi))
              <div class="feature-body">{!! $item->deskripsi !!}</div>
            @else
              <p class="feature-body">Deskripsi karakter belum tersedia.</p>
            @endif
          </div>
        </div>
      @endforeach
    @else
      <div class="feature-row">
        <div class="feature-img-wrap">
          <div class="feature-img-placeholder" style="border-radius:24px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
        </div>
        <div class="feature-text">
          <span class="feature-tag" data-translate="character-formation">Pembentukan Karakter</span>
          <h3 class="feature-title" data-translate="character-skills-title">Attitude & Life Skills Siap Kerja</h3>
          <p class="feature-body" data-translate="character-skills-desc">Tidak hanya hard skills teknis — kami mengutamakan
            pembentukan karakter, etika profesional, dan kemampuan hidup yang kuat sejak dini untuk menghadapi dunia kerja
            nyata.</p>
          <div class="feature-detail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6" />
            </svg>
            <span data-translate="character-label">Soft skills & karakter terintegrasi kurikulum</span>
          </div>
        </div>
      </div>
    @endif
  </section>

  <!-- PRODI -->
  <section class="section prodi-section" id="prodi">
    <div class="section-title-group" data-animate="slide-down">
      <div class="section-tag" data-translate="study-programs-section">Program Studi</div>
      <h2 class="section-heading" data-translate="bachelor-degree-title">Sarjana Terapan (D4) Prospek Cerah</h2>
      <p class="section-sub" data-translate="study-programs-desc">Pilih program studi yang paling sesuai dengan minat dan
        potensi karir Anda di industri modern.</p>
    </div>
    <div class="prodi-img-wrap" data-animate="fade-in">
      <img class="preview-image" src="assets/images/prodi.png" alt="Program Studi D4 Polmind"
        onerror="this.parentElement.innerHTML='<div style=\'padding:60px;text-align:center;background:var(--grey-muted);border-radius:24px;color:var(--text-light);\'>Gambar Program Studi</div>'" />
    </div>
    <div class="prodi-quote" data-translate="employees-quote">
      Terbuka juga bagi Karyawan yang ingin melanjutkan studi Sarjana Terapan!
    </div>
  </section>

  <!-- NEWS -->
  <section class="section news-section" id="news">
    <div class="section-title-group">
      <div class="section-tag" data-translate="information-tag">Informasi</div>
      <a href="{{ route('berita.index') }}">
        <h2 style="cursor:pointer" class="section-heading" data-translate="latest-news-title">Berita Terkini</h2>
      </a>
      <p class="section-sub" data-translate="news-section-desc">Ikuti perkembangan terbaru dari kampus, kegiatan
        mahasiswa, dan dunia industri.</p>
    </div>
    <div class="news-filters d-flex flex-wrap gap-2 mb-4">
      <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn active" data-filter="Semua"
        data-translate="filter-all">
        Semua
      </button>
      <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Umum"
        data-translate="filter-general">
        Umum
      </button>
      <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Prestasi"
        data-translate="filter-achievement">
        Prestasi
      </button>
      <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Kerjasama"
        data-translate="filter-collaboration">
        Kerjasama
      </button>
    </div>
    <div class="news-slider-wrap">
      <div class="news-track" id="newsTrack">
        @forelse($latestNews as $item)
          @php
            $excerpt = $item->excerpt;
            $firstImage = null;
            if ($item->content) {
              $content = is_string($item->content) ? json_decode($item->content, true) : $item->content;
              if (is_array($content) && isset($content['blocks'])) {
                foreach ($content['blocks'] as $block) {
                  if (empty($excerpt) && $block['type'] === 'text') {
                    $excerpt = \Illuminate\Support\Str::limit(
                      html_entity_decode(strip_tags($block['content'])),
                      100
                    );
                  }
                  if (!$firstImage && $block['type'] === 'image' && isset($block['image'])) {
                    $firstImage = $block['image'];
                  }
                }
              }
            }
          @endphp
          <a href="{{ route('berita.show', $item->slug) }}" class="news-card news-link"
            data-jenis-content="{{ $item->jenis_content ?? 'Umum' }}" data-animate="fade-in"
            data-animate-delay="{{ $loop->index * 0.06 }}s">
            @if($firstImage)
              <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->title }}" class="news-img preview-image">
            @else
              <div class="news-img-placeholder"><svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="1.5">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg></div>
            @endif
            <div class="news-body">
              <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                @php
                  $categoryValue = $item->jenis_content ?? 'Umum';

                  $categoryTranslateKey = match ($categoryValue) {
                    'Umum' => 'category-umum',
                    'Prestasi' => 'category-prestasi',
                    'Kerjasama' => 'category-kerjasama',
                    default => 'category-umum',
                  };
                @endphp

                <div class="news-item-date">
                  <span class="badge bg-secondary text-white" data-translate="{{ $categoryTranslateKey }}">
                    {{ $categoryValue }}
                  </span>
                </div>

                <p class="news-date mb-0 text-muted">{{ $item->created_at->format('d M Y') }}</p>
              </div>
              @php
                $content = is_string($item->content) ? json_decode($item->content, true) : $item->content;

                $title = $content['title'] ?? 'Tanpa judul';
              @endphp
              <h3 class="news-title">{{ $title }}</h3>
              <p class="news-excerpt">{{ $excerpt ?? 'Tanpa deskripsi' }}</p>
            </div>
          </a>
        @empty
          <div class="news-card news-card-empty">
            <div class="news-img-placeholder"><svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <polyline points="21 15 16 10 5 21" />
              </svg></div>
            <div class="news-body news-body-empty">
              <p class="news-empty-label" data-translate="news-no-data">
                Belum ada berita
              </p>

              <h3 class="news-title news-title-empty" data-translate="news-coming-soon">
                Berita akan segera hadir
              </h3>

              <p class="news-excerpt news-excerpt-empty" data-translate="news-no-description">
                Pantau halaman ini untuk informasi terbaru seputar kampus, kegiatan mahasiswa, dan update dari Politeknik Mitra Industri.
              </p>
            </div>
          </div>
        @endforelse
      </div>
    </div>
    <div id="newsEmptyMessage" class="news-empty" data-translate="news-empty-filter" style="display:none;">Tidak Ada
      berita yang Sesuai Filter</div>
    <div class="news-controls">
      <button class="news-ctrl-btn" id="newsLeft" aria-label="News sebelumnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>
      <button class="news-ctrl-btn" id="newsRight" aria-label="News berikutnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </button>
    </div>
  </section>

  <!-- PARTNER -->
  <section class="section partner-section" id="partner">
    <div class="section-title-group">
      <div class="section-tag" data-translate="ecosystem-tag">Ekosistem Industri</div>
      <h2 class="section-heading" data-translate="our-partners">Mitra Kami</h2>
      <p class="section-sub" data-translate="partners-desc">
        Didukung ratusan perusahaan industri terkemuka di dalam dan sekitar kawasan MM2100.
      </p>
    </div>

    <div class="partner-marquee-wrapper">
      <div class="partner-marquee-track">
        @if(!empty($partners) && $partners->isNotEmpty())
          @foreach($partners as $partner)
            <div class="partner-card" data-animate="fade-in" data-animate-delay="{{ $loop->index * 0.04 }}s">
              <img src="{{ asset('storage/' . $partner->url_images) }}" alt="{{ $partner->alt ?? $partner->nama_mitra }}"
                title="{{ $partner->nama_mitra }}" class="partner-logo preview-image" data-modal-skip="true"
                onerror="this.style.display='none'" />
            </div>
          @endforeach

          @php
            $visibleSlots = 8;
            $existing = is_countable($partners) ? count($partners) : $partners->count();
            $placeholders = max(0, $visibleSlots - $existing);
          @endphp

          @for($i = 0; $i < $placeholders; $i++)
            <div class="partner-card partner-card--skeleton">
              <div class="partner-logo-placeholder"
                style="width:100%;height:80px;border-radius:8px;background:linear-gradient(90deg,#eef3fb,#f8fbff);display:flex;align-items:center;justify-content:center;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#bfd2f7" stroke-width="1.2">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
              </div>
              <div style="text-align:center;color:var(--blue-light);font-size:12px;margin-top:8px;">Belum Terisi</div>
            </div>
          @endfor

        @else
          @for($i = 0; $i < 4; $i++)
            <div class="partner-card partner-card--skeleton">
              <div class="partner-logo-placeholder"
                style="width:100%;height:80px;border-radius:8px;background:linear-gradient(90deg,#eef3fb,#f8fbff);display:flex;align-items:center;justify-content:center;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#bfd2f7" stroke-width="1.2">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
              </div>
              <div style="text-align:center;color:var(--muted);font-size:12px;margin-top:8px;">Belum Terisi</div>
            </div>
          @endfor
        @endif
      </div>
    </div>
  </section>


  <!-- SAMBUTAN DIREKTUR -->
  <section class="sambutan-section" id="sambutan">
    <div class="sambutan-inner">
      <div class="sambutan-photo" data-animate="fade-in">
        @if($sambutan && !empty($sambutan->content['foto_direktur']))
          <img src="{{ asset('storage/' . $sambutan->content['foto_direktur']) }}" alt="Polmind" class="preview-image" />
        @else
          <img src="assets/images/sambutan.png" alt="Direktur Polmind" class="preview-image" />
        @endif
      </div>

      <div class="sambutan-content" data-animate="slide-up" data-animate-delay="0.08s">
        <h2 class="sambutan-heading">
          {{ $sambutan->content['judul_sambutan'] ?? 'Sambutan' }}<br>
        </h2>

        <div class="sambutan-card">
          {!! $sambutan->content['kata_sambutan'] ?? '
                        <p>Mari bergabung dengan <strong>Polmind</strong>, mencetak SDM/lulusan unggul dan berdaya saing global untuk Indonesia yang lebih baik.</p>
                    ' !!}
        </div>
      </div>
    </div>
  </section>

  <!-- CTA SECTION -->
  @if($showBottomPMB && $bottomPmbItem)
    <section class="cta-section cta-section--pmb">
      <div class="cta-grid">
        <div class="cta-content">
          <p class="cta-tag">Pendaftaran Mahasiswa Baru</p>
          <h2 class="cta-title">
            {!! $contentPendaftaranBawah->kata_kata ?? 'Siap Bergabung dengan Polmind ' . ($contentPendaftaranBawah->tahun_buka?->format('Y') ?? '2026') . '?' !!}
          </h2>
          <p class="cta-sub" data-translate="cta-pmb-desc">
            Klik tombol daftar untuk melanjutkan ke halaman pendaftaran resmi dan lengkapi data Anda.
          </p>
          <div class="cta-actions">
            <a href="{{ $bottomPmbLink }}" class="cta-btn-white" data-translate="register-btn-cta">Daftar Sekarang →</a>
          </div>
        </div>

        <div class="cta-form-panel">
          @if(isset($bottomPmbItem['url_images']) && $bottomPmbItem['url_images'])
            <div class="cta-image-wrapper">
              <img src="{{ asset('storage/' . $bottomPmbItem['url_images']) }}"
                alt="{{ $bottomPmbItem['alt'] ?? 'Banner pendaftaran' }}" class="cta-image preview-image" />
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif



@endsection