<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidenteMedia extends Model
{
    protected $table = "incidente_media";

    protected $fillable = [
        "incidente_id",
        "tipo_media",
        "incidente_media_name",
        "incidente_media_url"
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, "incidente_id");
    }
    
}
