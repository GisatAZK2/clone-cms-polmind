@extends('pages.layouts.app')

{{-- ── OGP / Social Meta ── --}}
@section('meta')
    <meta property="og:type"        content="article">
    <meta property="og:title"       content="{{ $og_title }}">
    <meta property="og:description" content="{{ $og_description }}">
    <meta property="og:image"       content="{{ $og_image }}">
    <meta property="og:url"         content="{{ $og_url }}">
    <meta property="og:site_name"   content="Politeknik Mitra Industri">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $og_title }}">
    <meta name="twitter:description" content="{{ $og_description }}">
    <meta name="twitter:image"       content="{{ $og_image }}">
    <link rel="canonical" href="{{ $canonical_url }}">
@endsection

@section('content')
    

    <div class="news-detail-container">

        {{-- ── HEADER ── --}}
        <div class="news-detail-header">
            <div class="news-detail-meta">
                <span>
                    <i class="bi bi-calendar3"></i>
                    {{ \Carbon\Carbon::parse($news->published_at)->format('d M Y') }}
                </span>
                <span data-translate-time="true" data-timestamp="{{ $news->published_at }}">
                    <i class="bi bi-clock"></i>
                    <span class="time-display">...</span>
                </span>
                <span>
                    <i class="bi bi-person"></i>
                     <span data-translate="news-author"> Penulis : </span>{{ $news->author ?? 'Admin' }}
                </span>
            </div>
            <h1 class="news-detail-title">
                {!! $news->content['title'] ?? 'Berita' !!}
            </h1>

        </div>

        {{-- ── FEATURED IMAGE ── --}}
        @if($news->url_image)
            <img src="{{ asset('storage/' . $news->url_image) }}"
                 alt="{{ $news->alt }}"
                 class="news-detail-image preview-image">
        @endif

        {{-- ── CONTENT BLOCKS ── --}}
        <div class="news-detail-content">
            @php
                $contentData = is_string($news->content)
                    ? json_decode($news->content, true)
                    : $news->content;

                if (is_array($contentData) && isset($contentData['blocks'])) {
                    foreach ($contentData['blocks'] as $block) {
                        if ($block['type'] === 'text' && isset($block['content'])) {
                            echo '<div>' . $block['content'] . '</div>';
                        } elseif ($block['type'] === 'image' && isset($block['image'])) {
                            $alt = $block['alt'] ?? 'Gambar';
                            echo '<figure>
                                    <img src="' . asset('storage/' . $block['image']) . '"
                                         alt="' . e($alt) . '"
                                         class="preview-image"
                                         style="max-width:100%;height:auto;border-radius:8px;">
                                    ' . ($block['alt'] ? '<figcaption>' . e($block['alt']) . '</figcaption>' : '') . '
                                  </figure>';
                        }
                    }
                }
            @endphp
        </div>

        {{-- ── FOOTER / SHARE + BACK ── --}}
        <div class="news-detail-footer">
            <a href="{{ route('berita.index') }}" class="news-detail-back" data-translate="news-back-link">
                ← Kembali ke Daftar Berita
            </a>
            {{-- Share bar bawah --}}
            @include('pages.berita._share_bar')
        </div>

        {{-- ── RELATED NEWS ── --}}
        @if(isset($relatedNews) && $relatedNews->count() > 0)
            <div class="related-news">
                <h2 data-translate="news-related-title">Berita Terkait</h2>
                <div class="related-news-grid">
                    @foreach($relatedNews as $relatedItem)
                        @php
                           $relContent = is_string($relatedItem->content)
    ? json_decode($relatedItem->content, true)
    : $relatedItem->content;

$relImage = null;
$relExcerpt = null;

