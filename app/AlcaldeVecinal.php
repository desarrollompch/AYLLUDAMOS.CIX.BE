<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlcaldeVecinal extends Model
{
    protected $table = "alcaldes_vecinales";

    protected $fillable = [
        "persona_id",
        'territorio_vecinal_id'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function territorioVecinal()
    {
        return $this->belongsTo(TerritorioVecinal::class, "territorio_vecinal_id");
    }
}
