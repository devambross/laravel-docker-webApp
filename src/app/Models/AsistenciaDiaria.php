<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsistenciaDiaria extends Model
{
    protected $table = 'asistencias_diarias';

    protected $fillable = [
        'codigo_socio',
        'tipo',
        'nombre',
        'dni',
        'evento_id',
        'evento_nombre',
        'fecha_hora_entrada',
        'fecha'
    ];

    protected $casts = [
        'fecha_hora_entrada' => 'datetime',
        'fecha' => 'date'
    ];

    /**
     * Relación con evento (si aplica)
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Verificar si ya asistió hoy
     */
    public static function yaAsistioHoy($codigoSocio)
    {
        return self::where('codigo_socio', $codigoSocio)
                   ->whereDate('fecha', Carbon::today())
                   ->exists();
    }

    /**
     * Obtener todas las asistencias del día
     */
    public static function asistenciasDelDia($fecha = null)
    {
        $fecha = $fecha ?? Carbon::today();

        return self::whereDate('fecha', $fecha)
                   ->orderBy('fecha_hora_entrada')
                   ->get();
    }

    /**
     * Contar asistencias del día por tipo
     */
    public static function estadisticasDelDia($fecha = null)
    {
        $fecha = $fecha ?? Carbon::today();

        $total = self::whereDate('fecha', $fecha)->count();
        $socios = self::whereDate('fecha', $fecha)->where('tipo', 'socio')->count();
        $familiares = self::whereDate('fecha', $fecha)->where('tipo', 'familiar')->count();
        $invitados = self::whereDate('fecha', $fecha)->where('tipo', 'invitado')->count();

        return [
            'total' => $total,
            'socios' => $socios,
            'familiares' => $familiares,
            'invitados' => $invitados,
            'fecha' => $fecha
        ];
    }

    /**
     * Obtener códigos únicos que asistieron hoy
     */
    public static function codigosAsistieronHoy()
    {
        return self::whereDate('fecha', Carbon::today())
                   ->pluck('codigo_socio')
                   ->unique()
                   ->values()
                   ->toArray();
    }

    /**
     * Contar cuántas veces asistió hoy un código específico
     */
    public static function contarAsistenciasHoy($codigoSocio)
    {
        return self::where('codigo_socio', $codigoSocio)
                   ->whereDate('fecha', Carbon::today())
                   ->count();
    }
}
