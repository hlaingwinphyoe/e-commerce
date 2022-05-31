<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ env('APP_URL') }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="shortcut icon" href="/">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @yield('style')
</head>

<body class="@yield('classes')">

    <div id="fse-admin" class="app">
        <main class="app-content m-0">      
            <section class="content">
                @yield('content')
            </section>
        </main>        

    </div>
    <script src="{{ asset('js/admin.js')}}"></script>
    @yield('script')

</body>

</html>
