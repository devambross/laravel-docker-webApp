<?php

namespace App\Http\Controllers;

use App\Models\EntradaEvento;
use App\Models\ParticipanteEvento;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntradaEventoController extends Controller
{
    /**
     * Marcar entrada al club para un participante del evento
     */
    public function marcarEntradaClub(Request $request, $participanteId)
    {
        try {
            $participante = ParticipanteEvento::findOrFail($participanteId);
            $entrada = EntradaEvento::firstOrCreate(
                ['participante_evento_id' => $participante->id],
                ['entrada_club' => false, 'entrada_evento' => false]
            );

            $entrada->marcarEntradaClub();

            return response()->json([
                'success' => true,
                'message' => 'Entrada al club registrada',
                'data' => $entrada
            ]);

        } catch (\Exception $e) {
            Log::error("Error al marcar entrada club: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la entrada'
            ], 500);
        }
    }

    /**
     * Marcar entrada al evento para un participante
     */
    public function marcarEntradaEvento(Request $request, $participanteId)
    {
        try {
            $participante = ParticipanteEvento::findOrFail($participanteId);
            $entrada = EntradaEvento::firstOrCreate(
                ['participante_evento_id' => $participante->id],
                ['entrada_club' => false, 'entrada_evento' => false]
            );

            $entrada->marcarEntradaEvento();

            return response()->json([
                'success' => true,
                'message' => 'Entrada al evento registrada',
                'data' => $entrada
            ]);

        } catch (\Exception $e) {
            Log::error("Error al marcar entrada evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la entrada'
            ], 500);
        }
    }

    /**
     * Buscar participantes de un evento
     */
    public function buscarParticipante(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'termino' => 'required|string|min:1'
        ]);

        try {
            $termino = $validated['termino'];
            $eventoId = $validated['evento_id'];

            $participantes = ParticipanteEvento::where('evento_id', $eventoId)
                ->where(function($q) use ($termino) {
                    $q->where('codigo_participante', 'like', "%{$termino}%")
                      ->orWhere('nombre', 'like', "%{$termino}%")
                      ->orWhere('dni', 'like', "%{$termino}%");
                })
                ->with(['mesa', 'entradaEvento'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $participantes->map(function($p) {
                    return [
                        'id' => $p->id,
                        'codigo_participante' => $p->codigo_participante,
                        'tipo' => $p->tipo,
                        'nombre' => $p->nombre,
                        'dni' => $p->dni,
                        'mesa_silla' => $p->mesa_silla,
                        'entrada_club' => $p->entradaEvento->entrada_club ?? false,
                        'entrada_evento' => $p->entradaEvento->entrada_evento ?? false,
                        'fecha_hora_club' => $p->entradaEvento->fecha_hora_club ?? null,
                        'fecha_hora_evento' => $p->entradaEvento->fecha_hora_evento ?? null
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error("Error al buscar participante: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar participante'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de asistencia de un evento
     */
    public function estadisticas($eventoId)
    {
        try {
            $evento = Evento::with(['participantes.entradaEvento'])->findOrFail($eventoId);

            $total = $evento->participantes->count();
            $entradaClub = $evento->participantes->filter(function($p) {
                return $p->entradaEvento && $p->entradaEvento->entrada_club;
            })->count();
            $entradaEvento = $evento->participantes->filter(function($p) {
                return $p->entradaEvento && $p->entradaEvento->entrada_evento;
            })->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'entrada_club' => $entradaClub,
                    'entrada_evento' => $entradaEvento,
                    'pendientes_club' => $total - $entradaClub,
                    'pendientes_evento' => $total - $entradaEvento
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener estadísticas: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Listar todos los participantes de un evento con su estado de asistencia
     */
    public function listar($eventoId)
    {
        try {
            $participantes = ParticipanteEvento::where('evento_id', $eventoId)
                                              ->with(['mesa', 'entradaEvento'])
                                              ->orderBy('mesa_id')
                                              ->orderBy('numero_silla')
                                              ->get();

            return response()->json([
                'success' => true,
                'data' => $participantes->map(function($p) {
                    return [
                        'id' => $p->id,
                        'codigo_participante' => $p->codigo_participante,
                        'tipo' => $p->tipo,
                        'nombre' => $p->nombre,
                        'dni' => $p->dni,
                        'mesa_silla' => $p->mesa_silla,
                        'entrada_club' => $p->entradaEvento->entrada_club ?? false,
                        'entrada_evento' => $p->entradaEvento->entrada_evento ?? false,
                        'fecha_hora_club' => $p->entradaEvento->fecha_hora_club ?? null,
                        'fecha_hora_evento' => $p->entradaEvento->fecha_hora_evento ?? null
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error("Error al listar participantes: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al listar participantes'
            ], 500);
        }
    }
}
