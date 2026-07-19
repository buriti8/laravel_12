<form role="form" method="POST" action="{{ $list->id ? route('lists.update', $list->id) : route('lists.store') }}" autocomplete="off">
    @csrf
    @if($list->id ?? false)
    @method('put')
    @endif

    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <label>*@lang('lists.list')</label>
                <div class="input-group input-group mb-2">
                    <select class="form-control-sm {{ $errors->has('list') ? 'is-invalid' : '' }} select2 w-100"
                        name="list" id="list">
                        <option value="">*@lang('lists.select_list')</option>
                        @foreach($lists as $li => $l)
                        <option value="{{$li}}" {{$li==old('list',$list->list ?? '') ? 'selected' : ''}}>
                            {{$l}}
                        </option>
                        @endforeach
                    </select>
                    @if($errors->has('list'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('list') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-4">
                <label>*@lang('lists.option')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-th-list"></i></div>
                    </div>
                    <input id="option" type="text" class="form-control {{ $errors->has('option') ? 'is-invalid' : '' }}"
                        name="option" value="{{old('option',$list->option ?? '')}}" placeholder="*@lang('lists.option')"
                        title="@lang('lists.option')">
                    @if($errors->has('option'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('option') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-4">
                <label>@lang('lists.option_key')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-hashtag"></i></div>
                    </div>
                    <input id="option_key" type="text"
                        class="form-control {{ $errors->has('option_key') ? 'is-invalid' : '' }}" name="option_key"
                        value="{{old('option_key',$list->option_key ?? '')}}" placeholder="@lang('lists.option_key')"
                        title="@lang('lists.option_key')">
                    @if($errors->has('option_key'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('option_key') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <small><strong>(*) </strong>@lang('base_lang.required')</small>
            </div>
            <div class="col-sm-12 col-md-7 text-center text-md-right pt-2">
                <button type="submit" class="btn btn-sm btn-primary">
                    @lang('base_lang.save')
                </button>
                <a href="{{ route('lists.index') }}" class="btn btn-sm btn-primary">
                    @lang('base_lang.cancel')
                </a>
            </div>
        </div>
    </div>
</form>