if (is_array($relContent) && isset($relContent['blocks'])) {
    foreach ($relContent['blocks'] as $relBlock) {

        // ambil text pertama jadi excerpt
        if (
            !$relExcerpt &&
            $relBlock['type'] === 'text' &&
            isset($relBlock['content'])
        ) {
            $relExcerpt = \Illuminate\Support\Str::limit(
                html_entity_decode(strip_tags($relBlock['content'])),
                100
            );
        }

        // ambil image pertama
        if (
            !$relImage &&
            $relBlock['type'] === 'image' &&
            isset($relBlock['image'])
        ) {
            $relImage = $relBlock['image'];
        }
    }
}
                        @endphp
                        <a href="{{ $relatedItem->public_url }}" class="related-news-item">
                            <div class="related-news-thumb">
                                @if($relImage)
                                    <img src="{{ asset('storage/' . $relImage) }}"
                                         alt="{{ $relContent['title'] ?? '' }}">
                                @else
                                    <div class="related-news-thumb-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="related-news-item-content">
                                <div class="related-news-item-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($relatedItem->published_at)->format('d M Y') }}
                                </div>
                                <h3 class="related-news-item-title">
                                    {!! $relContent['title'] ?? 'Berita' !!}
                                </h3>
                                <p class="related-news-item-excerpt">
                                    {{ $relExcerpt ?? 'Tanpa deskripsi' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <script>
    function getCurrentLang() {
        return localStorage.getItem('polmind_lang') || document.documentElement.lang || 'id';
    }

    function getTranslation(key, fallback = '') {
        const lang = getCurrentLang();

        return translations?.[lang]?.[key] || fallback;
    }

    function copyShareUrl(button) {
        const url = window.location.href;
        const textSpan = button.querySelector('span[data-translate]');

        navigator.clipboard.writeText(url).then(() => {
            const originalKey = textSpan.getAttribute('data-translate');
            const originalText = getTranslation(originalKey, textSpan.textContent);
            const copiedText = getTranslation('share-copied', 'Disalin!');

            button.classList.add('copied');
            textSpan.textContent = copiedText;

            setTimeout(() => {
                button.classList.remove('copied');
                textSpan.textContent = originalText;
            }, 2000);
        });
    }

    // === TIME TRANSLATION FUNCTION ===
    function getTranslatedTimeAgo(timestamp) {
        const publishedDate = new Date(timestamp);
        const now = new Date();
        const diffMs = now - publishedDate;
        const diffSecs = Math.floor(diffMs / 1000);
        const diffMins = Math.floor(diffSecs / 60);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);
        const diffWeeks = Math.floor(diffDays / 7);
        const diffMonths = Math.floor(diffDays / 30);
        const diffYears = Math.floor(diffDays / 365);

        let translationKey = '';

        if (diffSecs < 60) {
            if (diffSecs < 1) {
                translationKey = 'time-just-now';
            } else if (diffSecs === 1) {
                translationKey = 'time-1-second-ago';
            } else {
                translationKey = 'time-seconds-ago';
            }
        } else if (diffMins < 60) {
            translationKey = diffMins === 1 ? 'time-1-minute-ago' : 'time-minutes-ago';
        } else if (diffHours < 24) {
            translationKey = diffHours === 1 ? 'time-1-hour-ago' : 'time-hours-ago';
        } else if (diffDays < 7) {
            translationKey = diffDays === 1 ? 'time-1-day-ago' : 'time-days-ago';
        } else if (diffWeeks < 4) {
            translationKey = diffWeeks === 1 ? 'time-1-week-ago' : 'time-weeks-ago';
        } else if (diffMonths < 12) {
            translationKey = diffMonths === 1 ? 'time-1-month-ago' : 'time-months-ago';
        } else {
            translationKey = diffYears === 1 ? 'time-1-year-ago' : 'time-years-ago';
        }

        let translation = getTranslation(translationKey, '');
        
        // Replace {count} placeholder with actual count
        let count = diffSecs;
        if (diffMins >= 1) count = diffMins;
        if (diffHours >= 1) count = diffHours;
        if (diffDays >= 1) count = diffDays;
        if (diffWeeks >= 1) count = diffWeeks;
        if (diffMonths >= 1) count = diffMonths;
        if (diffYears >= 1) count = diffYears;

        translation = translation.replace('{count}', count);

        return translation;
    }

    // Initialize translated time display
    function initializeTimeDisplay() {
        const timeElements = document.querySelectorAll('[data-translate-time="true"]');
        timeElements.forEach(element => {
            const timestamp = element.getAttribute('data-timestamp');
            if (timestamp) {
                const timeDisplay = element.querySelector('.time-display');
                if (timeDisplay) {
                    timeDisplay.textContent = getTranslatedTimeAgo(timestamp);
                }
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initializeTimeDisplay);

    // Re-initialize when language changes
    window.addEventListener('languageChanged', initializeTimeDisplay);
    </script>

@endsection