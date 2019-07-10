<?php

use Illuminate\Database\Seeder;
use App\TipoPersona;
use App\NivelCiudadano;
use App\EstadoPersona;
use App\Urbanizacion;
use App\Rol;
use App\Persona;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tipo_ciudadano = TipoPersona::find(2);

        $tipo_defensa_civil = TipoPersona::find(6);

        $tipo_administrador = TipoPersona::find(1);

        $tipo_policia = TipoPersona::find(3);

        $tipo_alcalde = TipoPersona::find(4);

        $tipo_comite_gestion = TipoPersona::find(5);

        $nivel_principiante = NivelCiudadano::find(1);

        $nivel_bronce = NivelCiudadano::find(2);

        $nivel_plata = NivelCiudadano::find(3);

        $estado_persona = EstadoPersona::first();

        $urbanizacion_1 = Urbanizacion::find(1);

        $urbanizacion_2 = Urbanizacion::find(2);

        // $urbanizacion_3 = Urbanizacion::find(3);

        $rol_ciudadano = Rol::find(1);

        $rol_plandet = Rol::find(3);

        $rol_alcalde_vecinal = Rol::find(4);

        $rol_comite_gestion = Rol::find(5) ;  

        $rol_administrador = Rol::find(8) ;      

        factory(Persona::class)->create([
            "nombres"               => "Administrador",
            "mail"                  => "administrador@gmail.com",
            "tipo_persona_id"       => $tipo_administrador->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_administrador->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "juan.racchumi.nima@gmail.com",
            "tipo_persona_id"       => $tipo_ciudadano->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_ciudadano->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "asistemas@adylconsulting.com",
            "tipo_persona_id"       => $tipo_ciudadano->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_ciudadano->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "juancarlos@email.com",
            "tipo_persona_id"       => $tipo_ciudadano->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_ciudadano->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "plandet@gmail.com",
            "tipo_persona_id"       => $tipo_defensa_civil->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_plandet->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "alcalde@gmail.com",
            "tipo_persona_id"       => $tipo_alcalde->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_2->id,
            "rol_id"                => $rol_alcalde_vecinal->id
        ]);

        factory(Persona::class)->create([
            "mail"                  => "comite@gmail.com",
            "tipo_persona_id"       => $tipo_comite_gestion->id,
            "nivel_ciudadano_id"    => $nivel_principiante->id,
            "estado_persona_id"     => $estado_persona->id,
            "urbanizacion_id"       => $urbanizacion_1->id,
            "rol_id"                => $rol_comite_gestion->id
        ]);

        // factory(Persona::class, 5)->create([
        //     "tipo_persona_id"       => $tipo_administrador->id,
        //     "nivel_ciudadano_id"    => $nivel_plata->id,
        //     "estado_persona_id"     => $estado_persona->id,
        //     "urbanizacion_id"       => $urbanizacion_2->id,
        //     "rol_id"                => $rol_alcalde_vecinal->id
        // ]);


    }
}
