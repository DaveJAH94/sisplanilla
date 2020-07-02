<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_ingresos', function (Blueprint $table) {
            $table->char('codigo_tipo_ingreso', 6);
            $table->string('nombre', 30);
            $table->float('porcentaje_aplicado');

            $table->primary('codigo_tipo_ingreso');
            
            $table->timestamps();
        });

        DB::table('tipos_ingresos')->insert(array('codigo_tipo_ingreso'=>'HE_DIA', 'nombre'=>'Horas extras diurnas', 'porcentaje_aplicado'=>'15'));
        DB::table('tipos_ingresos')->insert(array('codigo_tipo_ingreso'=>'HE_NOC', 'nombre'=>'Horas extras nocturnas', 'porcentaje_aplicado'=>'25'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_ingresos');
    }
}
