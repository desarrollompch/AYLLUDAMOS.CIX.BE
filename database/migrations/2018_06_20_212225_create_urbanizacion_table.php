<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrbanizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbanizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("territorio_vecinal_id")->unsigned()->comment("Llave foránea que almacena un identificador de territorio vecinal");
            $table->foreign("territorio_vecinal_id")->references("id")->on("territorio_vecinal");
            $table->text('coordenadas');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('min_latitude');
            $table->string('max_latitude');
            $table->string('max_longitude');
            $table->string('min_longitude');
            $table->string("descripcion", 80)->comment("Almacena la descripción de una urbanización");
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
        Schema::dropIfExists('urbanizacion');
    }
}
