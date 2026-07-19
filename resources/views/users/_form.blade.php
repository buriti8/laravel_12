<form role="form" method="POST" action="{{ $user->id ? route('users.update', $user->id) : route('users.store') }}"
    autocomplete="off">
    @csrf
    @if($user->id)
    @method('put')
    @endif
    <div class="card-body">
        <div class="row">
            <div class="col col-md-12">
                <label>*@lang('users.name')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="@lang('users.name')">
                    @if($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.email')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                    </div>
                    <input id="email" type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="@lang('users.email')">
                    @if($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.username')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user-lock"></i></div>
                    </div>
                    <input id="username" type="text"
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username"
                        value="{{ old('username', $user->username) }}" placeholder="@lang('users.username')">
                    @if($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @if (!$user->id)
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.password')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                    </div>
                    <input id="password" type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password"
                        value="{{ old('password') }}" placeholder="@lang('users.password')" autocomplete="new-password">
                    @if($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.password_confirmation')</label>
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                    </div>
                    <input id="password_confirmation" type="password"
                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        name="password_confirmation" value="{{ old('password_confirmation') }}"
                        placeholder="@lang('users.password_confirmation')">
                    @if($errors->has('password_confirmation'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @endif
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.is_admin')</label>
                <div class="input-group input-group-sm mb-2">
                    <select id="is_admin"
                        class="form-control-sm {{ $errors->has('is_admin') ? 'is-invalid' : '' }} select2"
                        name="is_admin">
                        <option value="">@lang('users.is_admin')</option>
                        <option value="0" {{!old('is_admin', $user->is_admin ?? '') ? 'selected' : ''}}>No</option>
                        <option value="1" {{old('is_admin', $user->is_admin ?? '') ? 'selected' : ''}}>Sí</option>
                    </select>
                    @if($errors->has('is_admin'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('is_admin') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col col-md-6 col-sm-12">
                <label>*@lang('users.roles')</label>
                <div class="input-group mb-2">
                    <select id="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }} not-select"
                        name="role[]" multiple size="8" {{$user->is_admin ? 'disabled' : ''}}>
                        <option value="" disabled>@lang('users.roles')</option>
                        @foreach($roles as $r)
                        <option value="{{$r->id}}" {{$user->hasRole($r) || in_array($r->id,old('role',[])) ? 'selected'
                            : ''}}>
                            {{ucwords($r->name)}}
                        </option>
                        @endforeach
                    </select>
                    @if($errors->has('role'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('role') }}</strong>
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