<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNivelCiudadanoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nivel_ciudadano', function (Blueprint $table) {
            $table->increments('id');
            $table->string("icono", 100)->nullable()->comment("Almacena el ícono representativo de la puntuación");
            $table->string("descripcion", 40)->comment("Almacena la descripción para un nuevo nivel ciudadano");
            $table->integer("total_minimo")->unsigned()->comment("Almacena el puntaje mínimo de un nivel");
            $table->integer("total_maximo")->unsigned()->comment("Almacena el puntaje máximo de un nivel");

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
        Schema::dropIfExists('nivel_ciudadano');
    }
}
