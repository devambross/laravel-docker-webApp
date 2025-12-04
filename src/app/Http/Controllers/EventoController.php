<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Mesa;
use App\Models\ParticipanteEvento;
use App\Models\EntradaEvento;
use App\Services\EventoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EventoController extends Controller
{
    protected $eventoService;

    public function __construct(EventoService $eventoService)
    {
        $this->eventoService = $eventoService;
    }

    public function index()
    {
        $eventos = $this->eventoService->getEventos();
        return view('eventos', ['eventos' => $eventos]);
    }

    // Endpoint para listar eventos (JSON) usado por la UI
    public function getEventos()
    {
        $eventos = $this->eventoService->getEventos();
        return response()->json($eventos);
    }

    /**
     * API: Listar todos los eventos con detalles
     */
    public function apiIndex(Request $request)
    {
        $query = Evento::query()->with(['mesas', 'participantes']);

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        // Filtro por área
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $eventos = $query->orderBy('fecha', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $eventos->map(function($evento) {
                return [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'fecha' => $evento->fecha->format('Y-m-d'),
                    'area' => $evento->area,
                    'capacidad_total' => $evento->capacidad_total,
                    'asientos_ocupados' => $evento->asientos_ocupados,
                    'asientos_disponibles' => $evento->asientos_disponibles,
                    'total_mesas' => $evento->mesas->count()
                ];
            })
        ]);
    }

    /**
     * API: Listar eventos activos (para selectores)
     */
    public function activos()
    {
        try {
            $hoy = Carbon::today();

            // Eventos donde la fecha está entre hoy y fecha_fin (o fecha_fin es null)
            $eventos = Evento::whereDate('fecha', '<=', $hoy)
                ->where(function($query) use ($hoy) {
                    $query->whereDate('fecha_fin', '>=', $hoy)
                          ->orWhereNull('fecha_fin');
                })
                ->orderBy('fecha', 'desc')
                ->get();

            return response()->json($eventos->map(function($evento) {
                return [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'fecha' => $evento->fecha->format('Y-m-d'),
                    'fecha_fin' => $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d') : null,
                    'area' => $evento->area
                ];
            }));

        } catch (\Exception $e) {
            Log::error("Error al obtener eventos activos: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener eventos activos'
            ], 500);
        }
    }

    /**
     * API: Obtener capacidad de todos los eventos con eager loading
     * Optimizado para evitar múltiples consultas (N+1 problem)
     */
    public function capacidadTodos()
    {
        try {
            // Cargar todos los eventos con sus mesas y participantes en una sola consulta
            $eventos = Evento::with(['mesas', 'participantes'])
                ->orderBy('fecha', 'desc')
                ->get();

            $data = $eventos->map(function($evento) {
                // Calcular capacidad total sumando capacidad de todas las mesas
                $capacidadTotal = $evento->mesas->sum('capacidad');

                // Contar participantes
                $ocupados = $evento->participantes->count();

                // Calcular libres
                $libres = $capacidadTotal - $ocupados;

                return [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'fecha' => $evento->fecha->format('Y-m-d'),
                    'fecha_fin' => $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d') : null,
                    'area' => $evento->area,
                    'capacidad_total' => $capacidadTotal,
                    'ocupados' => $ocupados,
                    'libres' => $libres,
                    'total_mesas' => $evento->mesas->count()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener capacidad de eventos: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener capacidad de eventos'
            ], 500);
        }
    }

    /**
     * API: Crear nuevo evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha',
            'area' => 'required|string|max:100'
        ]);

        try {
            $evento = Evento::create($validated);            return response()->json([
                'success' => true,
                'message' => 'Evento creado exitosamente',
                'data' => $evento
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error al crear evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el evento'
            ], 500);
        }
    }

    /**
     * API: Actualizar evento
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'fecha' => 'sometimes|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha'
        ]);

        try {
            $evento->update($validated);            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado exitosamente',
                'data' => $evento
            ]);

        } catch (\Exception $e) {
            Log::error("Error al actualizar evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el evento'
            ], 500);
        }
    }

    /**
     * API: Eliminar evento (con sus mesas y participantes)
     */
    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);

            // Contar datos relacionados para logging
            $totalMesas = $evento->mesas()->count();
            $totalParticipantes = $evento->participantes()->count();

            // Laravel eliminará automáticamente los registros relacionados si hay cascada,
            // pero lo haremos explícitamente para tener control
            DB::beginTransaction();

            try {
                // Eliminar entradas de evento de los participantes
                $participantesIds = $evento->participantes()->pluck('id');
                EntradaEvento::whereIn('participante_evento_id', $participantesIds)->delete();

                // Eliminar participantes
                $evento->participantes()->delete();

                // Eliminar mesas
                $evento->mesas()->delete();

                // Eliminar evento
                $evento->delete();

                DB::commit();

                Log::info("Evento eliminado: ID={$id}, Mesas={$totalMesas}, Participantes={$totalParticipantes}");

                return response()->json([
                    'success' => true,
                    'message' => 'Evento eliminado exitosamente',
                    'deleted' => [
                        'mesas' => $totalMesas,
                        'participantes' => $totalParticipantes
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Error al eliminar evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el evento'
            ], 500);
        }
    }

    /**
     * API: Exportar evento a PDF
     */
    public function exportar($id)
    {
        try {
            $evento = Evento::with([
                'mesas' => function($query) {
                    $query->orderByRaw('CAST(numero_mesa AS INTEGER)');
                },
                'participantes' => function($query) {
                    $query->orderBy('mesa_id')->orderBy('numero_silla');
                },
                'participantes.mesa',
                'participantes.entradaEvento'
            ])->findOrFail($id);

            // Preparar datos para la vista
            $ahora = Carbon::now();
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                      'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

            $fechaGeneracion = $dias[$ahora->dayOfWeek] . ', ' .
                              $ahora->day . ' de ' .
                              $meses[$ahora->month - 1] . ' de ' .
                              $ahora->year;

            $data = [
                'evento' => $evento,
                'fecha_generacion' => $fechaGeneracion,
                'hora_generacion' => $ahora->format('H:i:s')
            ];

            // Generar PDF
            $pdf = Pdf::loadView('pdf.reporte_evento', $data);
            $pdf->setPaper('a4', 'portrait');

            $nombreArchivo = 'Evento_' . str_replace(' ', '_', $evento->nombre) . '_' . date('Y-m-d') . '.pdf';

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            Log::error("Error al exportar evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar el evento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar evento a Excel (CSV)
     */
    public function exportarExcel($id)
    {
        try {
            $exporter = new \App\Exports\EventoExport($id);
            return $exporter->export();
        } catch (\Exception $e) {
            Log::error("Error al exportar evento a Excel: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar el evento a Excel'
            ], 500);
        }
    }

    /**
     * API: Obtener detalles de un evento
     */
    public function show($id)
    {
        $evento = Evento::with(['mesas', 'participantes.mesa', 'participantes.entradaEvento'])
                        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $evento->id,
                'nombre' => $evento->nombre,
                'fecha' => $evento->fecha->format('Y-m-d'),
                'area' => $evento->area,
                'capacidad_total' => $evento->capacidad_total,
                'asientos_ocupados' => $evento->asientos_ocupados,
                'asientos_disponibles' => $evento->asientos_disponibles,
                'mesas' => $evento->mesas->map(function($mesa) {
                    return [
                        'id' => $mesa->id,
                        'numero_mesa' => $mesa->numero_mesa,
                        'capacidad' => $mesa->capacidad,
                        'ocupadas' => $mesa->ocupadas,
                        'disponibles' => $mesa->disponibles
                    ];
                }),
                'participantes' => $evento->participantes->map(function($p) {
                    return [
                        'id' => $p->id,
                        'codigo_participante' => $p->codigo_participante,
                        'tipo' => $p->tipo,
                        'nombre' => $p->nombre,
                        'mesa_silla' => $p->mesa_silla,
                        'entrada_club' => $p->entradaEvento->entrada_club ?? false,
                        'entrada_evento' => $p->entradaEvento->entrada_evento ?? false
                    ];
                })
            ]
        ]);
    }

    // Legacy methods para compatibilidad con código existente
    public function getSocio(Request $request)
    {
        $dni = $request->get('dni');
        $socio = $this->eventoService->getSocio($dni);
        return response()->json($socio);
    }

    public function getParticipantes(Request $request)
    {
        $eventoId = $request->get('evento_id');
        $codigoSocio = $request->get('codigo_socio');

        if (!$eventoId) {
            return response()->json([]);
        }

        $participantes = $this->eventoService->getParticipantes($eventoId, $codigoSocio);
        return response()->json($participantes);
    }

    public function getParticipantesByArea(Request $request)
    {
        $area = $request->get('area');
        $codigoSocio = $request->get('codigo_socio');

        if (!$area) {
            return response()->json([]);
        }

        $participantes = $this->eventoService->getParticipantesByArea($area, $codigoSocio);
        return response()->json($participantes);
    }

    public function getParticipantesByCodigo(Request $request)
    {
        $codigo = $request->get('codigo_socio');
        if (!$codigo) {
            return response()->json([]);
        }

        $participantes = $this->eventoService->getParticipantesByCodigo($codigo);
        return response()->json($participantes);
    }
}
