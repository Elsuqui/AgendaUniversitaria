<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMateriasDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materias_docente', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->unsignedInteger('id_materia');
            $table->unsignedInteger('id_usuario');
            $table->enum('estado', ["ACTIVO", "INACTIVO"])->default("ACTIVO");
            $table->unsignedInteger('id_usuario_creacion');
            $table->unsignedInteger('id_usuario_edicion')->nullable();
            $table->timestamps();
        });

        Schema::table('materias_docente', function(Blueprint $table) {
            $table->foreign('id_materia')->references('id')->on('materias');
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materias_docente', function(Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropForeign(['id_materia']);
        });

        Schema::dropIfExists('materias_docente');
    }
}
