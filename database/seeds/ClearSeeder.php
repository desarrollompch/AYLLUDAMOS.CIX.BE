<?php

use Illuminate\Database\Seeder;


class ClearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("SET foreign_key_checks=0");
        \Spatie\Activitylog\Models\Activity::truncate();
        \App\Inundacion::truncate();
        \App\CalleObstaculo::truncate();
        DB::table('incidente_coordinacion')->truncate();
        \App\Notificacion::truncate();
        \App\IncidenteMedia::truncate();
        \App\AtencionIncidente::truncate();
        \App\Polyline::truncate();
        \App\Incidente::truncate();
        DB::statement("SET foreign_key_checks=1");

    }
}
