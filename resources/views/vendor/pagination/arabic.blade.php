{{-- ترقيم الصفحات (عربي) — Paginator::defaultView في AppServiceProvider --}}
@if ($paginator->hasPages())
    <nav class="qq-pagination" role="navigation" aria-label="ترقيم الصفحات">
        {{-- السابق --}}
        @if ($paginator->onFirstPage())
            <span class="qq-page is-disabled" aria-disabled="true">السابق</span>
        @else
            <a class="qq-page" href="{{ $paginator->previousPageUrl() }}" rel="prev">السابق</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="qq-page is-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="qq-page is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="qq-page" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- التالي --}}
        @if ($paginator->hasMorePages())
            <a class="qq-page" href="{{ $paginator->nextPageUrl() }}" rel="next">التالي</a>
        @else
            <span class="qq-page is-disabled" aria-disabled="true">التالي</span>
        @endif
    </nav>

    @once
    <style>
        .qq-pagination { display: flex; flex-wrap: wrap; gap: .35rem; justify-content: center; align-items: center; margin: 1.5rem 0; direction: rtl; }
        .qq-page { min-width: 38px; height: 38px; padding: 0 .75rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1.5px solid #D4C4B0; background: #fff; color: #3A2418; font-size: .85rem; font-weight: 600; text-decoration: none; transition: all .2s; }
        a.qq-page:hover { border-color: #B68A4A; color: #B68A4A; }
        .qq-page.is-active { background: #3A2418; border-color: #3A2418; color: #B68A4A; }
        .qq-page.is-disabled { opacity: .45; cursor: default; }
    </style>
    @endonce
@endif
