<?php

use Illuminate\Database\Seeder;
use App\Familiar;

class FamiliarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Invocamos la factoría para poder crear la data de 12 familiares

    	factory(Familiar::class,12)->create(); 
    }
}
