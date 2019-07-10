@extends('layouts.app')

@section('title', 'Database')

@section('header')
    <h1>Database Config</h1>
    @include('layouts.breadcrumb', ['b'=> ['Configuration' => route('core.configuration'), 'Database Module']])
@endsection

@section('body')
    This is a config page for the database module
@endsection


