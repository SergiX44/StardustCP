<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} :: @yield('title')</title>

    <!-- Styles -->
    <link href="{{ mix('css/vendors.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/modules.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
@yield('_body_')
<!-- Scripts -->
<script src="{{ mix('js/vendors.js') }}"></script>
<script src="{{ mix('js/app.js') }}" defer></script>
<script src="{{ mix('js/modules.js') }}" defer></script>
@yield('js')
</body>
</html>
