<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} :: @yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/vendors.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@yield('_body')
<!-- Scripts -->
<script src="{{ asset('js/vendors.js') }}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
