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
});


Route::get('/detector', 'ReconocimientoDocenteController@index')->name('reconocimiento_docente');
Route::get('/cargarArchivoCascada', 'ReconocimientoDocenteController@archivoCascada')->name('archivo_cascada');
Route::get('/registro', 'ReconocimientoDocenteController@register')->name('reconocimiento_docente.register');
Route::post('/ingresar', 'ReconocimientoDocenteController@login')->name('reconocimiento_docente.login');
