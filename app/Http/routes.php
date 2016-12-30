<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return Redirect::to("index.html");
});
Route::group(['prefix'=>'ws'],function(){
	Route::any('login', 'UsuariosController@login');
	Route::post('upload', 'VentasController@ImportarExcel');
	Route::resource('usuarios', 'UsuariosController');
	Route::resource('tiposusuarios', 'TiposUsuariosController');
	Route::resource('categorias', 'CategoriasController');
	Route::resource('modelos', 'ModelosController');
	Route::resource('series', 'SeriesController');
	Route::resource('sinonimos', 'SinonimosController');
	Route::resource('paises', 'PaisesController');
	Route::resource('sucursales', 'SucursalesController');
	Route::resource('puntosventas', 'PuntosVentasController');
	Route::resource('inventarios', 'VentasController');
	Route::resource('permisos', 'PermisosController');
});