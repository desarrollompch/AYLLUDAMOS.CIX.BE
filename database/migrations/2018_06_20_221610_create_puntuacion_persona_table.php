<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntuacionPersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntuacion_persona', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("numero_incidente")->nullable()->comment("Almacena el número de incidente");

            $table->integer("actividad_puntuacion_id")->unsigned()->comment("Llave foránea que almacena el identificador único de actividad puntuación");
            $table->foreign("actividad_puntuacion_id")->references("id")->on("actividad_puntuacion");

            $table->integer("persona_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id")->references("id")->on("persona");
            
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
        Schema::dropIfExists('puntuacion_persona');
    }
}
