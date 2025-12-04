# Script de actualización automática para servidor remoto XAMPP
# Ejecutar como: .\actualizar_remoto.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ACTUALIZACION DEL PROYECTO LARAVEL   " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Variables de configuración
$RUTA_PROYECTO_REMOTO = "C:\xampp\htdocs\laravel-docker-webApp\src"
$RUTA_PROYECTO_LOCAL = "C:\Users\DANIEL\laravel-docker\src"

# Paso 1: Verificar que estamos en la ruta local correcta
Write-Host "[1/8] Verificando ruta del proyecto local..." -ForegroundColor Yellow
if (!(Test-Path $RUTA_PROYECTO_LOCAL)) {
    Write-Host "ERROR: No se encuentra la ruta del proyecto local: $RUTA_PROYECTO_LOCAL" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Ruta verificada" -ForegroundColor Green
Write-Host ""

# Paso 2: Navegar al proyecto local y obtener cambios de Git
Write-Host "[2/8] Actualizando código desde Git..." -ForegroundColor Yellow
Set-Location $RUTA_PROYECTO_LOCAL
git pull origin main
if ($LASTEXITCODE -ne 0) {
    Write-Host "ADVERTENCIA: Error al hacer git pull. Continuando..." -ForegroundColor Yellow
}
Write-Host "✓ Git actualizado" -ForegroundColor Green
Write-Host ""

# Paso 3: Sincronizar archivos al servidor remoto (excluyendo archivos innecesarios)
Write-Host "[3/8] Copiando archivos al servidor remoto..." -ForegroundColor Yellow
Write-Host "Origen: $RUTA_PROYECTO_LOCAL" -ForegroundColor Gray
Write-Host "Destino: $RUTA_PROYECTO_REMOTO" -ForegroundColor Gray

# Crear carpeta de destino si no existe
if (!(Test-Path $RUTA_PROYECTO_REMOTO)) {
    New-Item -Path $RUTA_PROYECTO_REMOTO -ItemType Directory -Force | Out-Null
}

# Copiar archivos (excluyendo node_modules, vendor, storage, .git)
robocopy $RUTA_PROYECTO_LOCAL $RUTA_PROYECTO_REMOTO /MIR /XD node_modules vendor storage .git /XF .env database.sqlite /NFL /NDL /NJH /NJS /nc /ns /np
Write-Host "✓ Archivos copiados" -ForegroundColor Green
Write-Host ""

# Paso 4: Navegar al proyecto remoto
Write-Host "[4/8] Navegando al proyecto remoto..." -ForegroundColor Yellow
Set-Location $RUTA_PROYECTO_REMOTO
Write-Host "✓ En proyecto remoto" -ForegroundColor Green
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
