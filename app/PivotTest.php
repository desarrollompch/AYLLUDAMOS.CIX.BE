<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PivotTest extends Model
{
    protected $table = "pivottest";

    protected $fillable = [
        "nombre",
        "edad"
    ];
}
