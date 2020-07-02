<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidad_Organizacional;
use App\Limite_Presupuestario;
//usamos esto para poder usar las funciones DB::table
use Illuminate\Support\Facades\DB;

class Unidad_OrganizacionalController extends Controller
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
            $unidades_organizacionales = DB::table('unidades_organizacionales as uo')
            ->join('limites_presupuestarios as lp','uo.codigo_unidad_organizativa','=','lp.codigo_unidad_organizativa')
            ->leftjoin('unidades_organizacionales as us','uo.codigo_unidad_superior','=','us.codigo_unidad_organizativa')
            ->select('uo.codigo_unidad_organizativa as id_unidad','uo.nombre as nombre_unidad','uo.descripcion as desc_unidad',
            'uo.codigo_unidad_superior as sup_unidad', 'us.codigo_unidad_organizativa as id_superior','us.nombre as uni_superior',
            'lp.id_limite_presupuestario as lp_id','lp.codigo_unidad_organizativa as lp_unidad','lp.limite_minimo as lim_min',
            'lp.limite_maximo as lim_max')
            ->orderBy('nombre_unidad','asc')->paginate(10);
        }
        else{
            if($criterio=='codigo_unidad_organizativa' || $criterio=='nombre'){
            $unidades_organizacionales = DB::table('unidades_organizacionales as uo')
            ->join('limites_presupuestarios as lp','uo.codigo_unidad_organizativa','=','lp.codigo_unidad_organizativa')
            ->leftjoin('unidades_organizacionales as us', 'uo.codigo_unidad_superior','=','us.codigo_unidad_organizativa')
            ->select('uo.codigo_unidad_organizativa as id_unidad','uo.nombre as nombre_unidad','uo.descripcion as desc_unidad',
            'uo.codigo_unidad_superior as sup_unidad', 'us.codigo_unidad_organizativa as id_superior','us.nombre as uni_superior',
            'lp.id_limite_presupuestario as lp_id','lp.codigo_unidad_organizativa as lp_unidad','lp.limite_minimo as lim_min',
            'lp.limite_maximo as lim_max')
            ->where('uo.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('nombre_unidad','desc')->paginate(10);
            }
            else{
                if($criterio=='nombre_sup'){
                    $unidades_organizacionales = DB::table('unidades_organizacionales as uo')
                    ->join('limites_presupuestarios as lp','uo.codigo_unidad_organizativa','=','lp.codigo_unidad_organizativa')
                    ->leftjoin('unidades_organizacionales as us', 'uo.codigo_unidad_superior','=','us.codigo_unidad_organizativa')
                    ->select('uo.codigo_unidad_organizativa as id_unidad','uo.nombre as nombre_unidad','uo.descripcion as desc_unidad',
                    'uo.codigo_unidad_superior as sup_unidad', 'us.codigo_unidad_organizativa as id_superior','us.nombre as uni_superior',
                    'lp.id_limite_presupuestario as lp_id','lp.codigo_unidad_organizativa as lp_unidad','lp.limite_minimo as lim_min',
                    'lp.limite_maximo as lim_max')
                    ->where('us.nombre', 'like', '%'. $buscar . '%')
                    ->orderBy('uni_superior','desc')->paginate(10);
                }
            }
        }

        return [
            'pagination' => [
                'total'        => $unidades_organizacionales->total(),
                'current_page' => $unidades_organizacionales->currentPage(),
                'per_page'     => $unidades_organizacionales->perPage(),
                'last_page'    => $unidades_organizacionales->lastPage(),
                'from'         => $unidades_organizacionales->firstItem(),
                'to'           => $unidades_organizacionales->lastItem(),
            ],
            'unidades_organizacionales' => $unidades_organizacionales
        ];
    }

    //Listar Unidades Organizativas Superiores
    public function selectSuperiores(Request $request){
        if (!$request->ajax()) return redirect('/');
        $unidades_superiores = Unidad_Organizacional::select('codigo_unidad_organizativa as id_superior','nombre as uni_superior')->orderBy('uni_superior', 'asc')->get();
        return ['unidades_superiores' => $unidades_superiores];
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

        //Guardamos la unidad organizacional
        $unidad_organizacional = new Unidad_Organizacional();
        $unidad_organizacional->codigo_unidad_organizativa = $request->id_unidad;
        $unidad_organizacional->codigo_unidad_superior = $request->sup_unidad;
        $unidad_organizacional->nombre = $request->nombre_unidad;
        $unidad_organizacional->descripcion = $request->desc_unidad;
        try{
            $unidad_organizacional->save();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }

        //Creamos el id para el nuevo límite prespuestario
        $limPre_idMax = Limite_Presupuestario::max('id_limite_presupuestario');
        $id_limPre = $limPre_idMax+1;

        //Guardamos el límite presupuestario en su tabla
        $limPre = new Limite_Presupuestario();
        $limPre->id_limite_presupuestario = $id_limPre;
        $limPre->codigo_unidad_organizativa = $request->id_unidad;
        $limPre->limite_minimo = $request->lim_min;
        $limPre->limite_maximo = $request->lim_max;
        try{
            $limPre->save();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
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
        
        $unidad_organizacional = Unidad_Organizacional::findOrFail($request->id_unidad);//Se busca el objeto que se recibe segun este id, para actualizarlo
        $unidad_organizacional->codigo_unidad_organizativa = $request->id_unidad;
        $unidad_organizacional->codigo_unidad_superior = $request->sup_unidad;
        $unidad_organizacional->nombre = $request->nombre_unidad;
        $unidad_organizacional->descripcion = $request->desc_unidad;
        try{
            $unidad_organizacional->save();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }

        //Actualizando limites presupuestarios
        $limPre = Limite_Presupuestario::findOrFail($request->lp_id);
        $limPre->id_limite_presupuestario = $request->lp_id;
        $limPre->codigo_unidad_organizativa = $request->id_unidad;
        $limPre->limite_minimo = $request->lim_min;
        $limPre->limite_maximo = $request->lim_max;
        try{
            $limPre->save();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
    }

    //Metodo de eliminado
    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $limPre = Limite_Presupuestario::findOrFail($request->lp_id);
        try{
            $limPre->delete();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
        
        $unidad_organizacional = Unidad_Organizacional::findOrFail($request->id_unidad);
        try{
            $unidad_organizacional->delete();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
    }

    //Estos métodos se usarían para hacer un borrado lógico en las tablas
    /* public function activar(Request $request)
    {
        $categoria_salarial = Categoria_Salarial::findOrFail($request->id_categoria_salarial);
        $categoria_salarial->condicion = '1';
        $categoria_salarial->save();
    } */
}
