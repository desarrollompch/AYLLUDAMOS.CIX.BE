<?php

use Illuminate\Database\Seeder;
use App\AtencionIncidente;

class AtencionIncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Invocamos la factoría para poder crear la data de 5 registro de atenciones

    	factory(AtencionIncidente::class,5)->create(); 
    }
}
