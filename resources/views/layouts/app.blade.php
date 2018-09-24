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
            <div class="ui tiny top menu">
                <a class="labeled launch icon item no-padding" id="menu">
                    <img src="{{ asset('imagenes/hamburguesa_menu.png') }}" style="height: 56.5px; width: 50px;">
                </a>
                &nbsp;
                <div class="item" style="background-color: black;">
                    <img class="icon" src="{{asset('imagenes/ucsg.png')}}" style="width: 300%; height: 100%;">
                </div>

                <div class="right menu">
                    <div class="item">
                        <img class="ui mini circular image no-padding" src="{{ asset('imagenes/default-user.png') }}">
                        &nbsp;
                        <div class="content no-padding">
                            <div class="ui sub header">{{ Auth::user()->name }}</div>
                            Estudiante
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui center attached segment" id="contenido" style="height: 100%;">
            <div class="ui sidebar inverted vertical menu">
                <div class="ui segment" style="background-image: url('{{ asset('imagenes/fondo_user.jpg') }}'); background-size: cover;">
                    <h2 class="ui center aligned icon header no-padding">
                        <img src="{{ asset('imagenes/default-user.png') }}" style="width: 40%; height: 40%; margin: 0;">
                    </h2>
                    <h5 class="ui center aligned header">Bienvenido {{ Auth::getUser()->name }}</h5>
                </div>
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

@yield('js')
<script type="application/javascript">
    $('#contenido .ui.sidebar')
        .sidebar({context: $('#contenido')})
        .sidebar('attach events', '#menu');
</script>
</div>
