<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimitesPresupuestariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limites_presupuestarios', function (Blueprint $table) {
            $table->Increments('id_limite_presupuestario');
            $table->char('codigo_unidad_organizativa',4);
            $table->float('limite_minimo',10,2);
            $table->float('limite_maximo',10,2);
            $table->timestamps();

            $table->primary('id_limite_presupuestario');
            $table->foreign('codigo_unidad_organizativa')->references('codigo_unidad_organizativa')->on('unidades_organizacionales');
        });

        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'ADMI', 'limite_minimo'=>'8000000',
        'limite_maximo'=>'8500000'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'COME', 'limite_minimo'=>'200000',
        'limite_maximo'=>'400000'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'RRHH', 'limite_minimo'=>'170000',
        'limite_maximo'=>'170900'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'INFO', 'limite_minimo'=>'100000',
        'limite_maximo'=>'115000'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'IN01', 'limite_minimo'=>'10000',
        'limite_maximo'=>'20000'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'IN02', 'limite_minimo'=>'15000',
        'limite_maximo'=>'25000'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'ABUE', 'limite_minimo'=>'100',
        'limite_maximo'=>'777'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'PADR', 'limite_minimo'=>'100',
        'limite_maximo'=>'777'));
        DB::table('limites_presupuestarios')->insert(array('codigo_unidad_organizativa'=>'HIJO', 'limite_minimo'=>'100',
        'limite_maximo'=>'777'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limites_presupuestarios');
    }
}
