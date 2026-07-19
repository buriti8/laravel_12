<form class="form-inline" action="{{ route('lists.index') }}" method="get" role="search">
    <input type="hidden" name="per_page" value="{{$lists_options->perPage()}}" />

    <div class="col-sm-12 col-md-3">
        <label>@lang('lists.list')</label>
        <div class="input-group input-group-sm mb-2">
            <select class="form-control-sm select2" name="q[list]">
                <option value="">@lang('lists.select_list')</option>
                @foreach($lists as $li => $l)
                <option value="{{$li}}" {{($search['list'] ?? '' )==$li ? 'selected' : '' }}>{{$l}}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <label>@lang('lists.option')</label>
        <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-th-list"></i></div>
            </div>
            <input type="text" class="form-control" name="q[option]" value="{{$search['option'] ?? ''}}"
                placeholder="@lang('lists.option')" autocomplete="off">
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <label>@lang('lists.option_key')</label>
        <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-th-list"></i></div>
            </div>
            <input type="text" class="form-control" name="q[option_key]" value="{{$search['option_key'] ?? ''}}"
                placeholder="@lang('lists.option_key')" autocomplete="off">
        </div>
    </div>

    <div class="col-sm-12 col-md-3 text-right text-md-left">
        <label>&nbsp;</label>
        <button type="submit" class="btn btn-sm btn-primary mb-2 mr-1">@lang('base_lang.search')</button>
        <a href="{{ route('lists.index', ['q' => []]) }}" class="btn btn-sm btn-secondary mb-2">
            @lang('base_lang.clear')
        </a>
    </div>
</form>