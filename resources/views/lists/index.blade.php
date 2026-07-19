@extends('layouts.menu')

@section('title', __('base_lang.lists'))

@section('title_page')
<i class="fas fa-th-list"></i>&nbsp;@lang('base_lang.lists')
@endsection

@section('content_page')

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">@include('layouts.message')</div>
        <div class="col-md-12">
            @permission(['edit_lists', 'view_lists', 'all_lists'])
            <div class="card card-secondary card-outline mb-2">
                <div class="pl-3 pr-3 pt-2 pb-1">
                    @include('lists._search')
                </div>
            </div>
            @endpermission

            <div class="button-w-100 pb-1">
                @permission(['edit_lists', 'all_lists'])
                <a href="{{ route('lists.create') }}" class="btn btn-sm btn-primary mb-1">
                    <i class="fa fa-lg fa-plus"></i>&nbsp;@lang('lists.new')
                </a>
                @endpermission
            </div>

            @permission(['edit_lists', 'view_lists', 'all_lists'])
            @include('vendor.pagination.record-count',
            ['paginator' => $lists_options, 'show_more_records' => false])
            <div class="table-responsive pt-2">
                @include('lists._table')
            </div>
            {{$lists_options->links()}}
            @endpermission
        </div>
    </div>
</div>
@endsection