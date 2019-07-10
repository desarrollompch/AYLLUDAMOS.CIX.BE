<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInundacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inundacion', function (Blueprint $table) {
            $table->increments('id');

            $table->string("tipo_inundacion", 40)->nullable()->comment("Almacena el tipo de inundación");
            
            $table->integer("incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una incidencia");
            $table->foreign("incidente_id")->references("id")->on("incidente");

            $table->integer("nivel_agua_id")->unsigned()->comment("Llave foránea que almacena el identificador único de un nivel de agua");
            $table->foreign("nivel_agua_id")->references("id")->on("nivel_agua");

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
        Schema::dropIfExists('inundacion');
    }
}
