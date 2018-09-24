@extends('layouts.app')

@section('content')
    <div class="ui container" style="padding-top: 5%;">
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
                                        <input type="text" value="{{ old('name') }}" name="name" id="name"
                                               placeholder="Nombre">
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
                                        <input type="password" name="password" id="password"
                                               placeholder="Contraseña...">
                                    </div>
                                </div>
                                <div class="required field">
                                    <label>Confirmar Contraseña</label>
                                    <div class="ui input fluid field">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                               placeholder="Confirmar Contraseña...">
                                    </div>
                                </div>
                            </div>
                            <img class="ui small bordered rectangle image" id="foto_temp" src="{{ asset('imagenes/default-user.png') }}"/>
                            <div class="field">
                                <input type="file" class="ui input fluid field" id="foto"/>
                            </div>

                            <button type="submit" class="ui blue submit fluid button">Registrarse</button>
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

@section('js')
    <script>
        $('#foto').change(function() {
            //El path ha cambiado se obtiene el base 64 y se lo coloca en la imagen y se crea un file reader para esto
            var reader  = new FileReader();
            var file = document.getElementById('foto').files[0];
            reader.readAsDataURL(file);
            reader.onload = function(e) {
                // Foto Cargada correctamente
                $('#foto_temp').attr('src', e.target.result);
            };
        });
    </script>
@endsection
