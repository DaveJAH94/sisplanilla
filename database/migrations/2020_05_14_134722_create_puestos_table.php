<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puestos', function (Blueprint $table) {
            $table->char('codigo_puesto',6);
            $table->string('nombre',25);
            $table->string('descripcion',150)->nullable();
            $table->integer('id_categoria_salarial');
            $table->char('codigo_unidad_organizativa',4);
            $table->timestamps();

            $table->primary('codigo_puesto');
            $table->foreign('id_categoria_salarial')->references('id_categoria_salarial')->on('categorias_salariales');
            $table->foreign('codigo_unidad_organizativa')->references('codigo_unidad_organizativa')->on('unidades_organizacionales');
        });

        DB::table('puestos')->insert(array('codigo_puesto'=>'DIR001', 'nombre'=>'DIRECTOR GENERAL',
        'descripcion'=>'DUEÑO DE LA EMPRESA','id_categoria_salarial'=>3,'codigo_unidad_organizativa'=>'ADMI'));
        DB::table('puestos')->insert(array('codigo_puesto'=>'VENT01', 'nombre'=>'COMERCIANTE',
        'descripcion'=>'ENCARGADOS DE LAS VENTAS DE PRODUCTOS','id_categoria_salarial'=>4,'codigo_unidad_organizativa'=>'COME'));
        DB::table('puestos')->insert(array('codigo_puesto'=>'JEF001', 'nombre'=>'JEFE DE RECURSOS HUMANOS',
        'descripcion'=>'JEFE DE UNIDAD DE RRHH','id_categoria_salarial'=>2,'codigo_unidad_organizativa'=>'RRHH'));
        DB::table('puestos')->insert(array('codigo_puesto'=>'SUB001', 'nombre'=>'SECRETARIA',
        'descripcion'=>'SECRETARIA DE LA UNIDAD DE RRHH','id_categoria_salarial'=>1,'codigo_unidad_organizativa'=>'RRHH'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('puestos');
    }
}
