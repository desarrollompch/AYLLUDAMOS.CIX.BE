<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        
        $this->call(TipoPersonaSeeder::class);
        $this->call(NivelCiudadanoSeeder::class);
        $this->call(EstadoIncidenteSeeder::class);      
        $this->call(TipoIncidenteSeeder::class);
        $this->call(NivelAguaSeeder::class);
        $this->call(TipoObstaculoSeeder::class);
        $this->call(EstadoPersonaSeeder::class);

        $this->call(TerritorioVecinalSeeder::class);
        $this->call(UrbanizacionSeeder::class);

        $this->call(RolSeeder::class);
        $this->call(PersonaSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(DirectorioSeeder::class);

        $this->call(ActividadPuntuacionSeeder::class);

        $this->call(PuntuacionPersonaSeeder::class);

        $this->call(FamiliarSeeder::class);

        $this->call(IncidenteSeeder::class);

        $this->call(IncidenteMediaSeeder::class);

        $this->call(AtencionIncidenteSeeder::class);

        $this->call(FamiliarUbicacionSeeder::class);


    }
}
