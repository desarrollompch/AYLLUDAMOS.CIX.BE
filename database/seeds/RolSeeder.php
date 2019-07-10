<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Rol::class)->create([
            "descripcion"       => "Ciudadano"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Plandet"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Alcalde Vecinal"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Comité de gestión"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Seguridad Ciudadana"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Policía"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Bomberos"
        ]);

        factory(Rol::class)->create([
            "descripcion"       => "Administrador"
        ]);


    }
}
