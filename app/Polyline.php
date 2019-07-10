<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Polyline extends Model
{
    protected $table = "polylines";
    protected $appends = ['coordinates_data'];
    //protected $appends = ['coodinates_data'];

    protected $fillable = [
        'coordinates',
        'incidente_id',
        'descripcion'
    ];

    public function getCoordinatesAttribute()
    {
        $data = explode(';', $this->attributes['coordinates']);
        $final_data = [];
        foreach ($data as $item)
        {
            $item_final = explode(',', $item);
            $final_data[] = $item_final;

        }

        return $final_data;
    }

    public function getCoordinatesDataAttribute()
    {
        $data = explode(';', $this->attributes['coordinates']);
        $final_data = [];
        foreach ($data as $item)
        {
            $item_final = explode(',', $item);
            $final_data[] = ['latitud'=>$item_final[0], 'longitud'=>$item_final[1]];
        }

        return $final_data;
    }
}
