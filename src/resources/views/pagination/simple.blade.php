@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Pagination Navigation">
        <ul class="pager__list">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <li class="pager__item is-disabled"><span class="pager__btn">‹</span></li>
            @else
                <li class="pager__item">
                    <a class="pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pager__item is-disabled"><span class="pager__btn">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pager__item is-active"><span class="pager__btn">{{ $page }}</span></li>
                        @else
                            <li class="pager__item"><a class="pager__btn"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="pager__item">
                    <a class="pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                </li>
            @else
                <li class="pager__item is-disabled"><span class="pager__btn">›</span></li>
            @endif
        </ul>
    </nav>
@endif
