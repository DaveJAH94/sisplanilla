<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Privilegio;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $privilegios = Privilegio::all();
        return $privilegios;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $privilegio = new Privilegio();
        $privilegio->nombre = $request->nombre;
        $privilegio->accion = $request->accion;
        $privilegio->entidad = $request->entidad;
        $privilegio->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $privilegio = Privilegio::findOrFail($request->id_privilegio);//Se busca el objeto que se recibe segun este id, para actualizarlo
        $privilegio->nombre = $request->nombre;
        $privilegio->accion = $request->accion;
        $privilegio->entidad = $request->entidad;
        $privilegio->save();
    }

    //Estos métodos se usarían para hacer un borrado lógico en las tablas
    /* public function desactivar(Request $request)
    {
        $privilegio = Privilegio::findOrFail($request->id_privilegio);
        $privilegio->condicion = '0';
        $privilegio->save();
    } */

    /* public function activar(Request $request)
    {
        $privilegio = Privilegio::findOrFail($request->id_privilegio);
        $privilegio->condicion = '1';
        $privilegio->save();
    } */

   
}
