<?php

use Illuminate\Database\Seeder;
use App\Directorio;

class DirectorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Invocamos la factoría para poder crear la data de 20 Directorios

    	factory(Directorio::class,20)->create();    

    }
}
