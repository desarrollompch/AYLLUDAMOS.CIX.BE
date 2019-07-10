<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directorio extends Model
{
    protected $table = "directorio";

    protected $fillable = [
        "nombre","direccion","telefono"
    ];
}
