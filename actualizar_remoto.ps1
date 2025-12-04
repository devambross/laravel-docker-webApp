# Script de actualización automática para servidor remoto XAMPP
# Ejecutar como: .\actualizar_remoto.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ACTUALIZACION DEL PROYECTO LARAVEL   " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Variables de configuracion
$RUTA_PROYECTO = "C:\xampp\htdocs\laravel-docker-webApp\src"

# Paso 1: Git pull
Write-Host "[1/6] Actualizando codigo desde Git..." -ForegroundColor Yellow
Set-Location "C:\xampp\htdocs\laravel-docker-webApp"
git pull origin main
if ($LASTEXITCODE -ne 0) {
    Write-Host "ADVERTENCIA: Error al hacer git pull. Continuando..." -ForegroundColor Yellow
}
Write-Host "OK Git actualizado" -ForegroundColor Green
Write-Host ""

# Paso 2: Navegar al proyecto
Write-Host "[2/6] Navegando al proyecto..." -ForegroundColor Yellow
Set-Location $RUTA_PROYECTO
Write-Host "OK En proyecto remoto" -ForegroundColor Green
Write-Host ""

# Paso 5: Instalar/actualizar dependencias de Composer
Write-Host "[5/8] Actualizando dependencias de Composer..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Fallo al instalar dependencias de Composer" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Composer actualizado" -ForegroundColor Green
Write-Host ""

# Paso 6: Ejecutar migraciones
Write-Host "[6/8] Ejecutando migraciones de base de datos..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ADVERTENCIA: Error en migraciones. Revisa la base de datos." -ForegroundColor Yellow
}
Write-Host "✓ Migraciones ejecutadas" -ForegroundColor Green
Write-Host ""

# Paso 7: Limpiar caché de Laravel
Write-Host "[7/8] Limpiando caché de Laravel..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
Write-Host "✓ Caché limpiado" -ForegroundColor Green
Write-Host ""

# Paso 8: Optimizar para producción
Write-Host "[8/8] Optimizando para producción..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "✓ Optimización completa" -ForegroundColor Green
Write-Host ""

# Resumen final
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ACTUALIZACION COMPLETADA CON EXITO   " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "El proyecto está actualizado en:" -ForegroundColor White
Write-Host "http://190.119.16.135" -ForegroundColor Cyan
Write-Host ""
Write-Host "Presiona cualquier tecla para salir..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
