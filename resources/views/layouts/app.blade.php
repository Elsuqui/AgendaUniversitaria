<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <meta name="description" content="Agenda Virtual Universitaria UCSG"/>
    <meta name="keywords" content="ucsg, agenda virtual"/>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datepicker/css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/semantic-ui-calendar/dist/calendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/components/popup.min.css') }}" rel="stylesheet">
    @yield('css')

</head>
<body class="{{ auth()->check() ? '' : 'signin'}}">
@auth
    <div class="ui top attached no-padding">
        <div class="ui tiny top menu no-padding" style="background-color: #98092a;">
            <a class="labeled launch icon item" style="background-color: #98092a; color: white;" id="menu" data-content="Menu">
                <i class="circular inverted sidebar large icon"></i>
                Menu
            </a>
            &nbsp;
            <div class="item" style="background-color: black; width: 140px;">
                <img src="{{asset('imagenes/ucsg.png')}}" style="width: 100px;">
            </div>

            <div class="right menu" style="border-left-color: black; border-left-style: groove;">
                <div class="item">
                    <div class="ui dropdown">
                        <i class="inbox circular inverted icon"></i>23
                    </div>
                </div>
                <div class="ui dropdown item" style="color: white;">
                    <img class="ui mini circular image no-padding"
                         src="{{ asset('imagenes/default-user.png') }}">
                    {{ Auth::user()->name }}<br>
                    Estudiante
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="#" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Cerrar Sesion</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
<div id="app">
    @auth
        <div class="ui center attached segment" id="contenido">
            <div class="ui sidebar inverted vertical menu">
                <div class="ui segment"
                     style="background-image: url('{{ asset('imagenes/fondo_user.jpg') }}'); background-size: cover;">
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
        <div class="pusher" style="height: 100vh; overflow-y: auto;">
            @yield('content')
        </div>
    @endguest
</div>
</body>
</html>

{{--<script src="{{ asset("plugins/fullcalendar/lib/moment.min.js") }}" type="text/javascript"></script>--}}
<script src="{{ asset('js/semantic.min.js') }}"></script>
<script src="{{ asset('dist/components/popup.min.js') }}"></script>
<script src="{{ asset("js/app.js") }}"></script>
@yield('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#contenido .ui.sidebar')
            .sidebar({context: $('#contenido')})
            .sidebar('attach events', '#menu');

        $('.ui.dropdown').dropdown({useLabels: false});
        var today = new Date();
    });
</script>
