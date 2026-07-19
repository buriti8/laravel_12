<form role="form" method="POST" action="{{ route('users.password.update', $user->id) }}">
    @csrf
    @method('put')
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <label>*@lang('users.full_name')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{$user->name}}" readonly>
                    @if($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <label>*@lang('users.username')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user-lock"></i></div>
                    </div>
                    <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                        value="{{$user->username}}" readonly>
                    @if($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @if($check_current ?? false)
            <div class="col-sm-12 col-md-3">
                <label>*@lang('users.current_password')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                    </div>
                    <input id="current_password" type="password"
                        class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                        name="current_password" placeholder="*@lang('users.current_password')"
                        title="@lang('users.current_password')">
                    @if($errors->has('current_password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('current_password') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @endif
            <div class="col-sm-12 col-md-3">
                <label>*@lang('users.password')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                    </div>
                    <input id="password" type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password"
                        placeholder="*@lang('users.password')" title="@lang('users.password')">
                    @if($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <label>*@lang('users.password_confirmation')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                    </div>
                    <input id="password-confirm" type="password"
                        class="form-control {{ $errors->has('confirm_password') ? 'is-invalid' : '' }}"
                        name="password_confirmation" placeholder="*@lang('users.confirm_password')"
                        title="@lang('users.confirm_password')">
                    @if($errors->has('password_confirmation'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
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
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                    @lang('base_lang.cancel')
                </a>
            </div>
        </div>
    </div>
</form>