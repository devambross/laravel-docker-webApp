<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipanteEvento extends Model
{
    protected $table = 'participantes_evento';

    protected $fillable = [
        'evento_id',
        'mesa_id',
        'numero_silla',
        'tipo',
        'codigo_socio',
        'codigo_participante',
        'dni',
        'nombre',
        'n_recibo',
        'n_boleta'
    ];

    protected $casts = [
        'evento_id' => 'integer',
        'mesa_id' => 'integer',
        'numero_silla' => 'integer'
    ];

    /**
     * Relación con evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Relación con mesa
     */
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    /**
     * Relación con entrada del evento
     */
    public function entradaEvento()
    {
        return $this->hasOne(EntradaEvento::class);
    }

    /**
     * Verificar si es un socio
     */
    public function esSocio()
    {
        return $this->tipo === 'socio';
    }

    /**
     * Verificar si es un invitado
     */
    public function esInvitado()
    {
        return $this->tipo === 'invitado';
    }

    /**
     * Obtener el nombre de la mesa/silla
     */
    public function getMesaSillaAttribute()
    {
        if (!$this->mesa) {
            return 'Sin asignar';
        }

        return "Mesa {$this->mesa->numero_mesa}" .
               ($this->numero_silla ? " - Silla {$this->numero_silla}" : '');
    }
}
