<?php

use Illuminate\Database\Seeder;
use App\NivelAgua;

class NivelAguaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = ['Pies', 'Rodillas', 'Cintura', 'Torax', 'Cabeza'];

        foreach ($data as $datum){
            NivelAgua::create([
                'descripcion'=>$datum
            ]);
        }



    }
}
