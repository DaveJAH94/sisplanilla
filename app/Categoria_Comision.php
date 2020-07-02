<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria_Comision extends Model
{
    protected $table = 'categorias_comision';
    protected $primaryKey = 'id_categoria_comision';
    protected $fillable = [
        'codigo_puesto', 'ventas_minimas', 'ventas_maximas','porcentaje'
    ];
    
    public function puesto(){
        return $this->belongTo('App\Puesto');
    }
}
