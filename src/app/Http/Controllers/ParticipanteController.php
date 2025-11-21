<?php

namespace App\Http\Controllers;

use App\Models\ParticipanteEvento;
use App\Models\Evento;
use App\Models\Mesa;
use App\Models\EntradaEvento;
use App\Services\SocioAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParticipanteController extends Controller
{
    protected $socioAPI;

    public function __construct(SocioAPIService $socioAPI)
    {
        $this->socioAPI = $socioAPI;
    }

    /**
     * Registrar participante en un evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'mesa_id' => 'nullable|exists:mesas,id',
            'numero_silla' => 'nullable|integer|min:1',
            'tipo' => 'required|in:socio,invitado',
            'codigo_socio' => 'required|string|max:50',
            'codigo_participante' => 'required|string|max:50',
            'dni' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'n_recibo' => 'nullable|string|max:100',
            'n_boleta' => 'nullable|string|max:100'
        ]);

        try {
            DB::beginTransaction();

            // Verificar disponibilidad de mesa/silla si se especificó
            if ($validated['mesa_id'] && $validated['numero_silla']) {
                $mesa = Mesa::findOrFail($validated['mesa_id']);

                if ($mesa->estaCompleta()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La mesa está completa'
                    ], 422);
                }

                // Verificar que la silla específica no esté ocupada
                $sillaOcupada = ParticipanteEvento::where('mesa_id', $validated['mesa_id'])
                                                  ->where('numero_silla', $validated['numero_silla'])
                                                  ->exists();

                if ($sillaOcupada) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La silla ya está ocupada'
                    ], 422);
                }
            }

            // Verificar que el código de participante sea único en el evento
            $existente = ParticipanteEvento::where('evento_id', $validated['evento_id'])
                                          ->where('codigo_participante', $validated['codigo_participante'])
                                          ->exists();

            if ($existente) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código de participante ya está registrado en este evento'
                ], 422);
            }

            // Crear participante
            $participante = ParticipanteEvento::create($validated);

            // Crear registro de entrada (sin marcar asistencia aún)
            EntradaEvento::create([
                'participante_evento_id' => $participante->id,
                'entrada_club' => false,
                'entrada_evento' => false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Participante registrado exitosamente',
                'data' => $participante->load('mesa', 'entradaEvento')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar participante: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el participante'
            ], 500);
        }
    }

    /**
     * Buscar socio en API externa para registrarlo
     */
    public function buscarSocio(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required_without:dni|string',
            'dni' => 'required_without:codigo|string'
        ]);

        try {
            $socio = null;

            if (isset($validated['codigo'])) {
                $socio = $this->socioAPI->buscarSocioPorCodigo($validated['codigo']);
            } else {
                $socio = $this->socioAPI->buscarSocioPorDNI($validated['dni']);
            }

            if (!$socio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Socio no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->socioAPI->formatearSocio($socio)
            ]);

        } catch (\Exception $e) {
            Log::error("Error al buscar socio: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar el socio'
            ], 500);
        }
    }

    /**
     * Obtener familiares de un socio titular desde API externa
     * Familiares tienen formato: ####-XXX (ej: 0001-A, 0234-FAM)
     */
    public function familiaresSocio($codigoSocio)
    {
        try {
            $familiares = $this->socioAPI->obtenerFamiliaresSocio($codigoSocio);

            return response()->json([
                'success' => true,
                'data' => array_map(function($fam) use ($codigoSocio) {
                    return $this->socioAPI->formatearFamiliar($fam, $codigoSocio);
                }, $familiares)
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener familiares: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los familiares'
            ], 500);
        }
    }

    /**
     * Alias para compatibilidad
     * @deprecated Usar familiaresSocio() en su lugar
     */
    public function invitadosSocio($codigoSocio)
    {
        return $this->familiaresSocio($codigoSocio);
    }

    /**
     * Actualizar asignación de mesa/silla de un participante
     */
    public function actualizarMesa(Request $request, $id)
    {
        $participante = ParticipanteEvento::findOrFail($id);

        $validated = $request->validate([
            'mesa_id' => 'nullable|exists:mesas,id',
            'numero_silla' => 'nullable|integer|min:1'
        ]);

        try {
            // Verificar disponibilidad de la nueva mesa/silla
            if ($validated['mesa_id'] && $validated['numero_silla']) {
                $sillaOcupada = ParticipanteEvento::where('mesa_id', $validated['mesa_id'])
                                                  ->where('numero_silla', $validated['numero_silla'])
                                                  ->where('id', '!=', $id)
                                                  ->exists();

                if ($sillaOcupada) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La silla ya está ocupada'
                    ], 422);
                }

                $mesa = Mesa::findOrFail($validated['mesa_id']);
                if ($mesa->estaCompleta() && $participante->mesa_id !== $validated['mesa_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La mesa está completa'
                    ], 422);
                }
            }

            $participante->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asignación actualizada exitosamente',
                'data' => $participante->load('mesa')
            ]);

        } catch (\Exception $e) {
            Log::error("Error al actualizar mesa: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la asignación'
            ], 500);
        }
    }

    /**
     * Eliminar participante de un evento
     */
    public function destroy($id)
    {
        try {
            $participante = ParticipanteEvento::findOrFail($id);
            $participante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Participante eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error("Error al eliminar participante: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el participante'
            ], 500);
        }
    }

    /**
     * Listar participantes de un evento
     */
    public function participantesEvento($eventoId)
    {
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
                    'entrada_evento' => $p->entradaEvento->entrada_evento ?? false
                ];
            })
        ]);
    }
}
