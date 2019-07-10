<?php

namespace App\Providers;

use App\ActividadPuntuacion;
use App\AlcaldeVecinal;
use App\ComiteGestion;
use App\Configuracion;
use App\Coordinacion;
use App\Directorio;
use App\EstadoIncidente;
use App\Incidente;
use App\Nacionalidad;
use App\NivelAgua;
use App\NivelCiudadano;
use App\Persona;
use App\Policies\ACLPolicy;
use App\Rol;
use App\TerritorioVecinal;
use App\TipoIncidente;
use App\TipoObstaculo;
use App\TipoPersona;
use App\Urbanizacion;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ActividadPuntuacion::class => ACLPolicy::class,
        AlcaldeVecinal::class => ACLPolicy::class,
        ComiteGestion::class => ACLPolicy::class,
        Coordinacion::class => ACLPolicy::class,
        Directorio::class => ACLPolicy::class,
        EstadoIncidente::class => ACLPolicy::class,
        Incidente::class => ACLPolicy::class,
        NivelAgua::class => ACLPolicy::class,
        NivelCiudadano::class => ACLPolicy::class,
        Persona::class => ACLPolicy::class,
        Rol::class => ACLPolicy::class,
        TerritorioVecinal::class => ACLPolicy::class,
        TipoIncidente::class => ACLPolicy::class,
        TipoObstaculo::class => ACLPolicy::class,
        TipoPersona::class => ACLPolicy::class,
        Urbanizacion::class => ACLPolicy::class,
        User::class => ACLPolicy::class,
        Configuracion::class => ACLPolicy::class,
        Nacionalidad::class => ACLPolicy::class,
        Activity::class => ACLPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
