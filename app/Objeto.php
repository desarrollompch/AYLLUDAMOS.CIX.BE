<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    protected $table = "objeto";

    protected $fillable = [
        "descripcion", "estado"
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class)->withTimestamps();
    }
}
