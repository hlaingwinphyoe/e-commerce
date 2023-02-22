<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>{{ config('app.name') }} - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ env('APP_URL') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="@yield('classes')">

    <div id="fse-admin" class="app">
        <main class="app-content" style="overflow-x: hidden">
            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>

    <script src="{{ asset('js/admin.js')}}"></script>

    @yield('scripts')
</body>
</html>
