<?php

use Illuminate\Database\Seeder;
use App\TerritorioVecinal;
use App\Urbanizacion;

class UrbanizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $territorio_vecinal_1 = TerritorioVecinal::find(1);

        $territorio_vecinal_3 = TerritorioVecinal::find(3);

        $territorio_vecinal_4 = TerritorioVecinal::find(4);

        factory(Urbanizacion::class)->create([
            "territorio_vecinal_id"     => $territorio_vecinal_1->id
        ]);

        factory(Urbanizacion::class)->create([
            "territorio_vecinal_id"     => $territorio_vecinal_3->id
        ]);

        factory(Urbanizacion::class)->create([
            "territorio_vecinal_id"     => $territorio_vecinal_4->id
        ]);
    }
}
