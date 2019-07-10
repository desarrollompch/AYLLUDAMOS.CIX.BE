<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidente', function (Blueprint $table) {
            $table->increments('id');
            $table->date("fecha")->comment("Almacena la fecha de registro de un incidente");
            $table->text("descripcion")->comment("Almacena la descripción de un incidente");
            
            // $table->integer("persona_id_validador")->comment("Almacena el identificador único de una persona que valida el incidente");

            $table->string('latitud');
            $table->string('longitud');
            $table->string('imagen')->nullable();

            $table->integer("urbanizacion_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una urbanizacion");
            $table->foreign("urbanizacion_id")->references("id")->on("urbanizacion");

            $table->integer("persona_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id")->references("id")->on("persona");

            $table->integer("persona_id_validador")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id_validador")->references("id")->on("persona");

            $table->integer("estado_incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de un estado incidente");
            $table->foreign("estado_incidente_id")->references("id")->on("estado_incidente");

            $table->integer("tipo_incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de un estado incidente");
            $table->foreign("tipo_incidente_id")->references("id")->on("tipo_incidente"); 
            
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
        Schema::dropIfExists('incidente');
    }
}
