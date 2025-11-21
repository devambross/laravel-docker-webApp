<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradaEvento extends Model
{
    protected $table = 'entrada_evento';

    protected $fillable = [
        'participante_evento_id',
        'entrada_club',
        'entrada_evento',
        'fecha_hora_club',
        'fecha_hora_evento'
    ];

    protected $casts = [
        'participante_evento_id' => 'integer',
        'entrada_club' => 'boolean',
        'entrada_evento' => 'boolean',
        'fecha_hora_club' => 'datetime',
        'fecha_hora_evento' => 'datetime'
    ];

    /**
     * Relación con participante del evento
     */
    public function participante()
    {
        return $this->belongsTo(ParticipanteEvento::class, 'participante_evento_id');
    }

    /**
     * Marcar entrada al club
     */
    public function marcarEntradaClub()
    {
        $this->update([
            'entrada_club' => true,
            'fecha_hora_club' => now()
        ]);
    }

    /**
     * Marcar entrada al evento
     */
    public function marcarEntradaEvento()
    {
        $this->update([
            'entrada_evento' => true,
            'fecha_hora_evento' => now()
        ]);
    }
}
