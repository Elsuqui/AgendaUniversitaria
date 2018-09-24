@extends('layouts.app')

@section('content')
    <div class="ui container" style="padding-top: 10%;">
        <div class="ui equal width center aligned padded grid stackable">
            <div class="row">
                <div class="six wide column">
                    <div class="ui segments">
                        <div class="ui segment inverted nightli">
                            <h3 class="ui header">
                                Ingresar a su Agenda Virtual
                            </h3>
                            <img src="{{ asset('imagenes/ucsg.png') }}">
                        </div>
                        <div class="ui segment">
                            <div class="description">
                                Ingrese su email y contraseña
                            </div>
                            {!! Form::open(['url' => route('login'), 'class' => 'ui form']) !!}
                            <div class="ui divider"></div>
                            <div class="ui input fluid field {{ $errors->has('email') ? 'error' : '' }}">
                                <input type="text" value="{{ old('email') }}" name="email" id="email" placeholder="Correo electronico...">
                            </div>
                            <div class="ui divider hidden"></div>
                            <div class="ui input fluid field {{ $errors->has('password') ? 'error' : '' }}">
                                <input type="password" name="password" id="password" placeholder="Contraseña...">
                            </div>
                            <div class="ui divider hidden"></div>
                            <div class="ui divider hidden"></div>
                            <button class="ui primary fluid button">
                                <i class="sign in alternate icon"></i>
                                Ingresar
                            </button>
                            <div class="ui divider hidden"></div>
                            <a href="{{ route('password.request') }}" class="ui">Olvidaste tu contraseña?</a>
                            <div class="ui hidden divider"></div>
                            Aún no tienes una cuenta? <a href="{{ route('register') }}" class="ui">Registrarse</a>
                            {!! Form::close() !!}
                            @if($errors->any())
                                <div class="ui error message">
                                    <ul class="list">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
