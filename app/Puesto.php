<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $table = 'puestos';
    protected $primaryKey= 'codigo_puesto';
    //ESTA LÍNEA SE UTILIZA PARA QUE NO INTENTE AUTOINCREMENTAR EL CÓDIGO DE LLAVE PRIMARIA
    public $incrementing = false;     protected $keyType = 'char';
    protected $fillable = [
        'id_categoria_salarial','codigo_unidad_organizativa','nombre','descripcion'
    ];
    
    public function categorias_comision(){
        return $this->hasMany('App\Categoria_Comision');
    }
    public function categoria_salarial(){
        return $this->belongTo('App\Categoria_Salarial');
    }
    /* //Este método, es para llegar al modelo Catalogo_Isr a través del modelo Categoria_Salarial
    public function trayendoIsr(){
        return $this->hasOneThrough('App\Catalogo_Isr','App\Categoria_Salarial','id_catalogo_isr','id_catalogo_isr');
    } */
    public function unidad_organizacional(){
        return $this->belongTo('App\Unidad_Organizacional');
    }
    /* public function contratos(){
        return $this->hasMany('App\Contrato');
    } */
}
