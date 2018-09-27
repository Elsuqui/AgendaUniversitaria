@extends('layouts.app')

@section('content')
    <div class="ui stackable fluid grid segment">
        <div class="twelve wide stackable column">
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
                Contenido de Novedades y Noticias Actuales
            </div>
        </div>
        <div class="four wide stackable column ">
            <div class="ui raised segment">
                <div class="ui header">
                    Próximos Eventos
                </div>
                <div class="ui divider"></div>
                <div class="ui centered card">
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

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
