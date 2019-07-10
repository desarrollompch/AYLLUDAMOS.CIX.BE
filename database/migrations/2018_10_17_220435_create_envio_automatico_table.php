<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioAutomaticoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envio_automatico', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('accion',10);
            $table->integer('tiempo');
            $table->string('telefono',9);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('envio_automatico');
    }
}
