@extends('layouts.app')

@section('title', 'Web')

@section('header')
    <h1>Web Config</h1>
    @include('layouts.breadcrumb', ['b'=> ['Configuration' => route('core.configuration'), 'Web Module']])
@endsection

@section('body')
    This is a config page for the web module
@endsection


