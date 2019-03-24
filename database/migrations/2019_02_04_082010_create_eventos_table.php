<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->unsignedInteger('id_materia_docente');
            $table->string('titulo');
            $table->string('descripcion')->nullable();
            $table->string('aula');
            $table->date('fecha');
            $table->date('fecha_fin');
            $table->time('hora');
            $table->time('hora_fin');
            $table->string('estado_control')->default("PENDIENTE");
            $table->enum('estado', ["ACTIVO", "INACTIVO"])->default("ACTIVO");
            $table->unsignedInteger('importancia');
            $table->unsignedInteger('id_usuario_creacion');
            $table->unsignedInteger('id_usuario_edicion')->nullable();
            $table->timestamps();
        });

        Schema::table('eventos', function(Blueprint $table){
            $table->foreign('id_materia_docente')->references('id')->on('materias_docente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos', function(Blueprint $table) {
            $table->dropForeign(['id_materia_docente']);
        });

        Schema::dropIfExists('eventos');
    }
}
