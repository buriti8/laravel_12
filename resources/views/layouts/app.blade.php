<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=11'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name')}} | @yield('title')</title>
    <link rel="icon" href="{{asset('img/icon.png')}}" type="image/png" sizes="16x16">
    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">

    @include('layouts.menu_css')
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    @include('layouts.menu_js')

    @yield('javascript')
</body>

</html>