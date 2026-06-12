@extends('pages.layouts.app')

@section('content')

  {{-- ==================== HERO HEADER ==================== --}}
  <div class="keunikan-hero">
    <div class="keunikan-hero-inner">
      <h1 class="keunikan-hero-title" data-translate="uniq-title">
        Keunikan &amp; Keunggulan
      </h1>

      <p class="keunikan-hero-sub" data-translate="uniq-hero-subtitle">
        Pendekatan pendidikan berbasis industri nyata — bukan sekadar teori di dalam kelas.
      </p>
    </div>

    <div class="keunikan-hero-shape"></div>
  </div>

  {{-- ==================== KONTEN DINAMIS / FALLBACK STATIC ==================== --}}
  @php
    /*
      Ambil hanya item DB yang benar-benar punya blocks.
      Jadi:
      - kalau DB kosong => fallback static tampil
      - kalau DB ada tapi content/blocks kosong => fallback static tampil
      - kalau DB ada dan blocks valid => tampil data DB
    */
    $validItems = collect($items ?? [])->filter(function ($item) {
      $content = $item->content ?? [];

      if (is_string($content)) {
        $content = json_decode($content, true) ?: [];
      }

      $blocks = $content['blocks'] ?? [];

      return is_array($blocks) && count($blocks) > 0;
    })->values();

    $hasDatabaseData = $validItems->count() > 0;
  @endphp

  <div class="keunikan-page">
    <div class="keunikan-container">

      @if($hasDatabaseData)

        @foreach($validItems as $itemIdx => $item)
          @php
            $content = $item->content ?? [];

            if (is_string($content)) {
              $content = json_decode($content, true) ?: [];
            }

            $blocks = $content['blocks'] ?? [];

            $title = $content['title'] ?? null;

            $paragraphBlocks = collect($blocks)->filter(function ($block) {
              return ($block['type'] ?? null) === 'paragraph' && !empty($block['value']);
            })->values();

            $imageBlocks = collect($blocks)->filter(function ($block) {
              return ($block['type'] ?? null) === 'image' && !empty($block['path']);
            })->values();
          @endphp

          <div class="keunikan-section {{ $itemIdx % 2 === 1 ? 'keunikan-section--alt' : '' }}">

            {{-- Judul item --}}
            @if($title)
              <div class="keunikan-section-title">
                <span class="keunikan-section-num">
                  {{ str_pad($itemIdx + 1, 2, '0', STR_PAD_LEFT) }}
                </span>

                <h2>{{ $title }}</h2>
              </div>
            @endif

            <div
              class="keunikan-section-body {{ $imageBlocks->count() ? 'has-image' : '' }} {{ $itemIdx % 2 === 1 ? 'reverse' : '' }}">

              {{-- Teks / Paragraf --}}
              @if($paragraphBlocks->count())
                <div class="keunikan-text-wrap">
                  @foreach($paragraphBlocks as $block)
                    <div class="keunikan-paragraph">
                      {!! $block['value'] !!}
                    </div>
                  @endforeach
                </div>
              @endif

              {{-- Gambar --}}
              @if($imageBlocks->count())
                <div class="keunikan-image-wrap">
                  @foreach($imageBlocks as $img)
                    @php
                      $imgAlt = $img['alt'] ?? ($title ?? 'Keunikan Polmind');
                      $useDefaultAlt = empty($img['alt']) && empty($title);
                    @endphp

                    <div class="keunikan-img-card">
                      <img src="{{ asset('storage/' . $img['path']) }}" alt="{{ $imgAlt }}" @if($useDefaultAlt)
                      data-translate-attr="alt" data-translate-key="uniq-image-alt" @endif class="keunikan-img preview-image"
                        loading="lazy" onerror="this.parentElement.classList.add('img-error')">

                      @if(!empty($img['alt']))
                        <div class="keunikan-img-caption">
                          {{ $img['alt'] }}
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
              @endif

            </div>
          </div>
        @endforeach

      @else

        {{-- Empty state ketika konten belum tersedia --}}
        <div class="keunikan-section keunikan-section--empty">
          <div class="keunikan-empty-state">
            <p class="preview-empty-title" data-translate="uniq-empty-title">
              Belum Diisi
            </p>

            <p class="preview-empty-desc" data-translate="uniq-empty-desc">
              Konten keunikan &amp; keunggulan belum tersedia.
            </p>
          </div>
        </div>

      @endif

    </div>
  </div>

@endsection