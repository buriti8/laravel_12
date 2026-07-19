<table class="table table-sm table-bordered table-striped">
    <thead class="thead-gray">
        <tr>
            <th class="text-center">@lang('lists.list')</th>
            <th class="text-center">@lang('lists.option')</th>
            <th class="text-center">@lang('lists.option_key')</th>
            <th class="text-center">@lang('lists.status')</th>
            @permission(['edit_lists', 'all_lists'])
            <th class="text-center vertical-center">@lang('base_lang.actions')</th>
            @endpermission
            <th class="text-center vertical-center">@lang('audit.change_log')</th>
        </tr>
    </thead>

    @forelse($lists_options as $l)
    <tr role="row" class="odd">
        <td>{{ $lists[$l->list] ?? '' }}</td>
        <td>{{ $l->option }}</td>
        <td>{{ $l->option_key }}</td>
        <td class="text-center">{{ $l->status_text }}</td>

        <td class="text-center">
            @permission(['edit_lists', 'all_lists'])
            @if(!isset($protected[$l->list]) || !isset($protected[$l->list][$l->option_key]))
            <a href="{{ route('lists.edit', $l->id) }}" class="btn btn-sm btn-default btn-xs" title="Editar">
                <i class="fa fa-fw fa-edit"></i>
            </a>
            @endif

            @if(!isset($protected[$l->list]) || !isset($protected[$l->list][$l->option_key]))
            <form class="d-inline-block" method="POST" action="{{ route('lists.update', $l->id) }}">
                @method('put')
                @csrf
                <input type="hidden" name="status" value="{{$l->status ? 0 : 1}}" />
                <button type="button" class="btn btn-sm btn-default btn-xs btn-status"
                    title="{{$l->status ? __('base_lang.disabled') : __('base_lang.enabled')}}">
                    <i class="fa fa-fw {{$l->status ? 'fa-ban' : 'fa-check'}}"></i>
                </button>
            </form>
            @endif
            @endpermission
        </td>
        <td style="text-transform: none; text-align: center;">
            @includeWhen($l instanceof \App\Audit\IsAuditable, 'audit.change_log_button' ,['model' => $l,
            'without_text_button' => true])
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5">
            <em>@lang('base_lang.no_records')</em>
        </td>
    </tr>
    @endforelse
</table>