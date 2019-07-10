<?php

use Illuminate\Database\Seeder;
use App\EstadoPersona;

class EstadoPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(EstadoPersona::class)->create([
            "descripcion"       => "ACTIVO"
        ]);

        factory(EstadoPersona::class)->create([
            "descripcion"       => "INACTIVO"
        ]);
    }
}
