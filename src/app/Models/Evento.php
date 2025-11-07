<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'nombre',
        'fecha',
        'descripcion',
        'estado'
    ];

    public function participantes()
    {
        return $this->belongsToMany(User::class, 'evento_user')
            ->withPivot(['mesa', 'asiento', 'asistencia_1', 'asistencia_2']);
    }
}
