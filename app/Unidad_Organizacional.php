<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad_Organizacional extends Model
{
    protected $table = 'unidades_organizacionales';
    protected $primaryKey= 'codigo_unidad_organizativa';
    //ESTA LÍNEA SE UTILIZA PARA QUE NO INTENTE AUTOINCREMENTAR EL CÓDIGO DE LLAVE PRIMARIA
    public $incrementing = false;     protected $keyType = 'char';
    protected $fillable = [
        'codigo_unidad_superior','nombre','descripcion'
    ];

    public function unidades_organizacionales(){
        return $this->hasMany('App\Unidad_Organizacional','codigo_unidad_organizativa');
    }
    public function unidad_organizacional(){
        return $this->belongTo('App\Unidad_Organizacional','codigo_unidad_superior');
    }
    public function puestos(){
        return $this->hasMany('App\Puesto','codigo_unidad_organizativa');
    }
    public function limites_presupuestarios(){
        return $this->hasMany('App\Limite_Presupuestario');
    }
    /* public function centros_costos(){
        return $this->hasMany('App\Centro_Costo');
    } */

}
