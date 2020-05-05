<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {//modificar después a "usuarios"
            $table->integer('id_usuario')->unsigned();
            $table->foreign('id_usuario')->references('codigo_empleado')->on('empleados')->onDelete('cascade');

            $table->string('username',25)->unique();
            $table->string('password',20);
            $table->boolean('activo')->default(1);
            $table->datetime('first_session')->nullable();
            $table->datetime('last_session')->nullable();

            $table->integer('id_rol')->unsigned();
            $table->foreign('id_rol')->references('id_rol')->on('roles');
  
            $table->rememberToken();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
