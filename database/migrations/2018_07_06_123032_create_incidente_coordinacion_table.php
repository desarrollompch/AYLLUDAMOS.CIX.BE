<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenteCoordinacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidente_coordinacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('incidente_id')->unsigned();
            $table->integer('coordinacion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('incidente_id')->references('id')->on('incidente');
            $table->foreign('coordinacion_id')->references('id')->on('coordinacion');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('incidente_coordinacion');
    }
}
