<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AUV') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/semantic.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <meta name="description" content="Agenda Virtual Universitaria UCSG"/>
    <meta name="keywords" content="ucsg, agenda virtual"/>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    @yield('css')

</head>
<body class="signin">
<div id="app" style="height: 100%;">
    @auth
        <div class="ui top attached segment">
            <div class="ui massive top menu">
                <div class="items">

                </div>
            </div>
        </div>
        <div class="ui center attached segment" id="contenido" style="height: 100%;">
            <div class="ui visible sidebar inverted vertical menu">
                <a class="item">
                    Opcion1
                </a>
                <a class="item">
                    Opcion2
                </a>
                <a class="item">
                    Opcion3
                </a>
            </div>
            <div class="pusher">
                @yield('content')
            </div>
        </div>
    @endauth
    @guest
        <div class="pusher">
            @yield('content')
        </div>
    @endguest
</div>
</body>
</html>

<script type="application/javascript">
    $('#contenido .ui.sidebar')
        .sidebar({context: $('#contenido')})
    @yield('js')
</script>
