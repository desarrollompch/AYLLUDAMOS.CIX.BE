<?php

use Illuminate\Database\Seeder;
use App\NivelCiudadano;

class NivelCiudadanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        NivelCiudadano::create([
            'total_minimo'  =>0,
            'total_maximo'  =>100,
            "descripcion"   => "Bronce",
            "icono"         => "bronce"
        ]);

        NivelCiudadano::create([
            'total_minimo'=>101,
            'total_maximo'=>200,
            "descripcion"       => "Plata",
            "icono"       => "plata"
        ]);

        NivelCiudadano::create([
            'total_minimo'=>201,
            'total_maximo'=>300,
            "descripcion"       => "Oro",
            "icono"       => "oro"
        ]);

        NivelCiudadano::create([
            'total_minimo'=>301,
            'total_maximo'=>400,
            "descripcion"       => "Platino",
            "icono"       => "platino"
        ]);
    }
}
