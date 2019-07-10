<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FamiliarUbicacion extends Model
{
    protected $table = "familiar_ubicacion";

    protected $fillable = [
        "familiar_id",
        "fecha",
        "latitude",
        "longitude",
        "descripcion"
    ];

    
    public function familiar()
    {
        return $this->belongsTo(Familiar::class, "familiar_id");
    }

    // public function scopeFilterFechas($query, $fecha_inicio, $fecha_final) {
    //     if(strlen($fecha_inicio) > 0 && strlen($fecha_final) == 0) {
    //         $date_inicio = Carbon::createFromFormat('d/m/Y', $fecha_inicio);
    //         $query->where("fecha", $date_inicio->format('Y-m-d'));
    //     }else if(strlen($fecha_inicio) > 0 && strlen($fecha_final) > 0) {
    //         $date_inicio = Carbon::createFromFormat('d/m/Y', $fecha_inicio);
    //         $date_final = Carbon::createFromFormat('d/m/Y', $fecha_final);
    //         $query->whereIn("fecha", [$date_inicio, $date_final]);
    //     }else if(strlen($fecha_inicio) == 0 && strlen($fecha_final) == 0) {
    //         $date_actual = Carbon::now();
    //         $query->where("fecha", $date_actual);
    //     }
    // }
}
