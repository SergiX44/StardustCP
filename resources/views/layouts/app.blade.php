@extends('layouts.base')

@section('_body_')
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            @include('layouts.sidebar')
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        @yield('header')
                    </div>
                    <div class="section-body">
                        @include('layouts.alerts')
                        @yield('body')
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
