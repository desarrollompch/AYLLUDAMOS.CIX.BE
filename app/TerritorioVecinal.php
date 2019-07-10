<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TerritorioVecinal extends Model
{
    protected $table = "territorio_vecinal";

    protected $fillable = [
        'descripcion',
        'coordenadas',
        'min_latitude',
        'max_latitude',
        'max_longitude',
        'min_longitude',
        'latitude',
        'longitude'
    ];

    public function urbanizaciones()
    {
        return $this->hasMany(Urbanizacion::class, "territorio_vecinal_id", "id");
    }

    public function getCoordenadasAttribute()
    {
        $data = explode(';', $this->attributes['coordenadas']);
        $final_data = [];
        foreach ($data as $item)
        {
            $item_final = explode(',', $item);
            $final_data[] = [(float) $item_final[0], (float)$item_final[1]];

        }

        return $final_data;
    }

    public function alcaldes()
    {
        return $this->hasMany(AlcaldeVecinal::class, 'territorio_vecinal_id');
    }


}
