<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Iluminate\Support\Facades\DB;
use App\Categoria_Salarial;

class Categoria_SalarialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Para redigir en caso se acceda directo por URL
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $categorias_salariales = Categoria_Salarial::orderBy('id_categoria_salarial', 'desc')->paginate(6);
        }
        else{
            $categorias_salariales = Categoria_Salarial::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_categoria_salarial', 'desc')->paginate(6);
        }

        return [
            'pagination' => [
                'total'        => $categorias_salariales->total(),
                'current_page' => $categorias_salariales->currentPage(),
                'per_page'     => $categorias_salariales->perPage(),
                'last_page'    => $categorias_salariales->lastPage(),
                'from'         => $categorias_salariales->firstItem(),
                'to'           => $categorias_salariales->lastItem(),
            ],
            'categorias_salariales' => $categorias_salariales
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categoria_salarial = new Categoria_Salarial();
        $categoria_salarial->salario_minimo = $request->salario_minimo;
        $categoria_salarial->salario_maximo = $request->salario_maximo;
        $categoria_salarial->id_catalogo_isr = $request->id_catalogo_isr;
        $categoria_salarial->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categoria_salarial = Categoria_Salarial::findOrFail($request->id_categoria_salarial);//Se busca el objeto que se recibe segun este id, para actualizarlo
        $categoria_salarial->salario_minimo = $request->salario_minimo;
        $categoria_salarial->salario_maximo = $request->salario_maximo;
        $categoria_salarial->id_catalogo_isr = $request->id_catalogo_isr;
        $categoria_salarial->save();
    }

    //Metodo de eliminado
    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categoria_salarial = Categoria_Salarial::findOrFail($request->id_categoria_salarial);
        $categoria_salarial->delete();
    }

    //Estos métodos se usarían para hacer un borrado lógico en las tablas
    /* public function activar(Request $request)
    {
        $categoria_salarial = Categoria_Salarial::findOrFail($request->id_categoria_salarial);
        $categoria_salarial->condicion = '1';
        $categoria_salarial->save();
    } */
}
