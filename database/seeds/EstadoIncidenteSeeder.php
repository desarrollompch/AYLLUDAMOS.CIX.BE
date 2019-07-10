<?php

use Illuminate\Database\Seeder;
use App\EstadoIncidente;

class EstadoIncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoIncidente::create([
            'descripcion'=>'Sin Confirmar'
        ]);

        EstadoIncidente::create([
            'descripcion'=>'Confirmado'
        ]);

        EstadoIncidente::create([
            'descripcion'=>'Falso Positivo'
        ]);

        EstadoIncidente::create([
            'descripcion'=>'Atendido'
        ]);
    }
}
