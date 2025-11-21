# Guía de Instalación y Configuración

## Requisitos Previos
- PHP 8.1+
- Composer
- MySQL/MariaDB
- Node.js y NPM (para assets)

## Instalación

### 1. Clonar el Repositorio
```bash
git clone <repository-url>
cd laravel-docker
```

### 2. Configurar Variables de Entorno
```bash
cp src/.env.example src/.env
```

Editar `src/.env` con tus configuraciones:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña

# URL de la API externa de socios
SOCIO_API_URL=http://tu-api-externa.com
```

### 3. Instalar Dependencias
```bash
cd src
composer install
npm install
```

### 4. Generar Application Key
```bash
php artisan key:generate
```

### 5. Ejecutar Migraciones
```bash
php artisan migrate
```

Esto creará las siguientes tablas:
- `eventos` - Eventos del club
- `mesas` - Mesas de los eventos
- `participantes_evento` - Participantes registrados en eventos
- `entrada_club` - Registro de entradas al club
- `entrada_evento` - Control de asistencia a eventos

### 6. Compilar Assets (Opcional)
```bash
npm run dev
# O para producción:
npm run build
```

### 7. Iniciar el Servidor
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Configuración de Docker (Opcional)

Si prefieres usar Docker:

```bash
docker-compose up -d
```

## Estructura de la Base de Datos

### Tabla: eventos
- `id` - ID del evento
- `nombre` - Nombre del evento
- `fecha` - Fecha del evento
- `area` - Área donde se realiza
- `capacidad_total` - Capacidad total (suma de todas las mesas)

### Tabla: mesas
- `id` - ID de la mesa
- `evento_id` - ID del evento (FK)
- `numero_mesa` - Número de la mesa
- `capacidad` - Capacidad de la mesa

### Tabla: participantes_evento
- `id` - ID del participante
- `evento_id` - ID del evento (FK)
- `mesa_id` - ID de la mesa asignada (FK, nullable)
- `numero_silla` - Número de silla asignada
- `tipo` - 'socio' o 'invitado'
- `codigo_socio` - Código del socio (o anfitrión si es invitado)
- `codigo_participante` - Código único (ej: S001, S001-INV1)
- `dni` - DNI del participante
- `nombre` - Nombre completo
- `n_recibo` - Número de recibo
- `n_boleta` - Número de boleta

### Tabla: entrada_club
- `id` - ID de la entrada
- `codigo_participante` - Código del participante
- `tipo` - 'socio' o 'invitado'
- `nombre` - Nombre completo
- `dni` - DNI
- `area` - Área visitada
- `fecha_hora` - Timestamp de entrada

### Tabla: entrada_evento
- `id` - ID del registro
- `participante_evento_id` - ID del participante (FK)
- `entrada_club` - Booleano: ingresó al club
- `entrada_evento` - Booleano: ingresó al evento
- `fecha_hora_club` - Timestamp de entrada al club
- `fecha_hora_evento` - Timestamp de entrada al evento

## API Externa de Socios

El sistema se integra con una API externa para obtener información de socios y familiares. Configura la URL en `.env`:

```env
SOCIO_API_URL=http://tu-api-externa.com
```

### Estructura de Códigos:

**Socios Titulares:**
- Formato: 4 dígitos numéricos (ej: `0001`, `0234`, `1456`)
- Permanentes en el sistema
- Retornados por API externa

**Familiares de Socios:**
- Formato: 4 dígitos + guion + letras (ej: `0001-A`, `0234-B`, `1456-FAM`)
- Vinculados al socio titular (primeros 4 dígitos)
- Permanentes mientras mantengan relación
- Retornados por API externa

**Invitados Temporales (No Eventos):**
- Formato: 4 dígitos numéricos (ej: `0500`, `0789`)
- **NO permanentes** - Solo para actividades/reservas específicas
- Registrados externamente, NO en API
- Almacenados en `entrada_club` cuando ingresan

### Endpoints Esperados de la API Externa:

```
GET /api/socios/{codigo}           - Obtener socio o familiar por código
GET /api/socios?dni={dni}          - Obtener socio o familiar por DNI
GET /api/socios?q={termino}        - Buscar socios y familiares
GET /api/socios/{codigo}/familiares - Obtener familiares de un socio titular
```

### Respuestas Esperadas:

**Socio Titular:**
```json
{
  "codigo": "0001",
  "tipo": "socio",
  "nombre": "Juan Pérez",
  "dni": "12345678",
  "email": "juan@example.com",
  "telefono": "999888777"
}
```

**Familiar:**
```json
{
  "codigo": "0001-A",
  "tipo": "familiar",
  "codigo_socio": "0001",
  "nombre": "María Pérez",
  "dni": "87654321",
  "parentesco": "Esposa"
}
```

## Endpoints de la Aplicación

Ver `ARQUITECTURA.md` para la lista completa de endpoints.

### Principales:
- `POST /api/eventos` - Crear evento
- `POST /api/mesas` - Crear mesa
- `POST /api/participantes` - Registrar participante
- `POST /api/entrada-club` - Registrar entrada al club
- `POST /api/entrada-evento/{id}/entrada-club` - Marcar entrada al club
- `POST /api/entrada-evento/{id}/entrada-evento` - Marcar entrada al evento

## Troubleshooting

### Error: "SQLSTATE[HY000] [2002] Connection refused"
Verifica que MySQL esté corriendo y las credenciales en `.env` sean correctas.

### Error: "The POST method is not supported"
Asegúrate de incluir el CSRF token en tus peticiones AJAX:
```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### Error: "Class 'App\Models\Mesa' not found"
Ejecuta:
```bash
composer dump-autoload
```

### La API externa no responde
Verifica:
1. `SOCIO_API_URL` en `.env`
2. Que la API externa esté accesible
3. Los logs en `storage/logs/laravel.log`

## Mantenimiento

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ver logs
```bash
tail -f storage/logs/laravel.log
```

### Backup de base de datos
```bash
mysqldump -u usuario -p nombre_base_datos > backup.sql
```
