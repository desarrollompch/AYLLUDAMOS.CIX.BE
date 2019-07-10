<?php

use Illuminate\Database\Seeder;
use App\ActividadPuntuacion;
use App\EstadoIncidente;

class ActividadPuntuacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$estado_incidente_1 = EstadoIncidente::where("descripcion", "Sin Confirmar")->first();

        $estado_incidente_2 = EstadoIncidente::where("descripcion", "Confirmado")->first();

        $estado_incidente_3 = EstadoIncidente::where("descripcion", "Falso Positivo")->first();

        ActividadPuntuacion::create([
            'descripcion'  			=>"Registro de Usuario",
            'puntaje'  				=>0,
            "estado_incidente_id"  	=> $estado_incidente_1->id
        ]);

        ActividadPuntuacion::create([
            'descripcion'  			=>"Registro de Usuario",
            'puntaje'  				=>10,
            "estado_incidente_id"  	=> $estado_incidente_2->id
        ]);

        ActividadPuntuacion::create([
            'descripcion'  			=>"Incidente Reportado",
            'puntaje'  				=>40,
            "estado_incidente_id"  	=> $estado_incidente_2->id
        ]);

        ActividadPuntuacion::create([
            'descripcion'  			=>"Incidente Reportado",
            'puntaje'  				=>0,
            "estado_incidente_id"  	=> $estado_incidente_3->id
        ]);
    }
}
