<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolylinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polylines', function (Blueprint $table) {
            $table->increments('id');

            $table->text('coordinates');

            $table->integer("incidente_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una incidencia");
            $table->foreign("incidente_id")->references("id")->on("incidente");

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
        Schema::dropIfExists('polylines');
    }
}
