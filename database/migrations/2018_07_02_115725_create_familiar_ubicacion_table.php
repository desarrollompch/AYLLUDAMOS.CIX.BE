<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamiliarUbicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familiar_ubicacion', function (Blueprint $table) {
            $table->increments('id')->comment("Almacena el id del registro de una ubicación");

            $table->integer("familiar_id")->unsigned()->comment("Llave foránea que almacena el identificador único de un familiar");
            $table->foreign("familiar_id")->references("id")->on("familiar");

            $table->timestamp("fecha")->comment("Almacena la fecha del registro de una ubicacion")->useCurrent = true; 
            //$table->timestamp('time')->useCurrent = true;           
            $table->string('latitude')->comment("Almacena la latitude del registro de una ubicacion");;
            $table->string('longitude')->comment("Almacena la longitude del registro de una ubicacion");;
            $table->string("descripcion", 250)->comment("Almacena la descripción de una ubicación");
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
        Schema::dropIfExists('familiar_ubicacion');
    }
}
