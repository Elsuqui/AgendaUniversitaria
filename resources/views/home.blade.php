@extends('layouts.app')

@section('css')
    <link href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
@endsection

@section('content')
        <div class="ui stackable grid vertically padded"  style="height: 100vh; overflow: scroll;">
            <div class="row">
                <div class="five wide column" style="margin-left: 5%;">
                    <div class="ui segments">
                        <div class="ui raised very padded container segment">
                            <div class="row no-padding">
                                <div class="five wide column">
                                    <div class="ui items">
                                        <div class="item">
                                            <div class="ui small circular image">
                                                <img src="{{ asset('imagenes/default-user.png') }}">
                                            </div>
                                            <div class="middle aligned content">
                                                <div class="header"></div>{{ Auth::getUser()->name }}
                                                <div class="meta">Facultad de Ingenieria en Sistemas Computacionales</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            Contenido de Novedades y Noticias Actuales
                        </div>
                    </div>

                    <div class="ui raised container segments">
                        <div class="ui header segment">
                            Actividades de Hoy
                        </div>
                        <div class="ui segment">
                            <listado-eventos></listado-eventos>
                        </div>
                    </div>
                </div>
                <div class="ten wide column">
                    <div class="ui segments">
                        <div class="ui segment">
                            <div class="ui header">
                                <h5>Calendario de Actividades, Tareas y Eventos</h5>
                            </div>
                        </div>
                        <div class="ui segment" style="overflow: scroll;">
                            {{-- Calendario en la pagina de Inicio, muestra todaos los eventos y actividades del docente --}}
                            {{--<div id="home_calendar"></div>--}}
                            <calendar></calendar>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
    {{--<script src="{{ asset("js/customjs/custom-calendar.js") }}"></script>
    <script src="{{ asset("plugins/fullcalendar/fullcalendar.min.js") }}" type="text/javascript"></script>--}}
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
