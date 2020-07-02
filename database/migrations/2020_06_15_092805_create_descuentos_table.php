<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descuentos', function (Blueprint $table) {
            $table->increments('id_descuento');
            $table->char('codigo_periodo', 8);
            $table->char('codigo_tipo_descuento', 6);
            $table->char('codigo_empleado',6);
            $table->string('titulo', 30);
            $table->float('monto');
            $table->string('descripcion', 100);

            $table->primary('id_descuento');
            $table->foreign('codigo_periodo')->references('codigo_periodo')->on('periodos');
            $table->foreign('codigo_tipo_descuento')->references('codigo_tipo_descuento')->on('tipos_descuentos');
            $table->foreign('codigo_empleado')->references('codigo_empleado')->on('empleados');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descuentos');
    }
}
