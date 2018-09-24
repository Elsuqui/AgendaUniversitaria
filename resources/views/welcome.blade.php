<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Agenda Virtual</title>

        <!-- Fonts -->

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background: url('{{ asset('imagenes/agenda_home.jpg')}}')  no-repeat center center fixed;
                background-size: cover;
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100%;

            }

            .full-height {
                height: 110vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 0px;
                top: 20px;
                padding: 20px;
            }

            .content {
                text-align: start;
                padding: 3%;
            }

            .title {
                font-size: 50px;
            }

            .opcion{
                display: inline-block;
                text-align: center;
                width: 200px;
                height: 100px;
                margin: 0em;
                padding: 0em;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 15px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .logo{
                max-width: 25%;
                height: auto;
            }

            .opcion > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 15px;
                display: block;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 10px;
            }

            .footer{
                display: compact;
                position: absolute;
                padding-top: 1%;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    <img src="{{ asset('imagenes/logo_ucsg.png') }}" class="logo">
                </div>

                <div class="title m-b-md">
                    Universidad Católica Santiago de Guayaquil
                </div>
                <hr>
                <div class="title m-b-md">
                    Agenda Universitaria
                </div>

                <div class="links">
                    <div class="opcion">
                        <a href="https://laravel.com/docs">Servicios en linea</a>
                        <img src="{{ asset('imagenes/imagen_libros.png') }}" width="50" height="50">
                    </div>
                    <div class="opcion">
                        <a href="https://laracasts.com">Plataforma Academica</a>
                        <img src="{{ asset('imagenes/logo_plataforma.png') }}" width="50" height="50">
                    </div>
                    <div class="opcion">
                        <a href="https://laravel-news.com">Universidad Catolica</a>
                        <img src="{{ asset('imagenes/web_home.png') }}" width="50" height="50">
                    </div>
                    @if (Route::has('login'))
                        <div class="opcion">
                            @auth
                                <a href="{{ url('/home') }}">Bienvenido <br> {{ Auth::getUser()->name }}</a>
                                <img src="{{ asset('imagenes/default-user.png') }}" width="50" height="50">
                            @else
                                <a href="{{ route('login') }}">Ingresar a su Agenda</a>
                                <img src="{{ asset('imagenes/login_button.png') }}" width="50" height="50">
                                {{-- <a href="{{ route('register') }}">Register</a> --}}
                            @endauth
                        </div>
                    @endif
                </div>

                <footer class="footer">Desarrollado por Gorky Suquinagua</footer>
            </div>
        </div>
    </body>
</html>
