<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    protected $table = "familiar";

    protected $fillable = [
        "nombres",
        "telefono",
        "persona_id"
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function familiarubicacion()
    {
        return $this->hasMany(FamiliarUbicacion::class, "familiar_id", "id");
    }
}
