<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agenda Virtual</title>

    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">

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

        .links > span {
            font-size: x-large;
            display: inline-block;
            margin: 30px;
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
    <div class="container">
        <div class="ui huge header">
            <div>
                <img style="float: left;" src="{{ asset('imagenes/Confirm.png') }}" class="ui small image logo">
                El cumplimiento de la Actividad: <br>{{ $evento->titulo }} se ha realizado con éxito!!!
            </div>
            <hr>
            Agenda Universitaria
        </div>
        <div class="links">
            <a href="{{ url('/home') }}">
                <img src="{{ asset('imagenes/event-default.png') }}"
                     style="position: relative; top: 20px; height: 10%; width: 10%;" width="50" height="50">
                <span>Ir al Calendario</span>
            </a>
        </div>

        <footer class="footer">Desarrollado por Gorky Suquinagua</footer>
    </div>
</div>
</body>
</html>

