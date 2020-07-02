<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasComisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_comision', function (Blueprint $table) {
            $table->increments('id_categoria_comision');
            $table->char('codigo_puesto',6);
            $table->float('ventas_minimas',8,2);
            $table->float('ventas_maximas',8,2);
            $table->float('porcentaje',8,2);
            $table->timestamps();

            $table->primary('id_categoria_comision');
            $table->foreign('codigo_puesto')->references('codigo_puesto')->on('puestos');
        });

        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'0',
        'ventas_maximas'=>'24.99','porcentaje'=>0));
        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'25',
        'ventas_maximas'=>'99.99','porcentaje'=>3));
        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'100',
        'ventas_maximas'=>'149.99','porcentaje'=>5));
        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'150',
        'ventas_maximas'=>'224.99','porcentaje'=>10));
        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'225',
        'ventas_maximas'=>'499.99','porcentaje'=>15));
        DB::table('categorias_comision')->insert(array('codigo_puesto'=>'VENT01', 'ventas_minimas'=>'500',
        'ventas_maximas'=>'999999','porcentaje'=>25));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias_comision');
    }
}
