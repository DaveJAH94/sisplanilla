<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesOrganizacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_organizacionales', function (Blueprint $table) {
            $table->char('codigo_unidad_organizativa',4);
            $table->char('codigo_unidad_superior',4)->nullable();
            $table->string('nombre',30)->unique();
            $table->string('descripcion',150);
            $table->timestamps();

            $table->primary('codigo_unidad_organizativa');
            $table->foreign('codigo_unidad_superior')->references('codigo_unidad_organizativa')->on('unidades_organizacionales');

        });

        DB::unprepared('
        /*TRIGGER: Asignar Nueva Unidad Superior a Unidades Organizativas después de haber eliminado su Unidad Superior*/
        CREATE OR REPLACE TRIGGER tg_modificar_unidad_superior 
        BEFORE DELETE ON unidades_organizacionales FOR EACH row

        DECLARE
        v_cod_unidad UNIDADES_ORGANIZACIONALES.CODIGO_UNIDAD_ORGANIZATIVA%type;
        v_cod_superior UNIDADES_ORGANIZACIONALES.CODIGO_UNIDAD_SUPERIOR%type;

        BEGIN    
            select CODIGO_UNIDAD_ORGANIZATIVA,CODIGO_UNIDAD_SUPERIOR
            into v_cod_unidad, v_cod_superior
            from unidades_organizacionales
            where CODIGO_UNIDAD_ORGANIZATIVA = :old.CODIGO_UNIDAD_ORGANIZATIVA;
        
                --Actualizaciones antes de eliminar
                UPDATE unidades_organizacionales
                SET codigo_unidad_superior = v_cod_superior
                WHERE codigo_unidad_superior = v_cod_unidad;
        END;

        /*TRIGGER: Asignar Nueva Unidad Superior a Unidades Organizativas después de haber eliminado su Unidad Superior/
        CREATE OR REPLACE TRIGGER tg_modificar_unidad_superior 
        BEFORE DELETE ON unidades_organizacionales FOR EACH row

        BEGIN    
            --Actualizaciones antes de eliminar
            UPDATE unidades_organizacionales
            SET codigo_unidad_superior = :old.codigo_unidad_superior
            WHERE codigo_unidad_superior = :old.codigo_unidad_organizativa;
        END;

        create or replace TRIGGER tg_modificar_unidad_superior
        BEFORE DELETE ON unidades_organizacionales FOR EACH row*/

        ');

        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'ADMI', 'nombre'=>'ADMINISTRACIÓN Y GERENCIA',
        'descripcion'=>'ES LA UNIDAD ORGANIZATIVA PRINCIPAL DE LA EMPRESA'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'RRHH', 'nombre'=>'DIRECCIÓN DE RECURSOS HUMANOS',
        'descripcion'=>'ES LA UNIDAD QUE SE ENCARGA DEL TALENTO HUMANO', 'codigo_unidad_superior'=>'ADMI'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'COME', 'nombre'=>'DIRECCIÓN COMERCIAL',
        'descripcion'=>'ES LA UNIDAD ENCARGADA DE PUBLICIDAD Y VENTAS', 'codigo_unidad_superior'=>'ADMI'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'INFO', 'nombre'=>'DIRECCIÓN DE INFORMÁTICA',
        'descripcion'=>'UNIDAD ENCARGADA DE TODO LO DE INFORMÁTICA', 'codigo_unidad_superior'=>'ADMI'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'IN01', 'nombre'=>'UNIDAD DE COMUNICACIONES',
        'descripcion'=>'UNIDAD ENCARGADO DE ESTRUCTURAS DE RED Y COMUNICACIÓN', 'codigo_unidad_superior'=>'INFO'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'IN02', 'nombre'=>'UNIDAD DE DESARROLLO',
        'descripcion'=>'UNIDAD ENCARGADA DE DESARROLLAR SOFTWARE', 'codigo_unidad_superior'=>'INFO'));
        /* DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'ABUE', 'nombre'=>'ABUELO',
        'descripcion'=>'ESTE ES EL ABUELO', 'codigo_unidad_superior'=>'ADMI'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'PADR', 'nombre'=>'PADRE',
        'descripcion'=>'ESTE ES EL PADRE', 'codigo_unidad_superior'=>'ABUE'));
        DB::table('unidades_organizacionales')->insert(array('codigo_unidad_organizativa'=>'HIJO', 'nombre'=>'HIJO',
        'descripcion'=>'ESTE ES EL HIJO', 'codigo_unidad_superior'=>'PADR')); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades_organizacionales');
    }
}
