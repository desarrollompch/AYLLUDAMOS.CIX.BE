<?php

use Illuminate\Database\Seeder;
use App\FamiliarUbicacion;

class FamiliarUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Invocamos la factoría para poder crear la data de 5 registros de ubicacion de familiares

    	factory(FamiliarUbicacion::class,5)->create(); 
    }
}
