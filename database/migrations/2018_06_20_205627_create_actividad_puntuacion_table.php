<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadPuntuacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_puntuacion', function (Blueprint $table) {
            $table->increments('id');

            //$table->enum('tipo', ['Válido', 'Inválido']);

            $table->string("descripcion", 40)->comment("Almacena la descripción de una actividad puntuación");
            $table->integer("puntaje")->comment("Almacena el puntaje de una actividad puntuación");

            $table->integer("estado_incidente_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de estado incidente");
            $table->foreign("estado_incidente_id")->references("id")->on("estado_incidente");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividad_puntuacion');
    }
}
