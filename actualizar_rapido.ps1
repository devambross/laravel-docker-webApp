# Script de actualización rápida para desarrollo
# Ejecutar como: .\actualizar_rapido.ps1

Write-Host "Actualizando proyecto..." -ForegroundColor Cyan

$RUTA_PROYECTO = "C:\xampp\htdocs\laravel-docker-webApp\src"

# Git pull
Set-Location "C:\xampp\htdocs\laravel-docker-webApp"
Write-Host "1. Actualizando desde Git..." -ForegroundColor Yellow
git pull origin main

# Actualizar dependencias y limpiar cache
Set-Location $RUTA_PROYECTO
Write-Host "2. Limpiando cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

Write-Host ""
Write-Host "Actualizacion completa" -ForegroundColor Green
Write-Host "http://190.119.16.135" -ForegroundColor Cyan
