<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class UpdateFechaIncidente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incidente', function(Blueprint $table) 
        {
             $table->datetime("fecha")->default(Carbon::now())->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('incidente', function(Blueprint $table) 
        {
             $table->date("fecha")->change();
        });
    }
}
