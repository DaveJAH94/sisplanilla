<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoIngreso extends Model
{
    protected $table = 'tipos_ingresos';
    protected $primaryKey = 'codigo_tipo_ingreso';
    protected $fillable = ['nombre', 'procentaje_aplicado'];
}
