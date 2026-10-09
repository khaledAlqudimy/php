{{-- ترقيم مبسّط (السابق / التالي) --}}
@if ($paginator->hasPages())
    <nav class="qq-pagination" role="navigation" aria-label="ترقيم الصفحات">
        @if ($paginator->onFirstPage())
            <span class="qq-page is-disabled" aria-disabled="true">السابق</span>
        @else
            <a class="qq-page" href="{{ $paginator->previousPageUrl() }}" rel="prev">السابق</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="qq-page" href="{{ $paginator->nextPageUrl() }}" rel="next">التالي</a>
        @else
            <span class="qq-page is-disabled" aria-disabled="true">التالي</span>
        @endif
    </nav>

    @once
    <style>
        .qq-pagination { display: flex; gap: .35rem; justify-content: center; margin: 1.5rem 0; direction: rtl; }
        .qq-page { height: 38px; padding: 0 1rem; display: inline-flex; align-items: center; border-radius: 8px; border: 1.5px solid #D4C4B0; background: #fff; color: #3A2418; font-size: .85rem; font-weight: 600; text-decoration: none; }
        a.qq-page:hover { border-color: #B68A4A; color: #B68A4A; }
        .qq-page.is-disabled { opacity: .45; cursor: default; }
    </style>
    @endonce
@endif
