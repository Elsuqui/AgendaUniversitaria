<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(["middleware" => ["auth"]], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/splash', 'HomeController@splash')->name('splash');
    Route::get('/eventos', 'EventosController@listarEventos')->name('eventos');
    Route::post('/eventos/nuevo', 'EventosController@guardarEvento')->name('eventos.nuevo');
    Route::put('/eventos/editar/{id}', 'EventosController@editarEvento')->name('eventos.editar');
    Route::delete('/eventos/eliminar/{id}', 'EventosController@eliminarEvento')->name('eventos.eliminar');
    Route::get('/eventos/listado', 'EventosController@eventos')->name('eventos.list');
    Route::get('/eventos/ver/{id}', 'EventosController@ver')->name('eventos.show');
    Route::get('/materias/asignaciones', 'MateriaController@asignacionIndex')->name('asignacion');
    Route::get("/materias/docentes", "MateriaController@listadoDocentes")->name('listado.docente');
    Route::get("/materias/asignadas", "MateriaController@materiasAsignadas")->name("materias.asignadas");
    Route::resource('materias', 'MateriaController');
    Route::get("/probar/{id}", "EventosController@probarNotificacionEvento");
    Route::get("/notificaciones", "EventosController@obtenerNotificaciones");
    Route::get("/marcar/notificacion/{id}", "EventosController@lecturaNotificacionTarea");
    Route::get("/probar/notificacion/{id}", "EventosController@probarBusquedaNotifiacion");
    Route::get("/confirmar/evento/{id}", "EventosController@confirmarEvento")->name("confirmar.evento");
    Route::get("/reprogramar/evento/{id}", "EventosController@reprogramarEvento")->name("reprogramar.evento");
    Route::post('/reprogramar/evento/nueva/actividad', 'EventosController@realizarReprogramacion')->name("realizar.reprogramacion");
});


Route::get('/detector', 'ReconocimientoDocenteController@index')->name('reconocimiento_docente');
Route::get('/cargarArchivoCascada', 'ReconocimientoDocenteController@archivoCascada')->name('archivo_cascada');
Route::get('/registro', 'ReconocimientoDocenteController@register')->name('reconocimiento_docente.register');
Route::post('/ingresar', 'ReconocimientoDocenteController@login')->name('reconocimiento_docente.login');
