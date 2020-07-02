<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDescuento extends Model
{
    protected $table = 'tipos_descuentos';
    protected $primaryKey = 'codigo_tipo_descuento';
    protected $fillable = ['nombre', 'procentaje_aplicado'];
}
