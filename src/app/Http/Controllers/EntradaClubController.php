<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaDiaria;
use App\Models\Evento;
use App\Models\ParticipanteEvento;
use App\Services\SocioAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EntradaClubController extends Controller
{
    protected $socioAPI;

    public function __construct(SocioAPIService $socioAPI)
    {
        $this->socioAPI = $socioAPI;
    }

    /**
     * Listar todos los posibles asistentes del día
     * - Todos los socios y familiares del API
     * - Participantes de eventos del día (socios ya listados + invitados)
     */
    public function listar(Request $request)
    {
        try {
            $hoy = Carbon::today();
            Log::info("Fecha de hoy: " . $hoy->toDateString());

            $resultados = [];
            $codigosYaAsistieron = AsistenciaDiaria::codigosAsistieronHoy();

            // Obtener conteo de asistencias por código
            $conteoPorCodigo = [];

            // 1. Obtener TODOS los socios y familiares del API
            $todosLosSocios = $this->socioAPI->obtenerTodosSocios();

            foreach ($todosLosSocios as $socio) {
                $codigo = $socio['codigo'];
                $tipo = strpos($codigo, '-') !== false ? 'familiar' : 'socio';

                // Contar asistencias si ya asistió
                $vecesAsistio = in_array($codigo, $codigosYaAsistieron)
                    ? AsistenciaDiaria::contarAsistenciasHoy($codigo)
                    : 0;

                $resultados[$codigo] = [
                    'codigo_socio' => $codigo,
                    'tipo' => $tipo,
                    'nombre' => $socio['nombre'],
                    'dni' => $socio['dni'] ?? null,
                    'evento_id' => null,
                    'evento_nombre' => null,
                    'mesa_silla' => null,
                    'fuente' => 'api',
                    'ya_asistio_hoy' => in_array($codigo, $codigosYaAsistieron),
                    'veces_asistio_hoy' => $vecesAsistio
                ];
            }

            // 2. Obtener eventos activos HOY (fecha_inicio <= hoy <= fecha_fin)
            $eventosHoy = Evento::where(function($query) use ($hoy) {
                $query->whereDate('fecha', '<=', $hoy)
                      ->where(function($q) use ($hoy) {
                          $q->whereDate('fecha_fin', '>=', $hoy)
                            ->orWhereNull('fecha_fin');
                      });
            })->get();

            Log::info("Fecha de hoy: " . $hoy->toDateString());
            Log::info("Eventos activos hoy: " . $eventosHoy->count());

            foreach ($eventosHoy as $evt) {
                Log::info("Evento activo: {$evt->nombre}, Fecha inicio: {$evt->fecha}, Fecha fin: {$evt->fecha_fin}");
            }

            foreach ($eventosHoy as $evento) {
                // Obtener participantes del evento
                $participantes = ParticipanteEvento::where('evento_id', $evento->id)
                                                   ->with(['mesa'])
                                                   ->get();

                Log::info("Evento: {$evento->nombre}, Participantes: " . $participantes->count());

                foreach ($participantes as $p) {
                    $codigo = $p->codigo_participante;

                    Log::info("Procesando participante: {$codigo}");

                    $tipo = strpos($codigo, '-INV') !== false ? 'invitado' :
                           (strpos($codigo, '-') !== false ? 'familiar' : 'socio');

                    // Si es socio/familiar y ya está en la lista, actualizar con info del evento
                    if (isset($resultados[$codigo]) && ($tipo === 'socio' || $tipo === 'familiar')) {
                        Log::info("Actualizando socio/familiar {$codigo} con evento {$evento->nombre}");
                        $resultados[$codigo]['evento_id'] = $evento->id;
                        $resultados[$codigo]['evento_nombre'] = $evento->nombre;
                        $resultados[$codigo]['mesa_silla'] = $p->mesa_silla;
                    }
                    // Si es invitado, agregarlo solo si es evento de hoy
                    else if ($tipo === 'invitado') {
                        Log::info("Agregando invitado {$codigo} del evento {$evento->nombre}");

                        $vecesAsistio = in_array($codigo, $codigosYaAsistieron)
                            ? AsistenciaDiaria::contarAsistenciasHoy($codigo)
                            : 0;

                        $resultados[$codigo] = [
                            'codigo_socio' => $codigo,
                            'tipo' => 'invitado',
                            'nombre' => $p->nombre,
                            'dni' => $p->dni,
                            'evento_id' => $evento->id,
                            'evento_nombre' => $evento->nombre,
                            'mesa_silla' => $p->mesa_silla,
                            'fuente' => 'evento',
                            'ya_asistio_hoy' => in_array($codigo, $codigosYaAsistieron),
                            'veces_asistio_hoy' => $vecesAsistio
                        ];
                    } else {
                        Log::warning("Participante {$codigo} no coincide - Tipo: {$tipo}, En resultados: " . (isset($resultados[$codigo]) ? 'SI' : 'NO'));
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($resultados)
            ]);

        } catch (\Exception $e) {
            Log::error("Error al listar participantes para entrada club: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar participantes'
            ], 500);
        }
    }

    /**
     * Registrar asistencia diaria
     */
    public function registrarAsistencia(Request $request)
    {
        $validated = $request->validate([
            'codigo_socio' => 'required|string|max:50',
            'tipo' => 'required|in:socio,familiar,invitado',
            'nombre' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20',
            'evento_id' => 'nullable|integer|exists:eventos,id',
            'evento_nombre' => 'nullable|string|max:255'
        ]);

        try {
            $asistencia = AsistenciaDiaria::create([
                'codigo_socio' => $validated['codigo_socio'],
                'tipo' => $validated['tipo'],
                'nombre' => $validated['nombre'],
                'dni' => $validated['dni'] ?? null,
                'evento_id' => $validated['evento_id'] ?? null,
                'evento_nombre' => $validated['evento_nombre'] ?? null,
                'fecha_hora_entrada' => now(),
                'fecha' => Carbon::today()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia registrada exitosamente',
                'data' => $asistencia
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error al registrar asistencia: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la asistencia'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas del día
     */
    public function estadisticas(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));

        try {
            $stats = AsistenciaDiaria::estadisticasDelDia($fecha);

            return response()->json([
                'success' => true,
                'data' => $stats
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
     * Obtener reporte diario de asistencias
     */
    public function reporteDiario(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));
        $formato = $request->get('formato', 'json'); // json o pdf

        try {
            $fechaCarbon = Carbon::parse($fecha);
            $asistencias = AsistenciaDiaria::whereDate('fecha', $fecha)
                ->orderBy('fecha_hora_entrada')
                ->get();
            $stats = AsistenciaDiaria::estadisticasDelDia($fecha);

            // Si se solicita PDF
            if ($formato === 'pdf') {
                // Preparar datos para la vista
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                          'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                $fechaFormatted = $dias[$fechaCarbon->dayOfWeek] . ', ' .
                                 $fechaCarbon->day . ' de ' .
                                 $meses[$fechaCarbon->month - 1] . ' de ' .
                                 $fechaCarbon->year;

                $ahora = Carbon::now();
                $fechaGeneracion = $dias[$ahora->dayOfWeek] . ', ' .
                                  $ahora->day . ' de ' .
                                  $meses[$ahora->month - 1] . ' de ' .
                                  $ahora->year;

                $data = [
                    'asistencias' => $asistencias,
                    'estadisticas' => $stats,
                    'fecha' => $fecha,
                    'fecha_formatted' => $fechaFormatted,
                    'fecha_generacion' => $fechaGeneracion,
                    'hora_generacion' => $ahora->format('H:i:s'),
                    'es_hoy' => $fechaCarbon->isToday()
                ];

                // Generar PDF
                $pdf = Pdf::loadView('pdf.reporte_asistencias', $data);
                $pdf->setPaper('a4', 'portrait');

                $nombreArchivo = 'Reporte_Asistencias_' . $fecha . '.pdf';

                return $pdf->download($nombreArchivo);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'asistencias' => $asistencias,
                    'estadisticas' => $stats,
                    'fecha' => $fecha
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error al generar reporte diario: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte'
            ], 500);
        }
    }

    /**
     * Obtener los últimos días con registros de asistencias
     */
    public function ultimosDiasConRegistros(Request $request)
    {
        $limite = $request->get('limite', 3);

        try {
            // Obtener fechas únicas donde hay registros, limitadas y ordenadas
            $fechas = AsistenciaDiaria::select('fecha')
                ->distinct()
                ->orderBy('fecha', 'desc')
                ->limit($limite)
                ->pluck('fecha')
                ->map(function($fecha) {
                    return Carbon::parse($fecha)->format('Y-m-d');
                });

            return response()->json([
                'success' => true,
                'data' => $fechas
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener últimos días con registros: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las fechas'
            ], 500);
        }
    }
}
