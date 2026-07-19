@extends('layouts.menu')

@section('title', __('base_lang.roles'))

@section('title_page')
<i class="fa fa-crosshairs"></i>&nbsp;@lang('base_lang.roles')
@endsection

@section('content_page')

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">@include('layouts.message')</div>
        <div class="col-md-12">
            <div class="card card-secondary mb-2">
                <div class="card-header py-1 px-2">
                    <h3 class="card-title">@lang('users.new_role')</h3>
                </div>
                <div class="p-3">
                    @include('roles._form')
                </div>
            </div>

            <div class="pb-3">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-plus"></i>&nbsp;@lang('users.new')
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-users"></i>&nbsp;@lang('users.view')
                </a>
            </div>
            @include('vendor.pagination.record-count', ['paginator' => $roles, 'show_more_records' => false])
            <div class="table-responsive pt-3">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="thead-gray">
                        <tr>
                            <th class="text-center">@lang('users.role')</th>
                            <th class="text-center">@lang('users.permissions')</th>
                            <th class="text-center">@lang('base_lang.delete')</th>
                        </tr>
                    </thead>

                    @foreach($roles as $r)
                    @if($r->is_not_admin)
                    <tr role="row" class="odd" id="filaR_{{  $r->id }}">
                        <td>{{ $r->name }}</td>
                        <td class="text-center">
                            <a type="submit" title="Asignar permisos" class="btn btn-sm btn-default btn-xs"
                                href="{{ route('roles.permissions.edit', $r->id) }}">
                                <i class="fa fa-fw fa-key"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            @if(in_array($r->name, $roles_base) !== true)
                            <form class="form-horizontal" role="form" method="POST" action="{{url("/roles/{$r->
                                id}")}}">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-sm btn-default btn-xs btn-delete" title="Eliminar">
                                    <i class="fa fa-fw fa-times delete"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
            {{$roles->links()}}
        </div>
    </div>
</div>
@endsection