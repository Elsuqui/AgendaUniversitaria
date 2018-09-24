@extends('layouts.app')

@section('content')
<div class="ui container" style="padding-top: 10%;">
    <div class="ui equal width center aligned padded grid stackable">
        <div class="row">
            <div class="fourteen wide column">
                <div class="ui segments">
                    <div class="ui segment inverted nightli">
                        <h3 class="ui header">
                            Registrar Nuevo Alumno
                        </h3>
                        <img src="{{ asset('imagenes/ucsg.png') }}">
                    </div>
                    <div class="ui segment">
                        <div class="description">
                            Llenar el formulario para ingresar nuevo Alumno
                        </div>
                        {!! Form::open(['url' => route('register'), 'class' => 'ui form segment']) !!}
                        <div class="ui divider"></div>
                        <div class="two fields">
                            <div class="required field">
                                <label>Nombre</label>
                                <div class="ui input fluid field {{ $errors->has('name') ? 'error' : '' }}">
                                    <input type="text" value="{{ old('name') }}" name="name" id="name" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="required field">
                                <label>Correo Electronico</label>
                                <div class="ui input fluid field {{ $errors->has('email') ? 'error' : '' }}">
                                    <input type="text" name="email" id="email" placeholder="Correo Electronico...">
                                </div>
                            </div>
                        </div>
                        <div class="ui divider hidden"></div>
                        <div class="two fields">
                            <div class="required field">
                                <label>Contraseña</label>
                                <div class="ui input fluid field {{ $errors->has('password') ? 'error' : '' }}">
                                    <input type="password" name="password" id="password" placeholder="Contraseña...">
                                </div>
                            </div>
                            <div class="required field">
                                <label>Confirmar Contraseña</label>
                                <div class="ui input fluid field">
                                    <input type="password" name="password_confirmation" id="password-confirm" placeholder="Confirmar Contraseña...">
                                </div>
                            </div>
                        </div>
                        <div class="ui blue submit fluid button">Registrarse</div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
