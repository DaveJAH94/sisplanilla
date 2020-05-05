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
    return view('contenido/contenido');
});

Route::get('/privilegio', 'PrivilegioController@index');
Route::post('/privilegio/registrar', 'PrivilegioCOntroller@store');//Si da algun error, se puede cambiar post por put :V
Route::put('/privilegio/actualizar', 'PrivilegioController@update');

Route::get('/rol', 'RolController@index');