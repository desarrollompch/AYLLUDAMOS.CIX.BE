<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnvioAutomatico extends Model
{
    protected $table = "envio_automatico";

    protected $fillable = [
        'id',
        'user_id',
        'accion',
        'tiempo',
        'telefono'
    ];

    public $timestamps = false;
}
