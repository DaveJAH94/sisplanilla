<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria_Comision;
use App\Puesto;

class Categoria_ComisionController extends Controller
{
    public function index(Request $request)
    {
        //Para redigir en caso se acceda directo por URL
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $categorias_comisiones = Categoria_Comision::orderBy('ventas_minimas', 'asc')->paginate(10);
        }
        else{
            $categorias_comisiones = Categoria_Comision::where($criterio, 'like', '%'. $buscar . '%')->orderBy('ventas_minimas', 'asc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'        => $categorias_comisiones->total(),
                'current_page' => $categorias_comisiones->currentPage(),
                'per_page'     => $categorias_comisiones->perPage(),
                'last_page'    => $categorias_comisiones->lastPage(),
                'from'         => $categorias_comisiones->firstItem(),
                'to'           => $categorias_comisiones->lastItem(),
            ],
            'categorias_comisiones' => $categorias_comisiones
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
        $categoria_comision = new Categoria_Comision();
        $categoria_comision->ventas_minimas = $request->ventas_minimas;
        $categoria_comision->ventas_maximas = $request->ventas_maximas;
        $categoria_comision->porcentaje = $request->porcentaje;
        $categoria_comision->codigo_puesto = $request->codigo_puesto;
        $categoria_comision->save();
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
        $categoria_comision = Categoria_Comision::findOrFail($request->id_categoria_comision);//Se busca el objeto que se recibe segun este id, para actualizarlo
        $categoria_comision->ventas_minimas = $request->ventas_minimas;
        $categoria_comision->ventas_maximas = $request->ventas_maximas;
        $categoria_comision->porcentaje = $request->porcentaje;
        $categoria_comision->codigo_puesto = $request->codigo_puesto;
        $categoria_comision->save();
    }

    //Metodo de eliminado
    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categoria_comision = Categoria_Comision::findOrFail($request->id_categoria_comision);
        $categoria_comision->delete();
    }
}
