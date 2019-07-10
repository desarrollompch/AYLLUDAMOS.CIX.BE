<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NivelAgua extends Model
{
    protected $table = "nivel_agua";

    protected $fillable = [
        "descripcion"
    ];

    protected $appends = ['src_imagen'];

    public function inundaciones()
    {
        return $this->hasMany(Inundacion::class, "nivel_agua_id", "id");
    }

    public function getSrcImagenAttribute()
    {
        if(!empty($this->attributes['imagen']))
        {
            return url('/storage/images/nivel-agua/'.$this->attributes['imagen']);
        }

        return '';
    }
}
