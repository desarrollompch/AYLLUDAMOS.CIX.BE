<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoObstaculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_obstaculo', function (Blueprint $table) {
            $table->increments('id');
            $table->string("descripcion", 40)->comment("Almacena la descripción de un tipo de obstáculo");
            $table->string("imagen")->nullable()->comment("Almacena la imagen de un tipo de obstáculo");
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
        Schema::dropIfExists('tipo_obstaculo');
    }
}
