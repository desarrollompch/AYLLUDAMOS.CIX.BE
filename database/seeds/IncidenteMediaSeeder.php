<?php

use Illuminate\Database\Seeder;
use App\IncidenteMedia;

class IncidenteMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Invocamos la factoría para poder crear la data de 10 registro de archivos multimedia

    	factory(IncidenteMedia::class,1)->create(); 
    }
}
