@extends('pages.layouts.app')
@section('content')

    <div class="news-list-container">
        <div class="news-list-header">
            <h1 data-translate="news-list-title">Berita Terkini</h1>
            <p data-translate="news-list-subtitle">Update terbaru dan informasi penting dari Politeknik Mitra Industri</p>
        </div>

        <div class="news-filters d-flex flex-wrap gap-2 mb-4">
            <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn active" data-filter="Semua" data-translate="filter-all">
            Semua
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Umum" data-translate="filter-general">
            Umum
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Prestasi" data-translate="filter-achievement">
            Prestasi
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm news-filter-btn" data-filter="Kerjasama" data-translate="filter-collaboration">
            Kerjasama
            </button>
        </div>

        @if($news->count() > 0)
            <div class="news-grid">
                @foreach($news as $item)
                    @php
                        $contentData = is_string($item->content)
                            ? json_decode($item->content, true)
                            : $item->content;

                        $firstImage = null;
                        $summary    = '';

                        if (is_array($contentData) && isset($contentData['blocks'])) {
                            foreach ($contentData['blocks'] as $block) {
                                if ($block['type'] === 'text' && empty($summary)) {
                                    $summary = strip_tags($block['content']);
                                }
                                if ($block['type'] === 'image' && !$firstImage && isset($block['image'])) {
                                    $firstImage = $block['image'];
                                }
                            }
                        }

                        $itemTitle  = $contentData['title'] ?? 'Untitled';
                        $itemUrl    = $item->public_url;
                        $waShare    = 'https://wa.me/?text=' . urlencode($itemTitle) . '%20' . urlencode($itemUrl);
                        $twShare    = 'https://twitter.com/intent/tweet?url=' . urlencode($itemUrl) . '&text=' . urlencode($itemTitle);
                    @endphp

                    <div class="news-item" data-jenis-content="{{ $item->jenis_content ?? 'Umum' }}">
                        {{-- Thumbnail --}}
                        <a href="{{ $itemUrl }}"
                            class="news-thumb-link"
                            data-url="{{ $itemUrl }}"
                            data-image="{{ $firstImage ? asset('storage/' . $firstImage) : '' }}"
                            data-title="{{ e($itemTitle) }}">
                                @if($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}"
                                        alt="{{ $itemTitle }}"
                                        class="news-item-image"
                                        data-modal-skip="true">
                                @else
                                    <div class="news-item-image"
                                        style="background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image" style="font-size: 2rem; color: #999;"></i>
                                    </div>
                                @endif
                        </a>

                        <div class="news-item-content">
            
                             <a href="{{ $itemUrl }}" style="text-decoration: none;">
                                <h3 class="news-item-title">{!! $itemTitle !!}</h3>
                            </a>

                            <div class="news-item-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                            </div>

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
                                <span data-translate="{{ $categoryTranslateKey }}">
                                    {{ $categoryValue }}
                                </span>
                            </div>

                            <p class="news-item-summary">
                                {{ mb_substr($summary, 0, 150) }}{{ mb_strlen($summary) > 150 ? '...' : '' }}
                            </p>

                            <div class="news-item-footer">
                                <a href="{{ $itemUrl }}" class="news-item-link" data-translate="news-read-more">
                                    Baca Selengkapnya →
                                </a>
                                <div class="share-inline">
                                    <a href="{{ $waShare }}"
                                       target="_blank" rel="noopener"
                                       class="share-inline-btn whatsapp"
                                       title="Bagikan via WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="{{ $twShare }}"
                                       target="_blank" rel="noopener"
                                       class="share-inline-btn twitter"
                                       title="Bagikan via X">
                                        <i class="bi bi-twitter-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="newsEmptyMessage" class="news-empty" data-translate="news-empty-filter" style="display:none;">Tidak Ada berita yang Sesuai Filter</div>

            @if($news->hasPages())
                <div class="pagination-wrapper">
                    {{ $news->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📰</div>
                <h3 data-translate="news-empty-title">Belum Ada Berita</h3>
                <p data-translate="news-empty-desc">Berita terbaru akan segera kami publikasikan</p>
            </div>
        @endif
    </div>
@endsection