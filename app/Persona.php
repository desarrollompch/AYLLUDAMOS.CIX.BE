<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "persona";

    protected $fillable = [
        'ape_paterno',
        'ape_materno',
        'nombres',
        'dni',
        'telefono',
        'mail',
        'direccion',
        'state',
        'tipo_persona_id',
        'nivel_ciudadano_id',
        'urbanizacion_id',
        'rol_id',
        'estado_persona_id'
    ];

    public function tipoPersona()
    {
        return $this->belongsTo(TipoPersona::class, "tipo_persona_id");
    }

    public function nivelCiudadano()
    {
        return $this->belongsTo(NivelCiudadano::class, "nivel_ciudadano_id");
    }

    public function estadoPersona()
    {
        return $this->belongsTo(EstadoPersona::class, "estado_persona_id");
    }

    public function urbanizacion()
    {
        return $this->belongsTo(Urbanizacion::class, "urbanizacion_id");
    }

    public function incidentes()
    {
        return $this->hasMany(Incidente::class, "persona_id", "id");
    }

    public function familiares()
    {
        return $this->hasMany(Familiar::class, "persona_id", "id");
    }

    public function atencion_incidentes()
    {
        return $this->hasMany(AtencionIncidente::class, "persona_id", "id");
    }

    public function puntuacionesPersona()
    {
        return $this->hasMany(PuntuacionPersona::class, "persona_id", "id");
    }

    public function user()
    {
        return $this->hasOne(User::class, "persona_id");
    }

    public function scopeFilter($query, $value)
    {
        if(!empty($value))
        {
            $query->where('tipo_persona_id', $value);
        }
    }

    //BUSQUEDA POR FILTROS
    public function scopeFilterNombre($query, $value)
    {
      if(!empty($value))
      {
        $query->whereRaw("CONCAT(persona.nombres,' ',persona.ape_paterno,' ',persona.ape_materno) LIKE ?",["%$value%"]);
      }
    }

    public function scopeFilterTipoPersona($query, $value)
    {
      if(!empty($value))
      {
        $query->where('persona.tipo_persona_id', $value);
      }
    }

    public function scopeFilterDni($query, $value)
    {
      if(!empty($value))
      {
        $query->where('persona.dni',$value);
      }
    }
}
