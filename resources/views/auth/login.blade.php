@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="login-box login-page">
    <div class="card">
        <div class="card-body login-card-body rounded">
            <span>
                <h3 class="login-box-msg text-uppercase pl-0 pr-0">
                    <b>
                        <span class="text-secondary">SOFASA </span>
                        <span class="text-primary">{{mb_strtoupper(config('app.name'))}}</span>
                    </b>
                </h3>
            </span>

            @include('layouts.message')

            <form role="form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input id="username" type="text"
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username"
                        value="{{ old('username') }}" placeholder="@lang('users.username')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    @if($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="input-group mb-3">
                    <input id="password" type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password"
                        placeholder="@lang('users.password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    @if($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-secondary btn-block text-primary font-weight-bold">
                            @lang('base_lang.login')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection