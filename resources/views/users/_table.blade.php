<table class="table table-sm table-bordered table-striped">
    <thead class="thead-gray">
        <tr>
            <th class="text-center vertical-center">@lang('users.full_name')</th>
            <th class="text-center vertical-center">@lang('users.email')</th>
            <th class="text-center vertical-center">@lang('users.username')</th>
            <th class="text-center vertical-center">@lang('users.roles')</th>
            <th class="text-center vertical-center">@lang('users.last_login')</th>
            <th class="text-center vertical-center">@lang('users.last_logout')</th>
            <th class="text-center vertical-center">@lang('users.ip_address')</th>
            <th class="text-center vertical-center">@lang('users.status')</th>
            <th class="text-center vertical-center">@lang('base_lang.actions')</th>
            @canImpersonate
            <th class="text-center">@lang('users.impersonate')</th>
            @endCanBeImpersonated
            <th class="text-center vertical-center">@lang('audit.change_log')</th>
        </tr>
    </thead>
    @forelse($users as $u)
    <tr>
        <td>{{ $u->name . ' ' . $u->last_name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->username }}</td>
        <td>{!! nl2br($u->full_name_role) !!}</td>
        <td>
            @formatDateTime($u->last_login)
        </td>
        <td>
            @formatDateTime($u->last_logout)
        </td>
        <td>{{$u->ip_address ?? ''}}</td>
        <td class="text-center">{{ $u->status_text }}</td>

        <td class="text-center">
            @if($u->id !== auth()->user()->id && $u->id !== 1)
            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-default btn-xs"
                title="@lang('base_lang.edit')">
                <i class="fa fa-fw fa-edit"></i>
            </a>

            <form class="d-inline-block" method="POST" action="{{ route('users.update', $u->id) }}">
                @method('put')
                @csrf
                <input type="hidden" name="status" value="{{$u->status ? 0 : 1}}" />
                <button type="button" class="btn btn-sm btn-default btn-xs btn-status"
                    title="{{$u->status ? __('base_lang.disabled') : __('base_lang.enabled')}}">
                    <i class="fa fa-fw {{$u->status ? 'fa-ban' : 'fa-check'}}"></i>
                </button>
            </form>

            <a href="{{ route('users.password.edit', $u->id) }}" class="btn btn-sm btn-default btn-xs"
                title="@lang('users.change_password')">
                <i class="fa fa-fw fa-lock"></i>
            </a>

            <form class="d-inline-block" role="form" method="POST" action="{{ route('users.destroy', $u->id) }}">
                @method('delete')
                @csrf
                <button type="button" class="btn btn-sm  btn-default btn-xs btn-delete"
                    title="@lang('base_lang.delete') {{$u->name}}">
                    <i class="fa fa-fw fa-times delete"></i>
                </button>
            </form>
            @endif
        </td>
        @canImpersonate
        <td class="text-center">
            @canBeImpersonated($u)
            <a href="{{route('impersonate', $u->id)}}" class="btn btn-sm btn-default btn-xs"
                title="@lang('users.impersonate')">
                <i class="fa fa-key"></i>
            </a>
            @endCanBeImpersonated
        </td>
        @endCanBeImpersonated
        <td style="text-transform: none; text-align: center;">
            @includeWhen($u instanceof \App\Audit\IsAuditable, 'audit.change_log_button' ,['model' => $u,
            'without_text_button' => true])
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="12">
            <em>@lang('base_lang.no_records')</em>
        </td>
    </tr>
    @endforelse
</table>