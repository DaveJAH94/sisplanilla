<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoIsrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_isr', function (Blueprint $table) {
            $table->increments('id_catalogo_isr');
            $table->float('limite_inferior',8,2);
            $table->float('limite_superior',8,2);
            $table->float('porcentaje',8,2);
            $table->float('cuota_fija',8,2);
            $table->timestamps();
        });

        /**
        * Datos basados en la tabla del Art. 37 de la ley de Impuesto sobre la Renta [https://www.mh.gob.sv/pmh/es/Temas/Declaracion_sugerida_de_la_Renta.html]
        * Datos presentados de forma ANUAL
        * Entiendase en el campo porcentaje de esta manera: "0.10" equivale a "10%"
        ** Dave **
        */
        DB::table('catalogo_isr')->insert(array('id_catalogo_isr'=>'1', 'limite_inferior'=>'0.01', 'limite_superior'=>'4064.00', 'porcentaje'=>'0', 'cuota_fija'=>'0'));
        DB::table('catalogo_isr')->insert(array('id_catalogo_isr'=>'2', 'limite_inferior'=>'4064.01', 'limite_superior'=>'9142.86', 'porcentaje'=>'10', 'cuota_fija'=>'212.12'));
        DB::table('catalogo_isr')->insert(array('id_catalogo_isr'=>'3', 'limite_inferior'=>'9142.87', 'limite_superior'=>'22857.14', 'porcentaje'=>'20', 'cuota_fija'=>'720'));
        DB::table('catalogo_isr')->insert(array('id_catalogo_isr'=>'4', 'limite_inferior'=>'22857.15', 'limite_superior'=>'999999', 'porcentaje'=>'30', 'cuota_fija'=>'3462.86'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_isr');
    }
}
