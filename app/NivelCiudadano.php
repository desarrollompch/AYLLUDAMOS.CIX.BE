<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NivelCiudadano extends Model
{
    protected $table = "nivel_ciudadano";

    protected $hidden = ['icono'];

    protected $fillable = [
        'icono',
        'descripcion',
        'total_minimo',
        'total_maximo'
    ];

    protected $appends = ['src_icono'];

    public function personas()
    {
        return $this->hasMany(Persona::class, "nivel_ciudadano_id", "id");
    }

    public function getSrcIconoAttribute()
    {
        if(!empty($this->attributes['icono']))
        {
            return url('/storage/images/niveles-iconos/'.$this->attributes['icono']);
        }

        return '';
    }
}
