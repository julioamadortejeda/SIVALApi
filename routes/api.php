<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
	Folios
*/
//Route::resource('folios', 'Folio\FolioController', ['only' => ['show']]);
Route::post('folios', 'Folio\FolioController@importarExcel');
Route::resource('folios', 'Folio\FolioController', ['except' => ['create', 'edit', 'store']]);
Route::resource('folios.audios', 'Folio\FolioAudioController', ['only' => ['index', 'store']]);
Route::resource('folios.documentos', 'Folio\FolioDocumentoController', ['only' => ['index', 'store']]);
Route::resource('folios.telefonos', 'Folio\FolioTelefonoController', ['only' => ['index', 'store']]);
Route::resource('folios.direcciones', 'Folio\FolioDireccionController', ['only' => ['index', 'store']]);

/* 
	Folios Validacion
*/

/*
	Empleados
*/
Route::resource('empleados', 'Empleado\EmpleadoController', ['except' => ['create','edit','store']]);
Route::post('empleados', 'Empleado\EmpleadoController@import');

/*
	Paquetes
*/
Route::resource('paquetes', 'Paquete\PaqueteController', ['only' => ['index']]);

/*
	Servicios
*/
Route::resource('servicios', 'Servicio\ServicioController', ['only' => ['index']]);

/*
	Usuarios
*/
Route::resource('users', 'User\UserController', ['except' => ['create','edit']]);
//Route::resource('users.folios', 'User\UserFolioController', ['except' => ['create', 'edit', 'store']]);

/*
	Tipos Usuarios
*/
Route::resource('tiposusuarios', 'TipoUsuario\TipoUsuarioController', ['except' => ['create', 'edit', 'show']]);


/*
	Documentos
*/
Route::resource('documentos', 'Documento\DocumentoController', ['only' => ['destroy']]);

/*
	Audios
*/
Route::resource('audios', 'Audio\AudioController', ['only' => ['destroy']]);

/*
	Telefonos
*/
Route::resource('telefonos', 'Telefono\TelefonoController', ['only' => ['index', 'destroy']]);

/*
	Direcciones
*/
Route::resource('direcciones', 'Direccion\DireccionController', ['only' => ['index', 'destroy']]);

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->middleware('add.credentials');

//***************************************************************************
// RUTAS PARA OBTENER UN TOKEN PERSONAL, POR EL MOMENTO SE USARA UN TOKEN DE TIPO PASSWORD
//***************************************************************************/
// Route::group(['prefix' => 'auth'], function () {
//     Route::post('login', 'AuthController@login');
//     Route::post('signup', 'AuthController@signup');
  
//     Route::group(['middleware' => 'auth:api'], function() {
//         Route::get('logout', 'AuthController@logout');
//         Route::get('user', 'AuthController@user');
//     });
// });