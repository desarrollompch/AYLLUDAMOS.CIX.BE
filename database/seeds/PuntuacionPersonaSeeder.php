<?php

use Illuminate\Database\Seeder;
use App\ActividadPuntuacion;
use App\PuntuacionPersona;
use App\Persona;

class PuntuacionPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $persona = Persona::where("mail", "asistemas@adylconsulting.com")->first();
        $actividad_puntuacion = ActividadPuntuacion::where("puntaje","40")->first();
        $actividad_puntuacion2 = ActividadPuntuacion::where("puntaje","10")->first();


        PuntuacionPersona::create([
            'numero_incidente'  		=>"1",
            'actividad_puntuacion_id'  	=>$actividad_puntuacion->id,
            "persona_id"  				=>$persona->id
        ]);

        PuntuacionPersona::create([
            'numero_incidente'          =>"2",
            'actividad_puntuacion_id'   =>$actividad_puntuacion->id,
            "persona_id"                =>$persona->id
        ]);

        PuntuacionPersona::create([
            'numero_incidente'          =>"3",
            'actividad_puntuacion_id'   =>$actividad_puntuacion2->id,
            "persona_id"                =>$persona->id
        ]);



    }
}
