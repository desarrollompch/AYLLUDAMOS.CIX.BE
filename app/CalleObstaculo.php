<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalleObstaculo extends Model
{
    protected $table = "calle_obstaculo";

    protected $fillable = [
        "incidente_id",
        "tipo_obstaculo_id"
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, "incidente_id");
    }

    public function tipoObstaculo()
    {
        return $this->belongsTo(TipoObstaculo::class, "tipo_obstaculo_id");
    }
}
