<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coordinacion extends Model
{
    protected $table = "coordinacion";

    protected $fillable = [
        "descripcion"
    ];
}
