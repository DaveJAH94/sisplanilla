<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_descuentos', function (Blueprint $table) {
            $table->char('codigo_tipo_descuento', 6);
            $table->string('nombre', 30);
            $table->float('porcentaje_aplicado');

            $table->primary('codigo_tipo_descuento');
            $table->timestamps();
        });

        DB::table('tipos_descuentos')->insert(array('codigo_tipo_descuento'=>'LY_ISS', 'nombre'=>'Isss', 'porcentaje_aplicado'=>'15'));
        DB::table('tipos_descuentos')->insert(array('codigo_tipo_descuento'=>'LY_AFP', 'nombre'=>'Afp', 'porcentaje_aplicado'=>'25'));
        DB::table('tipos_descuentos')->insert(array('codigo_tipo_descuento'=>'LY_REN', 'nombre'=>'Renta', 'porcentaje_aplicado'=>'25'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_descuentos');
    }
}
