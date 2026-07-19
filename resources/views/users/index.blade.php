@extends('layouts.menu')

@section('title', __('base_lang.users'))

@section('title_page')
<i class="fa fa-users"></i>&nbsp;@lang('base_lang.users')
@endsection

@section('content_page')

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">@include('layouts.message')</div>
        <div class="col-md-12">
            <div class="card card-secondary card-outline mb-2">
                <div class="pl-3 pr-3 pt-2 pb-1">
                    @include('users._search')
                </div>
            </div>

            <div class="pb-3">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-plus"></i>&nbsp;@lang('users.new')
                </a>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-lg fa-fw fa-crosshairs"></i>&nbsp;@lang('users.view_roles')
                </a>
            </div>

            @include('vendor.pagination.record-count', ['paginator' => $users, 'show_more_records' => false])
            <div class="table-responsive pt-3">
                @include('users._table')
            </div>
            {{$users->links()}}
        </div>
    </div>
</div>
@endsection