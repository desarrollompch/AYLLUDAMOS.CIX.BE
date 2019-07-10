<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\TipoPersona;
use App\NivelCiudadano;
use App\EstadoPersona;
use App\Urbanizacion;
use App\Rol;
use App\Persona;
use App\User;

class PersonaModuleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }

    /**
     * @test
     */
    function test_crearNuevoCiudadano()
    {
        $this->withoutExceptionHandling();

        // tipo persona
        $tipo_persona = TipoPersona::find(3);

        // nivel ciudadano
        $nivel_ciudadano = NivelCiudadano::find(1);

        // estado persona
        $estado_persona = EstadoPersona::first();

        // urbanizaciom
        $urbanizacion = Urbanizacion::find(1);

        // rol ciudadano
        $rol_ciudadano = Rol::find(1);

        $persona = factory(Persona::class)->create([
            "tipo_persona_id"       => $tipo_persona->id,
            "nivel_ciudadano_id"    => $nivel_ciudadano->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion->id,
            "rol_id"                => $rol_ciudadano->id
        ]);

        User::create([
            "name"          => $persona->nombres,
            "email"         => $persona->mail,
            "password"      => bcrypt("123456"),
            "last_name"     => $persona->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona->id,
            "rol_id"        => $rol_ciudadano->id
        ]);

        $this->assertDatabaseHas("users", ["email" => $persona->mail]);
    }
}
