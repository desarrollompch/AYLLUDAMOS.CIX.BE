<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    protected $table = "puntuacion";

    protected $fillable = [
        "total_minimo",
        "total_maximo"
    ];

    public function nivelesCiudadanos()
    {
        return $this->hasMany(Puntuacion::class, "puntuacion_id", "id");
    }
}
