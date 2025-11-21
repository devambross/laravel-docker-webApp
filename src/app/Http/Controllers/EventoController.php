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
     * API: Crear nuevo evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'area' => 'required|string|max:100'
        ]);

        try {
            $evento = Evento::create($validated);

            return response()->json([
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
            'fecha' => 'sometimes|date'
        ]);

        try {
            $evento->update($validated);

            return response()->json([
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
                    $query->orderBy('numero_mesa');
                },
                'participantes' => function($query) {
                    $query->orderBy('mesa_id')->orderBy('numero_silla');
                },
                'participantes.entradaEvento'
            ])->findOrFail($id);

            // Generar HTML para el PDF
            $html = $this->generarHTMLParaPDF($evento);

            // Por ahora retornamos HTML, luego implementaremos la generación de PDF
            // cuando instalemos una librería como DomPDF o wkhtmltopdf
            return response($html, 200)->header('Content-Type', 'text/html');

        } catch (\Exception $e) {
            Log::error("Error al exportar evento: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar el evento'
            ], 500);
        }
    }

    /**
     * Generar HTML para el informe del evento
     */
    private function generarHTMLParaPDF($evento)
    {
        $fechaExportacion = now()->format('d/m/Y H:i');

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Informe - {$evento->nombre}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 3px solid #78B548;
                    padding-bottom: 20px;
                }
                .header h1 {
                    color: #78B548;
                    margin: 0;
                }
                .info-section {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                }
                .info-row {
                    margin: 8px 0;
                }
                .info-label {
                    font-weight: bold;
                    color: #555;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th {
                    background: #78B548;
                    color: white;
                    padding: 10px;
                    text-align: left;
                    font-weight: 600;
                }
                td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background: #f8f9fa;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #ddd;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                }
                .summary {
                    display: flex;
                    justify-content: space-around;
                    margin: 20px 0;
                }
                .summary-box {
                    text-align: center;
                    padding: 15px;
                    background: #e8f5e9;
                    border-radius: 8px;
                    flex: 1;
                    margin: 0 10px;
                }
                .summary-number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #78B548;
                }
                .summary-label {
                    color: #555;
                    font-size: 14px;
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>INFORME DE EVENTO</h1>
                <p>Generado el: {$fechaExportacion}</p>
            </div>

            <div class='info-section'>
                <h2 style='color: #78B548; margin-top: 0;'>Información del Evento</h2>
                <div class='info-row'>
                    <span class='info-label'>Nombre:</span> {$evento->nombre}
                </div>
                <div class='info-row'>
                    <span class='info-label'>Fecha:</span> {$evento->fecha->format('d/m/Y')}
                </div>
                <div class='info-row'>
                    <span class='info-label'>Área:</span> {$evento->area}
                </div>
                <div class='info-row'>
                    <span class='info-label'>Capacidad Total:</span> {$evento->capacidad_total} asientos
                </div>
            </div>

            <div class='summary'>
                <div class='summary-box'>
                    <div class='summary-number'>{$evento->mesas->count()}</div>
                    <div class='summary-label'>Mesas</div>
                </div>
                <div class='summary-box'>
                    <div class='summary-number'>{$evento->participantes->count()}</div>
                    <div class='summary-label'>Participantes</div>
                </div>
                <div class='summary-box'>
                    <div class='summary-number'>{$evento->asientos_disponibles}</div>
                    <div class='summary-label'>Asientos Disponibles</div>
                </div>
            </div>

            <h2 style='color: #78B548;'>Lista de Participantes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Tipo</th>
                        <th>Mesa - Silla</th>
                        <th>N° Recibo</th>
                        <th>N° Boleta</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($evento->participantes as $participante) {
            $mesaSilla = $participante->mesa ?
                "Mesa {$participante->mesa->numero_mesa} - Silla {$participante->numero_silla}" :
                'Sin asignar';

            $html .= "
                    <tr>
                        <td>{$participante->codigo_participante}</td>
                        <td>{$participante->nombre}</td>
                        <td>{$participante->dni}</td>
                        <td>" . ucfirst($participante->tipo) . "</td>
                        <td>{$mesaSilla}</td>
                        <td>{$participante->n_recibo}</td>
                        <td>{$participante->n_boleta}</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>

            <h2 style='color: #78B548; margin-top: 30px;'>Distribución de Mesas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mesa</th>
                        <th>Capacidad</th>
                        <th>Ocupadas</th>
                        <th>Disponibles</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($evento->mesas as $mesa) {
            $html .= "
                    <tr>
                        <td>Mesa {$mesa->numero_mesa}</td>
                        <td>{$mesa->capacidad}</td>
                        <td>{$mesa->ocupadas}</td>
                        <td>{$mesa->disponibles}</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>

            <div class='footer'>
                <p>Sistema de Gestión de Eventos - Rinconada Country Club</p>
                <p>Documento generado automáticamente</p>
            </div>
        </body>
        </html>";

        return $html;
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
