<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = "notificacion";

    protected $fillable = [
        'incidente_id',
        'descripcion',
        'state'
    ];
}
