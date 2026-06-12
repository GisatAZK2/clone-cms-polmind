@props(['paginator', 'class' => 'pagination-wrapper'])

<div class="{{ $class }}">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true">
                <span class="page-link">← Sebelumnya</span>
            </li>
        @else
            <li>
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Sebelumnya</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            {{-- Three Dots --}}
            @if ($page == $paginator->currentPage() + 2 && $page != $paginator->lastPage() - 1)
                <li class="disabled" aria-disabled="true">
                    <span class="page-link">...</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if ($page >= $paginator->currentPage() - 1 && $page <= $paginator->currentPage() + 1)
                @if ($page == $paginator->currentPage())
                    <li class="active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li>
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endif

            {{-- Three Dots --}}
            @if ($page == $paginator->currentPage() - 2 && $page != 2)
                <li class="disabled" aria-disabled="true">
                    <span class="page-link">...</span>
                </li>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Selanjutnya →</a>
            </li>
        @else
            <li class="disabled" aria-disabled="true">
                <span class="page-link">Selanjutnya →</span>
            </li>
        @endif
    </ul>
</div>

<style>
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
        margin-bottom: 2rem;
    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        border: 1px solid #ddd;
        color: #102C53;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .pagination .page-link:hover {
        background-color: #f0f0f0;
        border-color: #102C53;
    }

    .pagination .active .page-link {
        background-color: #102C53;
        color: white;
        border-color: #102C53;
    }

    .pagination .disabled .page-link {
        color: #ccc;
        cursor: not-allowed;
        background-color: #f5f5f5;
    }

    @media (max-width: 768px) {
        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .pagination {
            gap: 0.25rem;
        }
    }
</style>