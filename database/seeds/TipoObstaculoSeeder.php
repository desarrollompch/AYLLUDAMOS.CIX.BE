<?php

use Illuminate\Database\Seeder;
use App\TipoObstaculo;

class TipoObstaculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = ['Arbol Caído', 'Poste en la Calle', 'Sacos en la calle'];

        foreach ($data as $datum){
            TipoObstaculo::create([
                'descripcion'=>$datum
            ]);
        }



    }
}
