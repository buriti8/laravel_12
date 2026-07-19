<form class="form-inline" role="form" method="POST" action="{{route('roles.store')}}">
    @csrf
    <div class="input-group input-group-sm mb-2 mr-sm-2">
        <div class="input-group-prepend">
            <div class="input-group-text"><i class="fa fa-user"></i></div>
        </div>
        <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name"
            value="{{ old('name', $user->name ?? '') }}" placeholder="*@lang('roles.name')">
        @if($errors->has('name'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
    </div>

    <button type="submit" class="btn btn-sm btn-primary mb-2">
        @lang('base_lang.save')
    </button>
</form>