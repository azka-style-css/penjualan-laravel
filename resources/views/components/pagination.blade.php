@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="pager">
        <p class="pager-info">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="pager-links">
            @if ($paginator->onFirstPage())
                <span class="pager-btn is-disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pager-btn" rel="prev" aria-label="Halaman sebelumnya">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pager-btn is-disabled hidden sm:inline-flex" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pager-btn is-active hidden sm:inline-flex" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pager-btn hidden sm:inline-flex" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pager-btn" rel="next" aria-label="Halaman berikutnya">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </a>
            @else
                <span class="pager-btn is-disabled" aria-disabled="true" aria-label="Halaman berikutnya">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
