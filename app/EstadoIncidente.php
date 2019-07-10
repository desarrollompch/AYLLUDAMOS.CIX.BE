<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoIncidente extends Model
{
    protected $table = "estado_incidente";

    protected $fillable = [
        "descripcion", "color"
    ];

    public function actividadesPuntuaciones()
    {
        return $this->hasMany(ActividadPuntuacion::class, "estado_incidente_id", "id");
    }

    public function incidentes()
    {
        return $this->hasMany(Incidente::class, "estado_incidente_id", "id");
    }
}
