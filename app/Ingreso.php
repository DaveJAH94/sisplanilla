<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingresos';
    protected $primaryKey = 'id_ingreso';
    protected $fillable = ['codigo_empleado', 'codido_periodo', 'codigo_tipo_ingreso', 'titulo', 'cuantia', 'monto', 'descripciones'];
}
