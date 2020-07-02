<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $table = 'descuentos';
    protected $primaryKey = 'id_descuento';
    protected $fillable = ['codigo_empleado', 'codido_periodo', 'codigo_tipo_descuento', 'titulo', 'monto', 'descripciones'];
}
