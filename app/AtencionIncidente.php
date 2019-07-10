<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AtencionIncidente extends Model
{
    protected $table = "atencion_incidente";

    protected $fillable = [
        "persona_id",
        "incidente_id",
        "fecha",
        "descripcion"
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, "incidente_id");
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function getFechaAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['fecha'])->format('d/m/Y h:i:s A');
    }
}
