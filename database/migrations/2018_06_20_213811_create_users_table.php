<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->boolean('admin')->default(0);
            $table->enum('state', ['Activo', 'Inactivo'])->default('Inactivo');
            $table->string('email', 128)->unique();
            $table->string('password');

            $table->integer("persona_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de una persona");
            $table->foreign("persona_id")->references("id")->on("persona");

            $table->integer("rol_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de un rol");
            $table->foreign("rol_id")->references("id")->on("rol");

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
