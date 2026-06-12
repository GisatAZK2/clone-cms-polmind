@if(isset($popup) && $popup)

    <div id="popupOverlay" class="pp-overlay" data-popup-id="{{ $popup->id }}">
        <div class="pp-modal" id="ppModal">

            <button class="pp-close" id="ppCloseBtn" aria-label="Tutup">✕</button>

            <div class="pp-container" id="ppContainer">

                @if($popup->url_image)
                    <div class="pp-img-wrap" id="ppImageWrap">
                        <img src="{{ asset('storage/' . $popup->url_image) }}" alt="{{ $popup->alt ?? 'Popup' }}" class="pp-img"
                            id="ppImage">
                    </div>
                @else
                    <div class="pp-header">
                        <div class="pp-icon">
                            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                        <p class="pp-header-sub">Politeknik Mitra Industri</p>
                        <p class="pp-header-title">{{ $popup->title ?? 'Informasi Penting' }}</p>
                    </div>
                @endif

                <div class="pp-content-wrap" id="ppContentWrap">
                    <div class="pp-body pp-tinymce-content" id="ppBody">
                        {!! $popup->content !!}
                    </div>
                    <div class="pp-read-fade" id="ppReadFade"></div>
                    <button class="pp-read-toggle" id="ppReadToggle">.... Lihat Selengkapnya</button>
                </div>

            </div>
        </div>
    </div>


@endif