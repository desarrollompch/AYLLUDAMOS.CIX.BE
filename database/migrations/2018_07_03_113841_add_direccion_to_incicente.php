<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDireccionToIncicente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incidente', function($table) 
        {
             $table->string("direccion")->nullable()->comment("Almacena la dirección de un incidente")->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incidente', function($table) {
            $table->dropColumn('direccion');
        });
    }
}
