<?php

use Illuminate\Database\Seeder;
use App\TipoPersona;

class TipoPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TipoPersona::class)->create([
            "descripcion"   => "Administrador"
        ]);

        factory(TipoPersona::class)->create([
            "descripcion"   => "Ciudadano"
        ]);

        factory(TipoPersona::class)->create([
            "descripcion"   => "Policía"
        ]);

        factory(TipoPersona::class)->create([
            "descripcion"   => "Alcalde Vecinal"
        ]);

        factory(TipoPersona::class)->create([
            "descripcion"   => "Comité Gestión Territorio Vecinal"
        ]);

        factory(TipoPersona::class)->create([
            "descripcion"   => "Personal Plandet"
        ]);
    }
}
