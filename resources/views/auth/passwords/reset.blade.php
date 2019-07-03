@extends('layouts.auth')

@section('title', 'Password reset')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="login-brand">
                    <img src="https://placeholder.pics/svg/100/DEDEDE/555555/LOGO" alt="logo" width="100" class="shadow-light rounded-circle">
                </div>

                <div class="card card-primary">
                    <div class="card-header"><h4>{{ config('app.name') }}</h4></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <label for="email">@lang('auth.email_address')</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" tabindex="1" required autocomplete="email" autofocus>
                                <span class="invalid-feedback">
                                    @error('email')
                                    {{ $message }}
                                    @else
                                        @lang('auth.email_required')
                                        @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="password" class="control-label">@lang('auth.password')</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="2" required autocomplete="new-password">
                                <div class="invalid-feedback">
                                    @error('password')
                                        {{ $message }}
                                    @else
                                        @lang('auth.password_required')
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="control-label">@lang('auth.password_confirm')</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" tabindex="3" required autocomplete="new-password">
                                <div class="invalid-feedback">
                                        @lang('auth.password_required')
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    @lang('auth.password_reset')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="simple-footer">
                    Copyright &copy; {{ config('app.name') }} {{ now()->year }}
                </div>
            </div>
        </div>
    </div>
@endsection
