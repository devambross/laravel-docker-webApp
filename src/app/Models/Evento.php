<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'nombre',
        'fecha',
        'fecha_fin',
        'area',
        'capacidad_total'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_fin' => 'date'
    ];

    /**
     * Relación con mesas
     */
    public function mesas()
    {
        return $this->hasMany(Mesa::class);
    }

    /**
     * Relación con participantes
     */
    public function participantes()
    {
        return $this->hasMany(ParticipanteEvento::class);
    }

    /**
     * Obtener el total de asientos ocupados
     */
    public function getAsientosOcupadosAttribute()
    {
        return $this->participantes()->count();
    }

    /**
     * Obtener el total de asientos disponibles
     */
    public function getAsientosDisponiblesAttribute()
    {
        return $this->capacidad_total - $this->asientos_ocupados;
    }
}
