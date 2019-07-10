<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntuacionPersona extends Model
{
    protected $table = "puntuacion_persona";

    protected $fillable = [
        "numero_incidente",
        "actividad_puntuacion_id",
        "persona_id"
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function actividadPuntuacion()
    {
        return $this->belongsTo(ActividadPuntuacion::class, "actividad_puntuacion_id");
    }
}
