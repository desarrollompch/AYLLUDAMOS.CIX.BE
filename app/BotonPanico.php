<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotonPanico extends Model
{
  protected $table = "boton_panico";

  protected $fillable = [
      "id",
      'nombre',
      'state',
      'fecha'
  ];

  public $timestamps = false;

  // public function user()
  // {
  //     return $this->hasOne(User::class, "persona_id");
  // }
}
