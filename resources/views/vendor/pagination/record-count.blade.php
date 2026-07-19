<div class="total_register">
    @if ($paginator->total() > 20 && ($show_more_records ?? true))
        <span>Por página:</span>
        {!! get_per_page_links($paginator)->implode(', ') !!}
        -
    @endif
    <span>@lang('base_lang.registers'):</span>
    <span>
        {{(($paginator->currentPage() -1 ) * $paginator->perPage())+ $paginator->count() }}/{{ $paginator->total()}}
    </span>
</div>