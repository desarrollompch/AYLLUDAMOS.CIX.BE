<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()   
    {
        Schema::create('directorio', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nombre", 40)->comment("Almacena los nombres de los directorios telefonicos");
            $table->string("direccion")->nullable()->comment("Almacena la dirección de un directorio telefonico");
            $table->string("telefono", 20)->nullable()->comment("Almacena el teléfono de un directorio telefonico");
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
        Schema::dropIfExists('directorio');
    }
}
