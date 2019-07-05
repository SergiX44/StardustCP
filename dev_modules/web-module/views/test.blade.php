@extends('layouts.app')

@section('title', 'Web')

@section('header')
    <h1>Web</h1>
    @include('layouts.breadcrumb', ['b'=> []])
@endsection

@section('body')
    Hello world from the web package
@endsection


