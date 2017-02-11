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
Route::get('/reporte', function () {
    return view("reporte");
});
Route::group(['prefix'=>'ws'],function(){
	Route::any('login', 'UsuariosController@login');
	Route::any('sucursalesporpais', 'SucursalesController@sucursales_por_pais');
	Route::any('puntosventasporsucursal', 'PuntosVentasController@puntosventas_por_sucursal');
	Route::any('seriesporcategoria', 'SeriesController@series_por_categoria');
	Route::any('modelosporserie','ModelosController@modelos_por_serie');
	Route::any('filtro','VentasController@filtro');
	Route::any('exportarexcel','VentasController@exportarexcel');
	Route::any('obtenerregistros', 'VentasController@obtenerregistros');
	Route::any('obtenerselloutpuntoventa','VentasController@sell_out_punto_ventas');
	Route::any('obtenerventasporsemana','VentasController@ventas_por_semana');
	Route::any('actualizarregistro','VentasController@agregarItem');
	Route::any('tendenciaPorCategoria', 'DashboardSelloutVentasController@tendenciaPorCategoria');
    Route::any('tendenciaPorSerie', 'DashboardSelloutVentasController@tendenciaPorSerie');
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
	Route::resource('ventaspendientes', 'VentasPendientesController');
	Route::resource('top15modelsellout', 'Top15ModelSelloutController');
	Route::resource('top15pdvsellout', 'Top15PDVSelloutController');
    Route::resource('dashboardSelloutVentas', 'DashboardSelloutVentasController');
    Route::resource('cuentas', 'CuentasController');
    Route::resource('plantillas', 'PlantillasController');
    Route::resource('categoriasPlantillas', 'CategoriasPlantillasController');
});