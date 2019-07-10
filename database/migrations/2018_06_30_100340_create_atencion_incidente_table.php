<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtencionIncidenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_incidente', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("persona_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id")->references("id")->on("persona");

            $table->integer("incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una incidencia");
            $table->foreign("incidente_id")->references("id")->on("incidente");

            $table->dateTime("fecha")->comment("Almacena la fecha de registro de un incidente");
            $table->text("descripcion")->comment("Almacena la descripción de la atención a un incidente");

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
        Schema::dropIfExists('atencion_incidente');
    }
}
