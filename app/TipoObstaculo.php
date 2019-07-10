<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoObstaculo extends Model
{
    protected $table = "tipo_obstaculo";

    protected $fillable = [
        "descripcion"
    ];

    protected $appends = ['src_imagen'];

    public function callesObstaculos()
    {
        return $this->hasMany(CalleObstaculo::class, "tipo_obstaculo_id", "id");
    }

    public function getSrcImagenAttribute()
    {
        if(!empty($this->attributes['imagen']))
        {
            return url('/storage/images/tipo-obstaculo/'.$this->attributes['imagen']);
        }

        return '';
    }
}
