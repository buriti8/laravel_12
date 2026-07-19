@extends('layouts.menu')

@section('title', __('users.permissions'))

@section('title_page')
<i class="fa fa-crosshairs"></i>&nbsp;@lang('users.permissions')
<i class="fas fa-caret-right pl-1 pr-1"></i>{{ $role->name }}
@endsection

@section('content_page')

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">@include('layouts.message')</div>
        <div class="col-md-12">
            <div class="pb-3">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-plus"></i>&nbsp;@lang('users.new')
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-users"></i>&nbsp;@lang('users.view')
                </a>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary ">
                    <i class="fa fa-lg fa-fw fa-crosshairs "></i>&nbsp;@lang('users.view_roles')
                </a>
            </div>
            <div class="panel panel-default">
                <div class="panel-body body_form">
                    <div class="card card-secondary mb-2">
                        <div class="card-header py-1 px-2">
                            <h3 class="card-title">@lang('base_lang.permission_by_modules')</h3>
                        </div>
                        @include('permissions._form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection