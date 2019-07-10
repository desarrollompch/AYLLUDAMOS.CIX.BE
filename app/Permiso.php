<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = "permisos";

    protected $fillable = [
        'model',
        'action',
        'rol_id'
    ];


}
