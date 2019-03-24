@component('mail::message')
# Introduction

<h6>Estimado Docente, la Actividad: {{ $evento->titulo }} ha finalizado.</h6>

<p>Información de la Actividad:</p>
<ul>
    <li>Descripcion: {{ $evento->descripcion }}</li>
    <li>Fecha: {{ $evento->fecha }}</li>
    <li>Hora: {{ $evento->hora }}</li>
</ul>
<p>Informacion de la Materia:</p>
<ul>
    <li>Materia: {{ $evento->materia_docente->materia->nombre }}</li>
    <li>Aula: {{ $evento->aula }}</li>
</ul>

<p>Finalmente, ¿considera usted que la actividad se ha sido cumplido con exito?</p>

@component('mail::button', ['url' => $url_confirmacion, 'color' => 'success'])
    Afirmativo
@endcomponent
@component('mail::button', ['url' => $url_reprogramar, 'color' => 'primary'])
    Reprogramar
@endcomponent

Atentamente,<br>
{{ config('app.name') }}
@endcomponent
