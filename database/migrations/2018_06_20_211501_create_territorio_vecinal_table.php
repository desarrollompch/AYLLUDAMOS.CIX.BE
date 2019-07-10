<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritorioVecinalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territorio_vecinal', function (Blueprint $table) {
            $table->increments('id');
            $table->text("descripcion")->comment("Almacena la descripción de un territorio vecinal");
            $table->text('coordenadas');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('min_latitude');
            $table->string('max_latitude');
            $table->string('max_longitude');
            $table->string('min_longitude');
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
        Schema::dropIfExists('territorio_vecinal');
    }
}
