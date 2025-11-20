<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventoController;

Route::get('/registro', [PageController::class, 'registro']);
Route::get('/registro', [AuthController::class, 'registro'])->name('registro');
Route::get('/entrada', [PageController::class, 'entrada']);
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/api/eventos', [EventoController::class, 'getEventos']);
Route::get('/api/socio', [EventoController::class, 'getSocio']);
Route::get('/api/participantes', [EventoController::class, 'getParticipantes']);
Route::get('/api/entrada/participantes', [EventoController::class, 'getParticipantesByArea']);
Route::get('/api/entrada/participantes_by_codigo', [EventoController::class, 'getParticipantesByCodigo']);
Route::get('/',[AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
