<?php

use Illuminate\Database\Seeder;
use App\TerritorioVecinal;

class TerritorioVecinalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(TerritorioVecinal::class, 10)->create();
        factory(TerritorioVecinal::class)->create([
            "coordenadas" => "-8.10999,-79.02103;-8.10768,-79.02128;-8.1068,-79.02118;-8.10665,-79.01817;-8.10971,-79.01808", 
            "latitude" => "-8.1090524", 
            "longitude" => "-79.0215336", 
            "min_latitude" => "-8.10999", 
            "max_latitude" => "-8.10665", 
            "max_longitude" => "-79.01808", 
            "min_longitude" => "-79.02128"]
        );

        factory(TerritorioVecinal::class)->create([
            "coordenadas" => "-8.10999,-79.02103;-8.10768,-79.02128;-8.1068,-79.02118;-8.10665,-79.01817;-8.10971,-79.01808", 
            "latitude" => "-8.1090524", 
            "longitude" => "-79.0215336", 
            "min_latitude" => "-8.10999", 
            "max_latitude" => "-8.10665", 
            "max_longitude" => "-79.01808", 
            "min_longitude" => "-79.02128"]
        );

        factory(TerritorioVecinal::class)->create([
            "coordenadas" => "-8.10999,-79.02103;-8.10768,-79.02128;-8.1068,-79.02118;-8.10665,-79.01817;-8.10971,-79.01808", 
            "latitude" => "-8.1090524", 
            "longitude" => "-79.0215336", 
            "min_latitude" => "-8.10999", 
            "max_latitude" => "-8.10665", 
            "max_longitude" => "-79.01808", 
            "min_longitude" => "-79.02128"]
        );

        factory(TerritorioVecinal::class)->create([
            "coordenadas" => "-8.10999,-79.02103;-8.10768,-79.02128;-8.1068,-79.02118;-8.10665,-79.01817;-8.10971,-79.01808", 
            "latitude" => "-8.1090524", 
            "longitude" => "-79.0215336", 
            "min_latitude" => "-8.10999", 
            "max_latitude" => "-8.10665", 
            "max_longitude" => "-79.01808", 
            "min_longitude" => "-79.02128"]
        );

    }
}
