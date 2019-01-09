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
Route::resource('folios.audios', 'Folio\FolioAudioController', ['only' => ['index', 'store']]);
Route::resource('folios.documentos', 'Folio\FolioDocumentoController', ['only' => ['index', 'store']]);

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
Route::resource('paquetes', 'Paquete\PaqueteController', ['except' => ['create','edit','store']]);

/*
	Servicios
*/
Route::resource('servicios', 'Servicio\ServicioController', ['except' => ['create','edit']]);

/*
	Usuarios
*/
Route::resource('users', 'User\UserController', ['except' => ['create','edit']]);
Route::resource('users.folios', 'User\UserFolioController', ['except' => ['create', 'edit', 'store']]);

/*
	Tipos Usuarios
*/
Route::resource('tiposusuarios', 'TipoUsuario\TipoUsuarioController', ['except' => ['create', 'edit']]);


/*
	Documentos
*/
Route::resource('documentos', 'Documento\DocumentoController', ['only' => ['destroy']]);

/*
	Audios
*/
Route::resource('audios', 'Audio\AudioController', ['only' => ['destroy']]);
