<?php

use Illuminate\Database\Seeder;
use App\Persona;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $persona_0 = Persona::where("mail", "administrador@gmail.com")->first();

        $persona_1 = Persona::where("mail", "juan.racchumi.nima@gmail.com")->first();

        $persona_2 = Persona::where("mail", "asistemas@adylconsulting.com")->first();

        $persona_3 = Persona::where("mail", "juancarlos@email.com")->first();

        $persona_4 = Persona::where("mail", "plandet@gmail.com")->first();

        $persona_5 = Persona::where("mail", "alcalde@gmail.com")->first();

        $persona_6 = Persona::where("mail", "comite@gmail.com")->first();

        // User::create([
        //     'name'=>'Administrador',
        //     'last_name'=>'Aministrador',
        //     'email'=>'administrador@gmail.com',
        //     'password'=>bcrypt('123456'),
        //     'admin'=>1,
        //     'state'=>'Activo'
        // ]);

        factory(User::class)->create([
            "name"          => $persona_0->nombres,
            "email"         => $persona_0->mail,
            "password"      => "123456",
            "last_name"     => $persona_0->nombres,
            "admin"         => 1,
            "state"         => "Activo",
            "persona_id"    => $persona_0->id,
            "rol_id"        => $persona_0->rol_id,
        ]);
        

        factory(User::class)->create([
            "name"          => $persona_1->nombres,
            "email"         => $persona_1->mail,
            "password"      => "123456",
            "last_name"     => $persona_1->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_1->id,
            "rol_id"        => $persona_1->rol_id,
        ]);

        factory(User::class)->create([
            "name"          => $persona_2->nombres,
            "email"         => $persona_2->mail,
            "password"      => "123456",
            "last_name"     => $persona_2->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_2->id,
            "rol_id"        => $persona_2->rol_id
        ]);

        factory(User::class)->create([
            "name"          => $persona_3->nombres,
            "email"         => $persona_3->mail,
            "password"      => "123456",
            "last_name"     => $persona_3->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_3->id,
            "rol_id"        => $persona_3->rol_id
        ]);

        factory(User::class)->create([
            "name"          => $persona_4->nombres,
            "email"         => $persona_4->mail,
            "password"      => "123456",
            "last_name"     => $persona_4->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_4->id,
            "rol_id"        => $persona_4->rol_id
        ]);

        factory(User::class)->create([
            "name"          => $persona_5->nombres,
            "email"         => $persona_5->mail,
            "password"      => "123456",
            "last_name"     => $persona_5->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_5->id,
            "rol_id"        => $persona_5->rol_id
        ]);

        factory(User::class)->create([
            "name"          => $persona_6->nombres,
            "email"         => $persona_6->mail,
            "password"      => "123456",
            "last_name"     => $persona_6->nombres,
            "admin"         => 0,
            "state"         => "Activo",
            "persona_id"    => $persona_6->id,
            "rol_id"        => $persona_6->rol_id
        ]);

    }
}
