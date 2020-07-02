<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->char('codigo_municipio',4);
            $table->char('codigo_departamento',5)->unsigned();
            $table->string('nombre',24);
            $table->primary('codigo_municipio');

            $table->foreign('codigo_departamento')->references('codigo_departamento')->on('departamentos');
        });

        DB::table('departamentos')->insert(array('codigo_departamento'=>'DDD01', 'nombre'=>'San Salvador'));
        DB::table('departamentos')->insert(array('codigo_departamento'=>'DDD02', 'nombre'=>'Santa Ana'));
        DB::table('municipios')->insert(array('codigo_municipio'=>'M001', 'codigo_departamento'=>'DDD01', 'nombre'=>'San Salvador'));
        DB::table('municipios')->insert(array('codigo_municipio'=>'M002', 'codigo_departamento'=>'DDD01', 'nombre'=>'San Marcos'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
