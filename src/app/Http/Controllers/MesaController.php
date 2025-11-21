<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MesaController extends Controller
{
    /**
     * Crear nueva mesa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'numero_mesa' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1|max:50'
        ]);

        try {
            // Verificar que no exista una mesa con el mismo número en este evento
            $existente = Mesa::where('evento_id', $validated['evento_id'])
                            ->where('numero_mesa', $validated['numero_mesa'])
                            ->first();

            if ($existente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una mesa con este número en el evento'
                ], 422);
            }

            $mesa = Mesa::create($validated);

            // Actualizar capacidad total del evento
            $this->actualizarCapacidadEvento($validated['evento_id']);

            return response()->json([
                'success' => true,
                'message' => 'Mesa creada exitosamente',
                'data' => $mesa
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error al crear mesa: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la mesa'
            ], 500);
        }
    }

    /**
     * Obtener una mesa específica
     */
    public function show($id)
    {
        try {
            $mesa = Mesa::with('participantes')->findOrFail($id);

            return response()->json([
                'id' => $mesa->id,
                'numero' => $mesa->numero_mesa,
                'capacidad' => $mesa->capacidad,
                'ocupados' => $mesa->ocupadas,
                'evento_id' => $mesa->evento_id
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener mesa: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Mesa no encontrada'
            ], 404);
        }
    }

    /**
     * Actualizar mesa
     */
    public function update(Request $request, $id)
    {
        $mesa = Mesa::findOrFail($id);

        $validated = $request->validate([
            'numero_mesa' => 'sometimes|string|max:50',
            'capacidad' => 'sometimes|integer|min:1|max:50'
        ]);

        try {
            // Si se cambia la capacidad, verificar que no sea menor a la cantidad ocupada
            if (isset($validated['capacidad'])) {
                $ocupadas = $mesa->ocupadas;

                if ($validated['capacidad'] < $ocupadas) {
                    return response()->json([
                        'success' => false,
                        'message' => "La capacidad no puede ser menor a {$ocupadas} (sillas ocupadas actualmente)"
                    ], 422);
                }
            }

            // Verificar unicidad del número de mesa si se está cambiando
            if (isset($validated['numero_mesa']) && $validated['numero_mesa'] !== $mesa->numero_mesa) {
                $existente = Mesa::where('evento_id', $mesa->evento_id)
                                ->where('numero_mesa', $validated['numero_mesa'])
                                ->where('id', '!=', $id)
                                ->first();

                if ($existente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una mesa con este número en el evento'
                    ], 422);
                }
            }

            $mesa->update($validated);

            // Actualizar capacidad total del evento si cambió la capacidad
            if (isset($validated['capacidad'])) {
                $this->actualizarCapacidadEvento($mesa->evento_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mesa actualizada exitosamente',
                'data' => $mesa
            ]);

        } catch (\Exception $e) {
            Log::error("Error al actualizar mesa: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la mesa'
            ], 500);
        }
    }

    /**
     * Eliminar mesa
     */
    public function destroy($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);

            // Verificar que la mesa no tenga participantes asignados
            if ($mesa->ocupadas > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar una mesa con participantes asignados'
                ], 422);
            }

            $eventoId = $mesa->evento_id;
            $mesa->delete();

            // Actualizar capacidad total del evento
            $this->actualizarCapacidadEvento($eventoId);

            return response()->json([
                'success' => true,
                'message' => 'Mesa eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error("Error al eliminar mesa: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la mesa'
            ], 500);
        }
    }

    /**
     * Listar mesas de un evento
     */
    public function mesasEvento($eventoId)
    {
        $mesas = Mesa::where('evento_id', $eventoId)
                    ->with('participantes')
                    ->orderBy('numero_mesa')
                    ->get();

        return response()->json($mesas->map(function($mesa) {
            return [
                'id' => $mesa->id,
                'numero' => $mesa->numero_mesa,
                'capacidad' => $mesa->capacidad,
                'ocupados' => $mesa->ocupadas,
                'disponibles' => $mesa->disponibles,
                'completa' => $mesa->estaCompleta()
            ];
        }));
    }

    /**
     * Actualizar la capacidad total del evento sumando todas sus mesas
     */
    private function actualizarCapacidadEvento($eventoId)
    {
        $evento = Evento::find($eventoId);
        if ($evento) {
            $capacidadTotal = Mesa::where('evento_id', $eventoId)->sum('capacidad');
            $evento->update(['capacidad_total' => $capacidadTotal]);
        }
    }
}
