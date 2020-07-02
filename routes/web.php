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
Route::post('/privilegio/registrar', 'PrivilegioController@store');
Route::put('/privilegio/actualizar', 'PrivilegioController@update');
Route::put('/privilegio/eliminar', 'PrivilegioController@destroy');

Route::get('/rol', 'RolController@index');
Route::post('/rol/registrar', 'RolController@store');
Route::put('/rol/actualizar', 'RolController@update');
Route::put('/rol/eliminar', 'RolController@destroy');

Route::get('/usuario', 'UsuarioController@index');
Route::post('/usuario/registrar', 'UsuarioController@store');
Route::put('/usuario/actualizar', 'UsuarioController@update');
Route::put('/usuario/eliminar', 'UsuarioController@destroy');
Route::get('/rol/selectRoles', 'UsuarioController@selectRoles');
Route::get('/empleado/selectEmpleado', 'UsuarioController@selectEmpleados');
Route::post('/rolprivilegio/registrar', 'Roles_PrivilegiosController@store');

Route::get('/empleado/selectEmpleado', 'UsuarioController@selectEmpleados');
Route::get('/empleado','EmpleadoController@index');
Route::post('/empleado/registrar','EmpleadoController@store');
Route::put('/empleado/actualizar','EmpleadoController@update');
Route::put('/empleado/desactivar','EmpleadoController@desactivar');
Route::put('/empleado/activar','EmpleadoController@activar');

Route::get('/ingresos', 'IngresoController@index');
Route::get('ingresos/selectTiposInDe', 'IngresoController@selectTiposInDe');
//Route::get('/periodo', 'PeriodoController@index');

Route::get('/categoriaSalarial', 'Categoria_SalarialController@index');
Route::post('/categoriaSalarial/registrar', 'Categoria_SalarialController@store');
Route::put('/categoriaSalarial/actualizar', 'Categoria_SalarialController@update');
Route::put('/categoriaSalarial/eliminar', 'Categoria_SalarialController@destroy');

Route::get('/unidadOrganizacional', 'Unidad_OrganizacionalController@index');
Route::post('/unidadOrganizacional/registrar', 'Unidad_OrganizacionalController@store');
Route::put('/unidadOrganizacional/actualizar', 'Unidad_OrganizacionalController@update');
Route::put('/unidadOrganizacional/eliminar', 'Unidad_OrganizacionalController@destroy');
Route::get('/unidadOrganizacional/selectUnidadSuperior', 'Unidad_OrganizacionalController@selectSuperiores');

Route::get('/puesto', 'PuestoController@index');
Route::post('/puesto/registrar', 'PuestoController@store');
Route::put('/puesto/actualizar', 'PuestoController@update');
Route::put('/puesto/eliminar', 'PuestoController@destroy');
Route::get('/unidadOrganizacional/selectUnidades', 'PuestoController@selectUnidad');

Route::get('/categoriaComision', 'Categoria_ComisionController@index');
Route::post('/categoriaComision/registrar', 'Categoria_ComisionController@store');
Route::put('/categoriaComision/actualizar', 'Categoria_ComisionController@update');
Route::put('/categoriaComision/eliminar', 'Categoria_ComisionController@destroy');