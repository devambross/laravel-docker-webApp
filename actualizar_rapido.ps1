# Script de actualización rápida para desarrollo
# Ejecutar como: .\actualizar_rapido.ps1

Write-Host "Actualizando proyecto..." -ForegroundColor Cyan

$RUTA_PROYECTO_REMOTO = "C:\xampp\htdocs\laravel-docker-webApp\src"
$RUTA_PROYECTO_LOCAL = "C:\Users\DANIEL\laravel-docker\src"

# Git pull local
Set-Location $RUTA_PROYECTO_LOCAL
Write-Host "1. Actualizando desde Git..." -ForegroundColor Yellow
git pull origin main

# Copiar archivos
Write-Host "2. Copiando archivos..." -ForegroundColor Yellow
robocopy $RUTA_PROYECTO_LOCAL $RUTA_PROYECTO_REMOTO /MIR /XD node_modules vendor storage .git /XF .env database.sqlite /NFL /NDL /NJH /NJS /nc /ns /np

# Actualizar dependencias y limpiar caché
Set-Location $RUTA_PROYECTO_REMOTO
Write-Host "3. Actualizando dependencias..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

Write-Host "4. Migraciones..." -ForegroundColor Yellow
php artisan migrate --force

Write-Host "5. Limpiando caché..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

Write-Host ""
Write-Host "✓ Actualización completa" -ForegroundColor Green
Write-Host "http://190.119.16.135" -ForegroundColor Cyan
