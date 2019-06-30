@extends('layouts.app')

@section('content')

        <div class="section-header">
            <h1>Page</h1>
            <!-- Breadcrumb -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Parent Page</a></div>
                <div class="breadcrumb-item">Page</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Dashboard</h2>
            <p class="section-lead">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
            </p>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
@endsection
