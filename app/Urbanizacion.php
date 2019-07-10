<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urbanizacion extends Model
{
    protected $table = "urbanizacion";

    protected $fillable = [
        'coordenadas',
        'min_latitude',
        'max_latitude',
        'max_longitude',
        'min_longitude',
        'latitude',
        'longitude',
        'descripcion',
        'territorio_vecinal_id'
    ];

    public function territorioVecinal()
    {
        return $this->belongsTo(TerritorioVecinal::class, "territorio_vecinal_id");
    }

    public function personas()
    {
        return $this->hasMany(Persona::class, "urbanizacion_id", "id");
    }

    public function incidentes()
    {
        return $this->hasMany(incidente::class, "urbanizacion_id", "id");
    }

    public function getCoordenadasAttribute()
    {
        $data = explode(';', $this->attributes['coordenadas']);
        $final_data = [];
        foreach ($data as $item)
        {
            $item_final = explode(',', $item);
            $final_data[] = $item_final;

        }

        return $final_data;
    }

}
