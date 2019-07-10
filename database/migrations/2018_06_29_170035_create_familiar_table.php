<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamiliarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familiar', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nombres", 100)->comment("Almacena los nombres de un familiar");
            $table->string("telefono", 20)->nullable()->comment("Almacena el teléfono de un familiar");
            $table->integer("persona_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id")->references("id")->on("persona");
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
        Schema::dropIfExists('familiar');
    }
}
