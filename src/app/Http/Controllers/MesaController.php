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
        // Log para debugging
        Log::info('[MesaController] store() iniciado', [
            'request_data' => $request->all(),
            'has_session' => $request->session()->has('user'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        // Validar con try-catch para capturar errores
        try {
            $validated = $request->validate([
                'evento_id' => 'required|exists:eventos,id',
                'numero_mesa' => 'required|string|max:50',
                'capacidad' => 'required|integer|min:1|max:50'
            ]);

            Log::info('[MesaController] Validación exitosa');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('[MesaController] Error de validación', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            Log::info('[MesaController] Verificando duplicados...');

            // Verificar que no exista una mesa con el mismo número en este evento
            $existente = Mesa::where('evento_id', $validated['evento_id'])
                            ->where('numero_mesa', $validated['numero_mesa'])
                            ->first();

            if ($existente) {
                Log::warning('[MesaController] Mesa duplicada detectada', [
                    'numero_mesa' => $validated['numero_mesa'],
                    'evento_id' => $validated['evento_id']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una mesa con este número en el evento'
                ], 422);
            }

            Log::info('[MesaController] Creando mesa en BD...', $validated);
            $mesa = Mesa::create($validated);
            Log::info('[MesaController] Mesa creada con ID: ' . $mesa->id);

            // Actualizar capacidad total del evento
            $this->actualizarCapacidadEvento($validated['evento_id']);

            Log::info('[MesaController] Retornando respuesta exitosa');
            return response()->json([
                'success' => true,
                'message' => 'Mesa creada exitosamente',
                'data' => $mesa
            ], 201);

        } catch (\Exception $e) {
            Log::error('[MesaController] EXCEPTION: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la mesa: ' . $e->getMessage()
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
                    ->orderByRaw('CAST(numero_mesa AS INTEGER)')
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
