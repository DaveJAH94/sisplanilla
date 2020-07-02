<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $empleados = Empleado::join('generos','empleados.id_genero','=','empleados.id')
            ->select('empleados.id','empleados.idcategoria','empleados.titulo','empleados.codigo_empleado','empleados.primer_nombre','empleados.segundo_nombre','empleados.primer_apellido','empleados.segundo_apellido','empleados.codigo_jefe')
            ->orderBy('empleados.id', 'desc')->paginate(3);
        }


        else{
            $empleados = Empleado::join('generos','empleados.idgenero','=','empleados.id')
            ->select('empleados.id','empleados.idcategoria','empleados.titulo','empleados.codigo_empleado','empleados.primer_nombre','empleados.segundo_nombre','empleados.primer_apellido','empleados.segundo_apellido','empleados.codigo_jefe')
            ->where('empleados.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('empleados.id', 'desc')->paginate(3);
        }




/*

        if ($buscar==''){
            $empleados = Empleado::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $empleados = Empleado::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        
*/
        return [
            'pagination' => [
                'total'        => $empleados->total(),
                'current_page' => $empleados->currentPage(),
                'per_page'     => $empleados->perPage(),
                'last_page'    => $empleados->lastPage(),
                'from'         => $empleados->firstItem(),
                'to'           => $empleados->lastItem(),
            ],
            'Empleados' => $empleados
        ];
    }

    public function selectEmpleado(Request $request){
        if (!$request->ajax()) return redirect('/');
        $empleados = Empleado::where('condicion','=','1')
        ->select('id','primer_nombre')->orderBy('primer_nombre', 'asc')->get();
        return ['categorias' => $empleados];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         $empleado=new Empleado();
         $empleado->primer_nombre =$request->primer_nombre;
         $empleado->segundo_nombre=$request->segundo_nombre;
         $empleado->primer_apellido=$request->primer_apellido;
         $empleado->segundo_apellido=$request->segundo_apellido;
         $empleado->email_personal=$request->email_personal;
         $empleado->email_institucional=$request->email_institucional;
         $empleado->fecha_nacimiento=$request->fecha_nacimiento;
         $empleado->identificador_isss=$request->identificador_isss;
         $empleado->identificador_nup=$request->identificador_nup;
         $empleado->identificador_nit=$request->identificador_nit;
         $empleado->codigo_profesion=$request->codigo_profesion;
         $empleado->id_direccion=$request->id_direccion;
         $empleado->numero_documento_identificacion=$request->numero_documento_identificacion;
         $empleado->id_estado_civil=$request->id_estado_civil;
         $empleado->id_genero=$request->id_genero;
         $empleado->id_contacto_telefonico=$request->id_contacto_telefonico;
         $empleado->condicion='1';
         $empleado->save();

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
        if (!$request->ajax()) return redirect('/');
        $empleados = Empleado::findOrFail($request->id);
        $empleado=new Empleado();
        $empleado->primer_nombre =$request->primer_nombre;
        $empleado->segundo_nombre=$request->segundo_nombre;
        $empleado->primer_apellido=$request->primer_apellido;
        $empleado->segundo_apellido=$request->segundo_apellido;
        $empleado->email_personal=$request->email_personal;
        $empleado->email_institucional=$request->email_institucional;
        $empleado->fecha_nacimiento=$request->fecha_nacimiento;
        $empleado->identificador_isss=$request->identificador_isss;
        $empleado->identificador_nup=$request->identificador_nup;
        $empleado->identificador_nit=$request->identificador_nit;
        $empleado->codigo_profesion=$request->codigo_profesion;
        $empleado->id_direccion=$request->id_direccion;
        $empleado->numero_documento_identificacion=$request->numero_documento_identificacion;
        $empleado->id_estado_civil=$request->id_estado_civil;
        $empleado->id_genero=$request->id_genero;
        $empleado->id_contacto_telefonico=$request->id_contacto_telefonico;
        $empleado->condicion='1';
        $empleado->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $empleado = Categoria::findOrFail($request->id);
        $empleado->condicion = '0';
        $empleado->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $empleado = Empleado::findOrFail($request->id);
        $empleado->condicion = '1';
        $empleado->save();
    }
    public function selectProfesion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $profesion = Profesion::where('condicion','=','1')
        ->select('codigo_profesion','titulo')->orderBy('titulo', 'asc')->get();
        return ['profesion' => $profesion];
    }
    public function selectDireccion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $direccion = Direccion::where('condicion','=','1')
        ->select('id_direccion','ubicacion')->orderBy('ubicacion', 'asc')->get();
        return ['ubicacion' => $ubicacion];
    }
    public function selectEstadoCivil(Request $request){
        if (!$request->ajax()) return redirect('/');
        $Estado = Estado::where('condicion','=','1')
        ->select('id_estado_civil','titulo')->orderBy('titulo', 'asc')->get();
        return ['estado' => $estado];
        
    }
    public function selectGenero(Request $request){
        if (!$request->ajax()) return redirect('/');
        $genero = Genero::where('condicion','=','1')
        ->select('id_genero','titulo')->orderBy('titulo', 'asc')->get();
        return ['genero' => $genero];
    }
    public function selectContacto(Request $request){
        if (!$request->ajax()) return redirect('/');
        $contacto = Contacto::where('condicion','=','1')
        ->select('id_contacto_telefonico','numero')->orderBy('numero', 'asc')->get();
        return ['contacto' => $contacto];
    }
}