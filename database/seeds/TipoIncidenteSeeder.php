<?php

use Illuminate\Database\Seeder;
use App\TipoIncidente;

class TipoIncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoIncidente::create([
            'descripcion'=>'Inundación'
        ]);

        TipoIncidente::create([
            'descripcion'=>'Obtaculización de Calles'
        ]);

    }
}
