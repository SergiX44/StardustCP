@extends('layouts.base')

@section('_body')
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            @include('layouts.sidebar')
            <div class="main-content">
                <section class="section">
                    @yield('content')
                </section>
            </div>
        </div>
    </div>
@endsection
