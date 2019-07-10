<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inundacion extends Model
{
    protected $table = "inundacion";

    protected $fillable = [
        'tipo_inundacion',
        'incidente_id',
        'nivel_agua_id'
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, "incidente_id");
    }

    public function nivelAgua()
    {
        return $this->belongsTo(NivelAgua::class, "nivel_agua_id");
    }
}
