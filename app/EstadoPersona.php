<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoPersona extends Model
{
    protected $table = "estado_persona";

    protected $fillable = [
        "descripcion"
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, "estado_persona_id", "id");
    }
}
