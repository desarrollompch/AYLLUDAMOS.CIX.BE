<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotonPanicoUser extends Model
{
  protected $table = "boton_panico_user";

  protected $fillable = [
    'id',
    'user_id',
    'accion',
    'fecha',
    'tiempo',
    'telefono'
  ];

  public $timestamps = false;

  // public function user()
  // {
  //     return $this->hasOne(User::class, "persona_id");
  // }
}
