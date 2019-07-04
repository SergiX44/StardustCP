@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h1>Dashboard</h1>
    @include('layouts.breadcrumb', ['b'=> []])
@endsection

@section('body')
    You are logged in!
@endsection

