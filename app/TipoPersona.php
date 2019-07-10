<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPersona extends Model
{
    protected $table = "tipo_persona";

    protected $fillable = [
        "descripcion"
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, "tipo_persona_id", "id");
    }
}
