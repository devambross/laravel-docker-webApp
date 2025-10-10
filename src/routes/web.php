<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/registro', [PageController::class, 'registro']);
Route::get('/registro', [AuthController::class, 'registro'])->name('registro');
Route::get('/entrada', [PageController::class, 'entrada']);
Route::get('/eventos', [PageController::class, 'eventos']);
Route::get('/',[AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
