<link rel="stylesheet" href="{{ asset('assets/css/main-style.css') }}">
@props(['newsItem', 'class' => 'news-card-default'])

@php
    $contentData = is_string($newsItem->content) ? json_decode($newsItem->content, true) : $newsItem->content;
    $firstImage = null;
    $summary = '';

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

    $title = $contentData['title'] ?? 'Untitled';
@endphp

<div class="news-card {{ $class }}">
    <a href="{{ route('berita.show', $newsItem->id) }}" style="text-decoration: none;">
        @if($firstImage)
            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $title }}" class="news-card-image preview-image">
        @else
            <div class="news-card-image"
                style="background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-image" style="font-size: 2rem; color: #999;"></i>
            </div>
        @endif
        <div class="news-content">
            <div class="news-title">{{ $title }}</div>
            <div class="news-date">
                {{ \Carbon\Carbon::parse($newsItem->published_at)->format('d F Y') }}
            </div>
            <div class="news-summary">
                {{ substr($summary, 0, 100) }}{{ strlen($summary) > 100 ? '...' : '' }}
            </div>
        </div>
    </a>
</div>