<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id_ingreso');
            $table->char('codigo_periodo', 8);
            $table->char('codigo_tipo_ingreso', 6);
            $table->char('codigo_empleado',6);
            $table->string('titulo', 30);
            $table->integer('cuantia');
            $table->float('monto');
            $table->string('descripciones', 100);

            $table->primary('id_ingreso');
            $table->foreign('codigo_periodo')->references('codigo_periodo')->on('periodos');
            $table->foreign('codigo_tipo_ingreso')->references('codigo_tipo_ingreso')->on('tipos_ingresos');
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
        Schema::dropIfExists('ingresos');
    }
}