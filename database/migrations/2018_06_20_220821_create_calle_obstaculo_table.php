<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalleObstaculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calle_obstaculo', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una incidencia");
            $table->foreign("incidente_id")->references("id")->on("incidente");

            $table->integer("tipo_obstaculo_id")->unsigned()->comment("Llave foránea que almacena el identificador único de un tipo de obstáculo");
            $table->foreign("tipo_obstaculo_id")->references("id")->on("tipo_obstaculo");
            
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
        Schema::dropIfExists('calle_obstaculo');
    }
}
