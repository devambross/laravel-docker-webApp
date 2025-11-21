<?php

namespace App\Http\Controllers;

use App\Models\EntradaClub;
use App\Services\SocioAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EntradaClubController extends Controller
{
    protected $socioAPI;

    public function __construct(SocioAPIService $socioAPI)
    {
        $this->socioAPI = $socioAPI;
    }

    /**
     * Registrar entrada al club
     *
     * Tipos de participantes:
     * - tipo='socio': Socios (####) y Familiares (####-XXX) de API
     * - tipo='invitado': Invitados temporales (####) NO permanentes
     */
    public function registrar(Request $request)
    {
        $validated = $request->validate([
            'codigo_participante' => 'required|string|max:50',
            'tipo' => 'required|in:socio,invitado',
            'nombre' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20',
            'area' => 'nullable|string|max:100'
        ]);

        try {
            $entrada = EntradaClub::create([
                'codigo_participante' => $validated['codigo_participante'],
                'tipo' => $validated['tipo'],
                'nombre' => $validated['nombre'],
                'dni' => $validated['dni'] ?? null,
                'area' => $validated['area'] ?? 'General',
                'fecha_hora' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entrada registrada exitosamente',
                'data' => $entrada
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error al registrar entrada: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la entrada'
            ], 500);
        }
    }

    /**
     * Buscar participantes (API + DB local) para entrada al club
     *
     * Fuentes:
     * - API Externa: Socios (####) y Familiares (####-XXX) permanentes
     * - DB Local: Historial incluyendo invitados temporales (####)
     */
    public function buscar(Request $request)
    {
        $validated = $request->validate([
            'termino' => 'required|string|min:1'
        ]);

        try {
            $termino = $validated['termino'];
            $resultados = [];

            // 1. Buscar en API externa (socios #### y familiares ####-XXX)
            $sociosAPI = $this->socioAPI->buscarSocios($termino);

            foreach ($sociosAPI as $socio) {
                $resultados[] = [
                    'codigo' => $socio['codigo'] ?? '',
                    'tipo' => 'socio',
                    'nombre' => $socio['nombre'] ?? '',
                    'dni' => $socio['dni'] ?? '',
                    'area' => 'General',
                    'fuente' => 'api'
                ];
            }

            // 2. Buscar en base de datos local (entrada_club histórico)
            $entradasDB = EntradaClub::buscar($termino)
                                    ->latest('fecha_hora')
                                    ->take(10)
                                    ->get();

            foreach ($entradasDB as $entrada) {
                // Evitar duplicados
                $existe = collect($resultados)->contains(function($r) use ($entrada) {
                    return $r['codigo'] === $entrada->codigo_participante;
                });

                if (!$existe) {
                    $resultados[] = [
                        'codigo' => $entrada->codigo_participante,
                        'tipo' => $entrada->tipo,
                        'nombre' => $entrada->nombre,
                        'dni' => $entrada->dni ?? '',
                        'area' => $entrada->area ?? 'General',
                        'fuente' => 'db'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $resultados
            ]);

        } catch (\Exception $e) {
            Log::error("Error al buscar participantes: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar participantes'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de entrada al club
     */
    public function estadisticas(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));

        try {
            $total = EntradaClub::whereDate('fecha_hora', $fecha)->count();
            $socios = EntradaClub::whereDate('fecha_hora', $fecha)
                                ->where('tipo', 'socio')
                                ->count();
            $invitados = EntradaClub::whereDate('fecha_hora', $fecha)
                                   ->where('tipo', 'invitado')
                                   ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'socios' => $socios,
                    'invitados' => $invitados,
                    'fecha' => $fecha
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
     * Listar entradas por fecha
     */
    public function listar(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));

        try {
            $entradas = EntradaClub::whereDate('fecha_hora', $fecha)
                                  ->orderBy('fecha_hora', 'desc')
                                  ->get();

            return response()->json([
                'success' => true,
                'data' => $entradas
            ]);

        } catch (\Exception $e) {
            Log::error("Error al listar entradas: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al listar entradas'
            ], 500);
        }
    }
}
