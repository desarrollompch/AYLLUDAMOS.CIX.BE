<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('id');

            $table->string("ape_paterno", 40)->comment("Almacena el apellido paterno de una persona");
            $table->string("ape_materno", 40)->comment("Almacena el apellido materno de una persona");
            $table->string("nombres", 40)->comment("Almacena los nombres de una persona");
            $table->string("dni", 10)->comment("Almacena el documento de identidad de una persona");
            $table->string("telefono", 20)->nullable()->comment("Almacena el teléfono de una persona");
            $table->string("mail")->nullable()->comment("Almacena el email de una persona");
            $table->string("direccion")->nullable()->comment("Almacena la dirección de una persona");
            $table->enum('state', ['Activo', 'Inactivo'])->default('Activo');

            $table->integer("tipo_persona_id")->unsigned()->comment("Llave foránea que almacena el identificador único de tipo de persona");
            $table->foreign("tipo_persona_id")->references("id")->on("tipo_persona");

            $table->integer("nivel_ciudadano_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de nivel del ciudadano");
            $table->foreign("nivel_ciudadano_id")->references("id")->on("nivel_ciudadano");

            $table->integer("estado_persona_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de estado de persona");
            $table->foreign("estado_persona_id")->references("id")->on("estado_persona");
            
            $table->integer("urbanizacion_id")->unsigned()->comment("Llave foránea que almacena el identificador único de una urbanización");
            $table->foreign("urbanizacion_id")->references("id")->on("urbanizacion");

            $table->integer("rol_id")->unsigned()->nullable()->comment("Llave foránea que almacena el identificador único de un rol");
            $table->foreign("rol_id")->references("id")->on("rol");
            
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
        Schema::dropIfExists('persona');
    }
}
