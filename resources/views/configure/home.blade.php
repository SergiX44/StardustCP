@extends('layouts.app')

@section('title', 'System Configuration')

@section('header')
    <h1>System Configuration</h1>
    @include('layouts.breadcrumb', ['b'=> ['Configuration']])
@endsection

@section('body')
    <div class="row">
        <div class="col-12">
            <h2 class="section-title">Core Configuration</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon bg-primary text-white">
                    <i class="fas fa-ethernet"></i>
                </div>
                <div class="card-body">
                    <h4>IP Addresses</h4>
                    <p>The system IP addresses.</p>
                    <a href="{{ route('core.ip.index') }}" class="card-cta">Configure <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2 class="section-title">Modules Configuration</h2>
        </div>
    </div>
    <div class="row">
        @foreach($modules as $module)
            @if($module->hasConfig())
                <div class="col-lg-6">
                    <div class="card card-large-icons">
                        <div class="card-icon bg-primary text-white">
                            <i class="fas {{ $module->icon() }}"></i>
                        </div>
                        <div class="card-body">
                            <h4>{{ $module->fancyName() }} <small>{{ $module->version() }}</small></h4>
                            <p>{{ $module->description() }}</p>
                            <a href="{{ $module->configRoute() }}" class="card-cta">Configure <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

