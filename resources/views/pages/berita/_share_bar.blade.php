@php
    $shareUrl   = urlencode($og_url ?? $canonical_url ?? url()->current());
    $shareTitle = urlencode($og_title ?? $news->content['title'] ?? 'Berita Polmind');
@endphp

<div class="share-bar">
    <span class="share-bar-label">
        <i class="bi bi-share"></i>
        <span data-translate="share-label">Bagikan:</span>
    </span>

    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
       target="_blank"
       rel="noopener"
       class="share-btn whatsapp">
        <i class="bi bi-whatsapp"></i>
        <span data-translate="share-whatsapp">WhatsApp</span>
    </a>

    <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}"
       target="_blank"
       rel="noopener"
       class="share-btn telegram">
        <i class="bi bi-telegram"></i>
        <span data-translate="share-telegram">Telegram</span>
    </a>

    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
       target="_blank"
       rel="noopener"
       class="share-btn twitter">
        <i class="bi bi-twitter-x"></i>
        <span data-translate="share-twitter">Tweet</span>
    </a>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
       target="_blank"
       rel="noopener"
       class="share-btn facebook">
        <i class="bi bi-facebook"></i>
        <span data-translate="share-facebook">Facebook</span>
    </a>

<button type="button"
        onclick="copyShareUrl(this)"
        class="share-btn copy">
    <i class="bi bi-link-45deg"></i>
    <span data-translate="share-copy-link">Salin Link</span>
</button>
</div>