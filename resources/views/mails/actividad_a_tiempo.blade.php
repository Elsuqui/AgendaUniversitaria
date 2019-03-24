<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificacion de Agenda</title>
</head>
<body>
<p> </p>
<p>Estimado docente, la siguiente actividad con prioridad:
    @switch($evento["importancia"])
        @case(1)
        <span>"{{ ' Alta ' }}"</span>
        @break
        @case(2)
        <span>"{{ ' Media ' }}"</span>
        @break
        @case(3)
        <span>"{{ ' Normal ' }}"</span>
        @break
    @endswitch
    esta comenzando!!
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
