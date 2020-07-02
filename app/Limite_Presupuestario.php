<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Limite_Presupuestario extends Model
{
    protected $table = 'limites_presupuestarios';
    protected $primaryKey = 'id_limite_presupuestario';
    protected $fillable = [
        'codigo_unidad_organizativa','limite_minimo','limite_maxiomo'
    ];

    public function unidad_organizacional(){
        return $this->belongTo('App\Unidad_Organizacional');
    }
}
