<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\TipoIngreso;
use App\TipoDescuento;


class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Para redigir en caso se acceda directo por URL
        //if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        
            if ($buscar==''){
                $empleados = Empleado::select('primer_nombre', 'codigo_empleado', 'segundo_nombre', 'primer_apellido', 'segundo_apellido')->orderBy('codigo_empleado', 'desc')->paginate(6);
               //error_log('Primer registro: '+$empleados[0]["primer_nombre"]);
            }
            else{
                $empleados = Empleado::select('primer_nombre', 'codigo_empleado', 'segundo_nombre', 'primer_apellido', 'segundo_apellido')->where($criterio, 'like', '%'. $buscar . '%')->orderBy('codigo_empleado', 'desc')->paginate(6);
            }

        
        

        return [
            'pagination' => [
                'total'        => $empleados->total(),
                'current_page' => $empleados->currentPage(),
                'per_page'     => $empleados->perPage(),
                'last_page'    => $empleados->lastPage(),
                'from'         => $empleados->firstItem(),
                'to'           => $empleados->lastItem(),
            ],
            'empleados' => $empleados
        ];
    }

    public function selectTiposInDe(Request $request){

        $tipo = $request->tipo;
        $tipos = [];

        //error_log('tipo: ');
        //var_dump($tipo);

        if($tipo == 'ing'){
            $tipos = TipoIngreso::select('codigo_tipo_ingreso','nombre')->get();
        }else if($tipo == 'des'){
            $tipos = TipoDescuento::select('codigo_tipo_descuento','nombre')->get();
        }
        
        return ['tipos' => $tipos];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
