@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

        <div class="section-header">
            <h1>Dashboard</h1>
            <!-- Breadcrumb -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Parent Page</a></div>
                <div class="breadcrumb-item">Page</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('status'))
                <div class="alert alert-primary">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
@endsection
