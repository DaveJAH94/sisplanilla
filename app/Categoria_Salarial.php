<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria_Salarial extends Model
{
    protected $table = 'categorias_salariales';
    protected $primaryKey = 'id_categoria_salarial';
    protected $fillable = [
        'id_catalogo_isr','salario_minimo','salario_maximo'
    ];
    
    public function catalogo_isr(){
        return $this->belongTo('App\Catalogo_Isr');
    }
    public function puestos(){
        return $this->hasMany('App\Puesto');
    }
}
