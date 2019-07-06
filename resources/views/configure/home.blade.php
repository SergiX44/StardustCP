@extends('layouts.app')

@section('title', 'System Configuration')

@section('header')
    <h1>System Configuration</h1>
    @include('layouts.breadcrumb', ['b'=> ['Configuration']])
@endsection

@section('body')
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
                            <a href="{{ $module->configRoute() }}" class="card-cta">Change Settings <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

