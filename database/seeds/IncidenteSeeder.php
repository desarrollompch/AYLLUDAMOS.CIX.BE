<?php

use Illuminate\Database\Seeder;
use App\Incidente;
use App\Inundacion;
Use App\NivelAgua;
use App\IncidenteMedia;
use Faker\Generator as Faker;

class IncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {     

        //Creamos 5 Incidencias
        factory(Incidente::class)->create(); 
        factory(Incidente::class)->create(); 
        factory(Incidente::class)->create(); 
        factory(Incidente::class)->create(); 
        factory(Incidente::class)->create();       


        //Objetos Incidentes
        $incidente_1 = Incidente::find(1);  
        $incidente_2 = Incidente::find(2);
        $incidente_3 = Incidente::find(3);
        $incidente_4 = Incidente::find(4);
        $incidente_5 = Incidente::find(5);

        $nivelagua = NivelAgua::first();

        
        Inundacion::create([
            'tipo_inundacion'	=>$nivelagua->id,
            'incidente_id'		=>$incidente_1->id,
            'nivel_agua_id'		=>$nivelagua->id
        ]);
        

        factory(IncidenteMedia::class)->create([
            "incidente_id"          =>$incidente_1->id,
            "tipo_media"            =>"Imagen"
        ]);


		Inundacion::create([
            'tipo_inundacion'=>$nivelagua->id,
            'incidente_id'=>$incidente_2->id,
            'nivel_agua_id'=>$nivelagua->id
        ]);

        factory(IncidenteMedia::class)->create([
            "incidente_id"          =>$incidente_2->id,
            "tipo_media"            =>"Imagen"
        ]);

        Inundacion::create([
            'tipo_inundacion'=>$nivelagua->id,
            'incidente_id'=>$incidente_3->id,
            'nivel_agua_id'=>$nivelagua->id
        ]);

        factory(IncidenteMedia::class)->create([
            "incidente_id"          =>$incidente_3->id,
            "tipo_media"            =>"Imagen"
        ]);

        Inundacion::create([
            'tipo_inundacion'=>$nivelagua->id,
            'incidente_id'=>$incidente_4->id,
            'nivel_agua_id'=>$nivelagua->id
        ]);

        factory(IncidenteMedia::class)->create([
            "incidente_id"          =>$incidente_4->id,
            "tipo_media"            =>"Imagen"
        ]);

        Inundacion::create([
            'tipo_inundacion'=>$nivelagua->id,
            'incidente_id'=>$incidente_5->id,
            'nivel_agua_id'=>$nivelagua->id
        ]);

        factory(IncidenteMedia::class)->create([
            "incidente_id"          =>$incidente_5->id,
            "tipo_media"            =>"Imagen"
        ]); 


    }
}
