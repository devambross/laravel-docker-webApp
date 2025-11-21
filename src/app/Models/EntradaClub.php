<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradaClub extends Model
{
    protected $table = 'entrada_club';

    protected $fillable = [
        'codigo_participante',
        'tipo',
        'nombre',
        'dni',
        'area',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    /**
     * Scope para filtrar por fecha
     */
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha_hora', $fecha);
    }

    /**
     * Scope para buscar por código o nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('codigo_participante', 'like', "%{$termino}%")
                    ->orWhere('nombre', 'like', "%{$termino}%")
                    ->orWhere('dni', 'like', "%{$termino}%");
    }
}
