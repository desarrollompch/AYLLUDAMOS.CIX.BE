<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenteMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidente_media', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una incidencia");
            $table->foreign("incidente_id")->references("id")->on("incidente");

            $table->enum('tipo_media', ['Imagen', 'Video'])->comment("Almacena el tipo de evidencia imagen o video");
            $table->string("incidente_media_name", 250)->comment("Almacena los nombres de una imagen o video");
            $table->string("incidente_media_url", 250)->comment("Almacena la ruta de una imagen o video");

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
        Schema::dropIfExists('incidente_media');
    }
}
