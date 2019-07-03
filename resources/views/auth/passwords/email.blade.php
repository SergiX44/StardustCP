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

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="email">@lang('auth.email_address')</label>
                                <input id="email" type="email" class="form-control @error('password') is-invalid @enderror" name="email" tabindex="1" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span class="invalid-feedback">
                                    @error('email')
                                    {{ $message }}
                                    @else
                                        @lang('auth.email_required')
                                    @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2">
                                    @lang('auth.password_reset')
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-secondary btn-lg btn-block" tabindex="3">
                                    @lang('misc.back')
                                </a>
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
