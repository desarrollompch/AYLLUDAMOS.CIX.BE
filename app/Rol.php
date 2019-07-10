<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = "rol";

    protected $fillable = [
        'descripcion',
        'estado',
        'permisos'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, "rol_id", "id");
    }

    public function setPermisosAttribute($value)
    {
        if($value >= 0)
        {
            $this->attributes['permisos'] = implode(',', $value);
        }else{
            $this->attributes['permisos'] = "";
        }
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'rol_id');
    }

    public function getPermisosAttribute()
    {
        if($this->attributes['permisos'] != '')
        {
            return explode(',', $this->attributes['permisos']);
        }else{
            return [];
        }

    }

}
