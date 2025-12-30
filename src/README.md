# Laravel Docker WebApp

Sistema de gestión de eventos y registro de asistencias para club desarrollado con Laravel 12 y Docker.

## Características

- Gestión de eventos
- Registro de participantes
- Control de asistencias diarias
- Sistema de mesas y reservas
- Registro de entrada al club
- Exportación de datos a Excel/PDF
- API simulada de socios
- Autocompletado de datos de socios

## Requisitos Previos

- Docker
- Docker Compose
- Git

## Instalación y Despliegue

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd laravel-docker-webApp
```

### 2. Iniciar los contenedores Docker

```bash
docker-compose up -d
```

### 3. Instalar dependencias de Composer

```bash
docker exec -it laravel-app composer install
```

### 4. Configurar el archivo de entorno

```bash
docker exec -it laravel-app cp .env.example .env
```

### 5. Generar la clave de aplicación

```bash
docker exec -it laravel-app php artisan key:generate
```

### 6. Crear y configurar la base de datos SQLite

```bash
docker exec -it laravel-app touch database/database.sqlite
docker exec -it laravel-app chmod 664 database/database.sqlite
docker exec -it laravel-app chown www-data:www-data database/database.sqlite
```

### 7. Configurar permisos de directorios

```bash
docker exec -it laravel-app chmod -R 775 storage bootstrap/cache
docker exec -it laravel-app chown -R www-data:www-data storage bootstrap/cache
```

### 8. Ejecutar las migraciones

```bash
docker exec -it laravel-app php artisan migrate
```

### 9. (Opcional) Cargar datos de prueba

```bash
docker exec -it laravel-app php artisan db:seed --class=TestDataSeeder
```

### 10. Acceder a la aplicación

Abrir el navegador y visitar: [http://localhost:8080](http://localhost:8080)

## Comandos Útiles

### Detener los contenedores
```bash
docker-compose down
```

### Ver logs del contenedor
```bash
docker logs laravel-app -f
```

### Acceder al contenedor
```bash
docker exec -it laravel-app bash
```

### Limpiar caché
```bash
docker exec -it laravel-app php artisan cache:clear
docker exec -it laravel-app php artisan config:clear
docker exec -it laravel-app php artisan route:clear
docker exec -it laravel-app php artisan view:clear
```

### Ejecutar tests
```bash
docker exec -it laravel-app php artisan test
```

## Estructura del Proyecto

Para más información sobre la arquitectura y funcionalidades implementadas, consulta:

- [ARQUITECTURA.md](../ARQUITECTURA.md) - Estructura y arquitectura del proyecto
- [FUNCIONALIDADES_IMPLEMENTADAS.md](../FUNCIONALIDADES_IMPLEMENTADAS.md) - Lista de funcionalidades
- [FUNCIONALIDADES_EVENTOS.md](../FUNCIONALIDADES_EVENTOS.md) - Gestión de eventos
- [EJEMPLOS_API.md](../EJEMPLOS_API.md) - Ejemplos de uso de la API
- [TESTING.md](../TESTING.md) - Guía de testing

## Problemas Comunes

### Error: "Permission denied" en storage/logs
Ejecutar:
```bash
docker exec -it laravel-app chmod -R 775 storage
docker exec -it laravel-app chown -R www-data:www-data storage
```

### Error: "Database file does not exist"
Ejecutar:
```bash
docker exec -it laravel-app touch database/database.sqlite
docker exec -it laravel-app chmod 664 database/database.sqlite
docker exec -it laravel-app chown www-data:www-data database/database.sqlite
```

### Error: "Failed to open vendor/autoload.php"
Ejecutar:
```bash
docker exec -it laravel-app composer install
```

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
