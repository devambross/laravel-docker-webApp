<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = [
        'evento_id',
        'numero_mesa',
        'capacidad'
    ];

    protected $casts = [
        'evento_id' => 'integer',
        'capacidad' => 'integer'
    ];

    /**
     * Relación con evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Relación con participantes
     */
    public function participantes()
    {
        return $this->hasMany(ParticipanteEvento::class);
    }

    /**
     * Obtener el número de sillas ocupadas
     */
    public function getOcupadasAttribute()
    {
        return $this->participantes()->count();
    }

    /**
     * Obtener el número de sillas disponibles
     */
    public function getDisponiblesAttribute()
    {
        return $this->capacidad - $this->ocupadas;
    }

    /**
     * Verificar si la mesa está completa
     */
    public function estaCompleta()
    {
        return $this->ocupadas >= $this->capacidad;
    }
}
