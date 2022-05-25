@if ($paginator->hasPages())
    <nav>
        <ul class="pagination mb-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item me-3 disabled " aria-disabled="true">
                    <span class="page-link rounded-0">@lang('pagination.previous')</span>
                </li>
            @else
                <li class="page-item me-3">
                    <a class="page-link rounded-0 text-dark fw-bold" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-0 text-dark fw-bold" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-0">@lang('pagination.next')</span>
                </li>
            @endif
        </ul>
        <p class="text-muted">Showing page <span class="fw-bold">{{ $paginator->currentPage() }}</span> of total <span class="fw-bold">{{ $paginator->lastPage() }}</span> page(s).</p>
    </nav>
@endif