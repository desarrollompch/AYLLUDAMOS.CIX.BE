<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $table = "incidente";

    protected $fillable = [
        'fecha',
        'descripcion',
        'direccion',
        'persona_id_validador',
        'latitud',
        'longitud',
        'urbanizacion_id',
        'persona_id',
        'estado_incidente_id',
        'tipo_incidente_id',
        'imagen',
        'rol_id'
    ];

    protected $appends = ['src_imagen', 'hora'];

    public function getSrcImagenAttribute()
    {
        if(!empty($this->attributes['imagen']))
        {
            return url('/storage/images/incidentes/'.$this->attributes['imagen']);
        }

        return '';
    }

    public function getHoraAttribute()
    {
        $hora = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('d/m/Y H:i:s');

        return $hora;
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, "rol_id");
    }

    public function urbanizacion()
    {
        return $this->belongsTo(Urbanizacion::class, "urbanizacion_id");
    }

    public function estadoIncidente()
    {
        return $this->belongsTo(EstadoIncidente::class, "estado_incidente_id");
    }

    public function tipoIncidente()
    {
        return $this->belongsTo(TipoIncidente::class, "tipo_incidente_id");
    }

    public function inundacion()
    {
        return $this->hasOne(Inundacion::class, "incidente_id", "id");
    }

    public function polylines()
    {
        return $this->hasMany(Polyline::class, "incidente_id", "id");
    }

    public function calleObstaculo()
    {
        return $this->hasOne(CalleObstaculo::class, "incidente_id", "id");
    }

    public function incidentesmedia()
    {
        return $this->hasMany(IncidenteMedia::class, "incidente_id", "id");
    }

    public function atencionIncidente()
    {
        return $this->hasMany(AtencionIncidente::class, "incidente_id", "id");
    }

    public function coordinaciones()
    {
        return $this->belongsToMany(Coordinacion::class, 'incidente_coordinacion', 'incidente_id', 'coordinacion_id');
    }

    public function scopeFilterFecha($query,$value){
        if(!empty($value))
        {
            $date = Carbon::createFromFormat('d/m/Y', $value);
            $query->whereDate('fecha', $date->format('Y-m-d'));
        }
    }

    public function scopeFilterFechaFormat($query,$value){
        if(!empty($value))
        {
            $date = Carbon::createFromFormat('d/m/Y', $value);
            $query->whereDate('fecha', $date->format('Y-m-d'));
            //$query->where(DB::raw("(DATE_FORMAT(fecha,'%Y-%m-%d'))"), $date->format('Y-m-d'));
        }
    }

    public function scopeFilterFechaRango($query,$valuestart, $valueend){
        if(!empty($valuestart) && !empty($valueend))
        {
            $fecha_inicio = Carbon::parse($valuestart)->startOfDay();
            $fecha_final = Carbon::parse($valueend)->endOfDay();

            $query->whereBetween("fecha", array($fecha_inicio, $fecha_final));
        }
    }

    public function scopeFilterEstado($query,$value){
        if(!empty($value))
        {
            $query->where('estado_incidente_id', $value);
        }
    }

    public function scopeFilterUrbanizacion($query,$value){
        if(!empty($value))
        {
            $query->where('urbanizacion_id', $value);
        }
    }

    public function scopeFilterTerritorioVecinal($query,$value){
        if(!empty($value))
        {
            $query->join('urbanizacion', 'urbanizacion.id', '=', 'incidente.urbanizacion_id')
            ->where('urbanizacion.territorio_vecinal_id', $value);
        }
    }

}
