<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\EntradaClubController;
use App\Http\Controllers\EntradaEventoController;
use App\Services\SocioAPISimulada;

// Rutas de autenticación
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

// Rutas de vistas
Route::get('/registro', [PageController::class, 'registro'])->name('registro');
Route::get('/entrada', [PageController::class, 'entrada'])->name('entrada');
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos');

// ========== API ENDPOINTS ==========

// Eventos - API nueva
Route::prefix('api/eventos')->group(function () {
    Route::get('/', [EventoController::class, 'apiIndex']); // Lista de eventos
    Route::post('/', [EventoController::class, 'store']); // Crear evento
    Route::get('/{id}', [EventoController::class, 'show']); // Detalles de evento
    Route::put('/{id}', [EventoController::class, 'update']); // Actualizar evento
    Route::delete('/{id}', [EventoController::class, 'destroy']); // Eliminar evento
    Route::get('/{id}/exportar', [EventoController::class, 'exportar']); // Exportar evento a PDF
});

// Mesas
Route::prefix('api/mesas')->group(function () {
    Route::post('/', [MesaController::class, 'store']); // Crear mesa
    Route::get('/{id}', [MesaController::class, 'show']); // Obtener mesa específica
    Route::put('/{id}', [MesaController::class, 'update']); // Actualizar mesa
    Route::delete('/{id}', [MesaController::class, 'destroy']); // Eliminar mesa
    Route::get('/evento/{eventoId}', [MesaController::class, 'mesasEvento']); // Mesas de un evento
});

// Participantes
Route::prefix('api/participantes')->group(function () {
    Route::post('/', [ParticipanteController::class, 'store']); // Registrar participante
    Route::delete('/{id}', [ParticipanteController::class, 'destroy']); // Eliminar participante
    Route::put('/{id}/mesa', [ParticipanteController::class, 'actualizarMesa']); // Actualizar mesa/silla
    Route::get('/evento/{eventoId}', [ParticipanteController::class, 'participantesEvento']); // Participantes de evento
    Route::post('/buscar-socio', [ParticipanteController::class, 'buscarSocio']); // Buscar socio en API externa
    Route::get('/socio/{codigo}/familiares', [ParticipanteController::class, 'familiaresSocio']); // Familiares de socio (####-XXX)
    Route::get('/socio/{codigo}/invitados', [ParticipanteController::class, 'invitadosSocio']); // Alias para compatibilidad
});

// Entrada Club
Route::prefix('api/entrada-club')->group(function () {
    Route::post('/', [EntradaClubController::class, 'registrar']); // Registrar entrada
    Route::post('/buscar', [EntradaClubController::class, 'buscar']); // Buscar participantes (API + DB)
    Route::get('/estadisticas', [EntradaClubController::class, 'estadisticas']); // Estadísticas del día
    Route::get('/listar', [EntradaClubController::class, 'listar']); // Listar entradas del día
});

// Entrada Evento
Route::prefix('api/entrada-evento')->group(function () {
    Route::post('/buscar', [EntradaEventoController::class, 'buscarParticipante']); // Buscar participante en evento
    Route::post('/{participanteId}/entrada-club', [EntradaEventoController::class, 'marcarEntradaClub']); // Marcar entrada club
    Route::post('/{participanteId}/entrada-evento', [EntradaEventoController::class, 'marcarEntradaEvento']); // Marcar entrada evento
    Route::get('/{eventoId}/estadisticas', [EntradaEventoController::class, 'estadisticas']); // Estadísticas del evento
    Route::get('/{eventoId}/listar', [EntradaEventoController::class, 'listar']); // Listar participantes del evento
});

// Legacy endpoints (compatibilidad con código existente)
Route::get('/api/eventos', [EventoController::class, 'getEventos']);
Route::get('/api/socio', [EventoController::class, 'getSocio']);
Route::get('/api/participantes', [EventoController::class, 'getParticipantes']);
Route::get('/api/entrada/participantes', [EventoController::class, 'getParticipantesByArea']);
Route::get('/api/entrada/participantes_by_codigo', [EventoController::class, 'getParticipantesByCodigo']);

// API Simulada de Socios
Route::prefix('api/socios-externos')->group(function () {
    // Buscar socio con familiares
    Route::get('/buscar/{codigo}', function($codigo) {
        $resultado = SocioAPISimulada::buscarSocioConFamiliares($codigo);
        if (!$resultado) {
            return response()->json(['error' => 'Socio no encontrado'], 404);
        }
        return response()->json($resultado);
    });

    // Obtener nombre del socio (para invitados)
    Route::get('/nombre/{codigo}', function($codigo) {
        $nombre = SocioAPISimulada::getNombreSocio($codigo);
        if (!$nombre) {
            return response()->json(['error' => 'Socio no encontrado'], 404);
        }
        return response()->json(['codigo' => $codigo, 'nombre' => $nombre]);
    });

    // Verificar si existe un socio
    Route::get('/existe/{codigo}', function($codigo) {
        $existe = SocioAPISimulada::existeSocio($codigo);
        return response()->json(['existe' => $existe, 'codigo' => $codigo]);
    });
});

