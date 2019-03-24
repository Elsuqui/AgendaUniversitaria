@extends('layouts.app')

@section('content')
    <div class="ui modal" id="modal_materias" style="height: 100vh; overflow: scroll;">
        <div class="header">
            Gestion de Materia
        </div>
        <div class="content">
            <form id="form_materia" method="POST" action="{{ config("app.url") . route("materias.store") }}" class="ui form">
                @csrf
                <input type="hidden" id="metodo_form" name="_method" value="POST">
                <div class="field">
                    <label for="nombre">Nombre de Materia</label>
                    <input id="nombre" name="nombre" type="text"/>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ui approve button" onclick="$('#form_materia').submit()">Guardar</button>
            <button class="ui cancel button">Cancelar</button>
        </div>
    </div>


    <div class="ui container-fluid">
        <div class="ui segments">
            <div class="ui segment">
                <div class="ui header">CATALOGO DE MATERIAS</div>
            </div>
            <div class="ui segment">
                <button id="nueva_materia" class="ui primary button">NUEVA MATERIA</button>
                <table class="ui celled table">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materias as $materia)
                        <tr>
                            <td>{{ $materia->nombre }}</td>
                            <td>
                                <button class="ui primary button editar" value="{{ $materia->id }}">Editar</button>
                                <a class="ui primary button" href="{{ route("materias.destroy", $materia->id) }}">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("js")
    <script>
        $(document).ready(function (){
            $(".editar").on("click", function(){
                let id = $(this).val();
                $.get("materias/" + id, function(response){
                    let ruta = '{{ route('materias.index') }}' + "/" + + id;
                    $("#form_materia").attr("action", ruta);
                    $("#nombre").val(response.materia.nombre);
                    $("#metodo_form").val("PUT");
                    $("#modal_materias").modal('setting', 'closable', false).modal("show");
                });
            });

            $("#nueva_materia").on("click", function (){
                let ruta = '{{ route('materias.store') }}';
                $("#modal_materias").modal('setting', 'closable', false).modal("show");
                $("#form_materia").attr("action", ruta);
                $("#metodo_form").val("POST");
            });
        });
    </script>
@endsection
