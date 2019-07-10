<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotonPanicoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boton_panico_user', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->string('accion',10);
          $table->datetime('fecha'); 
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
        Schema::dropIfExists('boton_panico_user');
    }
}
