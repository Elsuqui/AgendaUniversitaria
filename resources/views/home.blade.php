@extends('layouts.app')

@section('content')
    <div class="ui stackable container grid vertically padded" style="height: 1000px; overflow: auto;">
        <div class="four wide column">
            <div class="ui center aligned segment">
                <div class="ui header">
                    Próximos Eventos
                </div>
                <div class="ui divider"></div>
                <div class="ui very compact centered card">
                    <div class="content">
                        <div class="header">Asistir alGym</div>
                    </div>
                    <div class="content">
                        <div class="description">
                            Hora: 15:00
                        </div>
                        <div class="description">
                            Lugar: Gold's Gym
                        </div>
                    </div>
                </div>

                <div class="ui very compact centered card">
                    <div class="content">
                        <div class="header">Asistir al Gym</div>
                    </div>
                    <div class="content">
                        <div class="description">
                            Hora: 15:00
                        </div>
                        <div class="description">
                            Lugar: Gold's Gym
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="eight wide container column">
            <div class="ui raised container segment">
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

        <div class="four wide container column">
            <div class="ui segments">
                <div class="ui segment no-padding-bottom">
                    <h5 class="ui left floated header">
                        Calendario
                    </h5>
                    <div class="clearfix"></div>
                </div>
                <div class="ui redli segment">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>


        {{--@if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif--}}

    </div>
@endsection

@section('js')

@endsection