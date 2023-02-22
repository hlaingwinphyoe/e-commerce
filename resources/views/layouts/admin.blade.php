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

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @laravelPWA
</head>
<body class="@yield('classes')">

    <div id="fse-admin" class="app admin_app sidebar-mini">

        <header class="app-header">
            <x-admin.header></x-admin.header>
        </header>
        <!-- Sidebar menu-->


        <aside class="app-sidebar smooth-scroll shadow">
            <x-admin.sidebar></x-admin.sidebar>
        </aside>


        <main class="app-content">
            <section class="content">
                @yield('content')
            </section>
        </main>

        <footer class="footer py-2 px-1 small">
            @include('components.admin.footer')
        </footer>
    </div>

    <script src="{{ asset('js/admin.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
            var vapid_key ="{{ Config::get('app.vapid_key') }}";
    </script>
{{--    <script src="{{ asset('js/push-noti.js') }}"></script>--}}
    @yield('scripts')

    @if(session('message'))
        <script>
            toastr.options.closeButton = true;
            toastr.success("{{ session('message') }}");
        </script>
    @endif
</body>
</html>
