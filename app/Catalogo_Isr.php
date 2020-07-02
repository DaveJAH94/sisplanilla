<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo_Isr extends Model
{
    protected $table = 'catalogo_isr';
    protected $primaryKey = 'id_catalogo_isr';
    protected $fillable = ['limite_inferior','limite_superior','porcentaje','cuota_fija'];
    
    public function categorias_salariales(){
        return $this->hasMany('App\Categoria_Salarial');
    }
}
