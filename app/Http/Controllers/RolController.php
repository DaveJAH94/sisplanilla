<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $roles = Rol::orderBy('id_rol', 'desc')->paginate(6);
        }
        else{
            $roles = Rol::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_rol', 'desc')->paginate(6);
        }

        return [
            'pagination' => [
                'total'         => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'      => $roles->perPage(),
                'last_page'     => $roles->lastPage(),
                'from'          => $roles->firstItem(),
                'to'            => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

     //GUARDAR ROL
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->save();        
    }

    //ACTUALIZAR ROL
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rol = Rol::findOrFail($request->id_rol);
        $rol->nombre = $request->nombre;
        $rol->save();
    }

    //**
    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $rol = Rol::findOrFail($request->id_rol);
        $rol->delete();   
    }

    

}
