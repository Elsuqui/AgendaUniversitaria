<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificacion de Agenda</title>
</head>
<body>
{{--@if($evento["id_usuario_edicion"] == null)
    <p>Estimado docente, se ha reportado un nuevo ingreso de agenda a las {{ $evento["created_at"] }}.</p>
    <p>Estimado docente, su agenda le recuerda que tiene agendada la siguiente actividad: </p>
@else
    <p>Estimado docente, se ha reportado una modificacion de agenda a las {{ $evento["updated_at"] }}.</p>
@endif--}}

@if($evento["id_usuario_edicion"] == null && $evento["estado_control"] == "PENDIENTE")
    <p>Estimado docente, su agenda le recuerda que tiene planificada la siguiente actividad: </p>
@elseif($evento["id_usuario_edicion"] != null && $evento["estado_control"] == "PENDIENTE")
    <p>Estimado docente, su agenda le recuerda que la siguiente actividad ha sido modificada: </p>
@elseif($evento["id_usuario_edicion"] != null && $evento["estado_control"] == "REPROGRAMADA")
    <p>Estimado docente, su agenda le recuerda que la siguiente actividad ha sido reprogramada: </p>
@else
    <p>Estimado docente, su agenda le recuerda que la siguiente actividad ha sido cancelada: </p>
@endif

@if($evento["estado_control"] != "CANCELADA")
    <p>La Actividad se realizará en: {{ $evento["tiempo_restante"] }}</p>
@else
    <p>La Actividad estaba destinada realizarse en: {{ $evento["tiempo_restante"] }}</p>
@endif

<p>Importancia:
    @switch($evento["importancia"])
        @case(1)
        <span>"{{ 'Alta' }}"</span>
        @break
        @case(2)
        <span>"{{ 'Media' }}"</span>
        @break
        @case(3)
        <span>"{{ 'Normal' }}"</span>
        @break
    @endswitch
</p>
<p>Información de la Actividad:</p>
<ul>
    <li>Titulo: {{ $evento["titulo"] }}</li>
    <li>Descripcion: {{ $evento["descripcion"] }}</li>
    <li>Fecha: {{ $evento["fecha"] }}</li>
    <li>Hora: {{ $evento["hora"] }}</li>
</ul>
<p>Informacion de la Materia:</p>
<ul>
    <li>Materia: {{ $evento["materia_docente"]["materia"]["nombre"] }}</li>
    <li>Aula: {{ $evento["aula"] }}</li>
</ul>
</body>
</html>
