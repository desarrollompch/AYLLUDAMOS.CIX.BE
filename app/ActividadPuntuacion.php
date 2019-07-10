<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActividadPuntuacion extends Model
{
    protected $table = "actividad_puntuacion";

    protected $fillable = [
        "descripcion",
        "puntaje",
        "estado_incidente_id"
    ];

    public function estadoIncidente()
    {
        return $this->belongsTo(EstadoIncidente::class, "estado_incidente_id");
    }

    public function puntuacionesPersona()
    {
        return $this->hasMany(PuntuacionPersona::class, "actividad_puntuacion_id", "id");
    }
}
