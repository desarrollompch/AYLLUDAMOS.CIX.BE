<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoIncidente extends Model
{
    protected $table = "tipo_incidente";

    protected $fillable = [
        "descripcion"
    ];

    public function incidentes()
    {
        return $this->hasMany(Incidente::class, "tipo_incidente_id", "id");
    }
}
