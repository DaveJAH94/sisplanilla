<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->char('codigo_periodo', 8);
            $table->date('fecha_inicial');
            $table->date('fecha_final');
            $table->integer('cantidad_semanas');
            $table->boolean('activo');
            $table->integer('anio_calendario');

            $table->primary('codigo_periodo');
            
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
        Schema::dropIfExists('periodos');
    }
}
