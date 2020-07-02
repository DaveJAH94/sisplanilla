<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasSalarialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_salariales', function (Blueprint $table) {
            $table->increments('id_categoria_salarial');
            $table->float('salario_minimo',8,2);
            $table->float('salario_maximo',8,2);
            $table->integer('id_catalogo_isr')->unsigned();
            $table->timestamps();

            $table->primary('id_categoria_salarial');
            $table->foreign('id_catalogo_isr')->references('id_catalogo_isr')->on('catalogo_isr');
        });

        DB::table('categorias_salariales')->insert(array('salario_minimo'=>'300', 'salario_maximo'=>'325',
        'id_catalogo_isr'=>'1'));
        DB::table('categorias_salariales')->insert(array('salario_minimo'=>'3500', 'salario_maximo'=>'4000',
        'id_catalogo_isr'=>'4'));
        DB::table('categorias_salariales')->insert(array('salario_minimo'=>'9000', 'salario_maximo'=>'11000',
        'id_catalogo_isr'=>'4'));
        DB::table('categorias_salariales')->insert(array('salario_minimo'=>'400', 'salario_maximo'=>'500',
        'id_catalogo_isr'=>'2'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias_salariales');
    }
}
