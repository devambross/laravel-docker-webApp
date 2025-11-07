<?php

namespace App\Http\Controllers;

use App\Services\EventoService;
use Illuminate\Http\Request;

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

    // Obtener participantes por área (entrada) — el área puede ser un evento u otra actividad
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
}
