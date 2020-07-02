<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Puesto;
use App\Categoria_Salarial;
use App\Catalogo_Isr;
use App\Unidad_Organizacional;
//usamos esto para poder usar las funciones DB::table
use Illuminate\Support\Facades\DB;

class PuestoController extends Controller
{
    
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $puestos = DB::table('puestos as p')
            ->join('categorias_salariales as cs', 'p.id_categoria_salarial','=','cs.id_categoria_salarial')
            ->join('unidades_organizacionales as u', 'p.codigo_unidad_organizativa','=','u.codigo_unidad_organizativa')
            ->select('p.codigo_puesto as p_id','p.nombre as nombrePuesto','p.descripcion as descPuesto','p.codigo_unidad_organizativa as p_idUnidad',
            'u.codigo_unidad_organizativa as id_unidad','u.nombre as nombreUnidad','cs.id_categoria_salarial as cs_id',
            'cs.salario_minimo as cs_min','cs.salario_maximo as cs_max','cs.id_catalogo_isr as cs_isr')
            ->orderBy('nombrePuesto', 'desc')
            ->paginate(6);
        }
        //Con el campo "Buscar" con contenido
        else{
            //Cuando la búsqueda es de la tabla puestos
            if($criterio=='codigo_puesto' || $criterio=='nombre'){
                $puestos = DB::table('puestos as p')
                ->join('categorias_salariales as cs', 'p.id_categoria_salarial','=','cs.id_categoria_salarial')
                ->join('unidades_organizacionales as u', 'p.codigo_unidad_organizativa','=','u.codigo_unidad_organizativa')
                ->select('p.codigo_puesto as p_id','p.nombre as nombrePuesto','p.descripcion as descPuesto','p.codigo_unidad_organizativa as p_idUnidad',
                'u.codigo_unidad_organizativa as id_unidad','u.nombre as nombreUnidad','cs.id_categoria_salarial as cs_id',
                'cs.salario_minimo as cs_min','cs.salario_maximo as cs_max','cs.id_catalogo_isr as cs_isr')
                ->where('p.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('nombrePuesto', 'desc')
                ->paginate(6);
            }
            else{
                //Cuando la búsqueda es de la tabla categorias_salariales
                if($criterio=='salario_minimo' || $criterio=='salario_maximo'){
                    $puestos = DB::table('puestos as p')
                    ->join('categorias_salariales as cs', 'p.id_categoria_salarial','=','cs.id_categoria_salarial')
                    ->join('unidades_organizacionales as u', 'p.codigo_unidad_organizativa','=','u.codigo_unidad_organizativa')
                    ->select('p.codigo_puesto as p_id','p.nombre as nombrePuesto','p.descripcion as descPuesto','p.codigo_unidad_organizativa as p_idUnidad',
                    'u.codigo_unidad_organizativa as id_unidad','u.nombre as nombreUnidad','cs.id_categoria_salarial as cs_id',
                    'cs.salario_minimo as cs_min','cs.salario_maximo as cs_max','cs.id_catalogo_isr as cs_isr')
                    ->where('cs.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('cs_min', 'desc')
                    ->paginate(6);
                }
                else{
                    //Cuando la búsqueda es de la tabla unidades_organizacionales
                    if($criterio=='nombreu'){
                        $puestos = DB::table('puestos as p')
                        ->join('categorias_salariales as cs', 'p.id_categoria_salarial','=','cs.id_categoria_salarial')
                        ->join('unidades_organizacionales as u', 'p.codigo_unidad_organizativa','=','u.codigo_unidad_organizativa')
                        ->select('p.codigo_puesto as p_id','p.nombre as nombrePuesto','p.descripcion as descPuesto','p.codigo_unidad_organizativa as p_idUnidad',
                        'u.codigo_unidad_organizativa as id_unidad','u.nombre as nombreUnidad','cs.id_categoria_salarial as cs_id',
                        'cs.salario_minimo as cs_min','cs.salario_maximo as cs_max','cs.id_catalogo_isr as cs_isr')
                        ->where('u.nombre', 'like', '%'. $buscar . '%')
                        ->orderBy('nombreUnidad', 'desc')
                        ->paginate(6);
                    }

                }
            }
            
        }

        return [
            'pagination' => [
                'total'         => $puestos->total(),
                'current_page'  => $puestos->currentPage(),
                'per_page'      => $puestos->perPage(),
                'last_page'     => $puestos->lastPage(),
                'from'          => $puestos->firstItem(),
                'to'            => $puestos->lastItem(),
            ],
            'puestos' => $puestos
        ];
    }


    public function selectUnidad(Request $request)
    {
        if(!$request->ajax()) return redirect('/');{
            $unidad = DB::table('unidades_organizacionales')
            ->get();

            return $unidad;
        }
    }

     //GUARDAR Puesto
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');  
        
        //Creamos el nuevo id para la categoria salarial
        $rangoSal_idMax = Categoria_Salarial::max('id_categoria_salarial');
        $id_rango=$rangoSal_idMax+1; //utilizo var $id_rango, solo dentro de este método
        
        //Guardamos rango salarial en tabla categoria_salarial
        $rangoSal = new Categoria_Salarial();
        $rangoSal->id_categoria_salarial = $id_rango;
        $rangoSal->salario_minimo = $request->cs_min;
        $rangoSal->salario_maximo = $request->cs_max;
        $rangoSal->id_catalogo_isr = $request->cs_isr;
        //$rangoSal->save();
        try{
            $rangoSal->save();
         }
         catch(\Exception $e){
            error_log($e->getMessage());
         }
        //error_log($request->p_id);
         $puesto = new Puesto();
        //el primer "codigo_puesto" coincide con el nombre de la bd; el segundo "codigo_puesto" debe coincidir con la variable de la vista
        $puesto->codigo_puesto = $request->p_id;
        $puesto->nombre = $request->nombrePuesto;
        $puesto->descripcion = $request->descPuesto;
        $puesto->id_categoria_salarial = $id_rango;
        $puesto->codigo_unidad_organizativa = $request->p_idUnidad;
        //$puesto->save();
        try{
            $puesto->save();
         }
         catch(\Exception $e){
            error_log($e->getMessage());
         }
    }

    //ACTUALIZAR Puesto
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //
        $rangoSal = Categoria_Salarial::findOrFail($request->cs_id);
        $rangoSal->id_categoria_salarial = $request->cs_id;
        $rangoSal->salario_minimo = $request->cs_min;
        $rangoSal->salario_maximo = $request->cs_max;
        $rangoSal->id_catalogo_isr = $request->cs_isr;
        try{
            $rangoSal->save();
         }
         catch(\Exception $e){
            error_log($e->getMessage());
         }

        $puesto = Puesto::findOrFail($request->p_id);
        //el primer "codigo_puesto" coincide con el nombre de la bd; el segundo "codigo_puesto" debe coincidir con la variable de la vista
        $puesto->codigo_puesto = $request->p_id;
        $puesto->nombre = $request->nombrePuesto;
        $puesto->descripcion = $request->descPuesto;
        $puesto->id_categoria_salarial = $request->cs_id;
        $puesto->codigo_unidad_organizativa = $request->p_idUnidad;
        try{
            $puesto->save();
         }
         catch(\Exception $e){
            error_log($e->getMessage());
         }
    }

    //**
    public function destroy(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $puesto = Puesto::findOrFail($request->p_id);
        try{
        $puesto->delete();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
        
        $rangoSal = Categoria_Salarial::findOrFail($request->cs_id);
        try{
        $rangoSal->delete();
        }catch(\Exception $e){
            error_log($e->getMessage());
        }
    }
}
