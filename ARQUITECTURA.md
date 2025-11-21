# Arquitectura del Sistema - Gestión de Eventos y Asistencia

## Estructura de Códigos del Sistema

### Códigos de Socios Titulares
- **Formato:** 4 dígitos numéricos (ej: `0001`, `0234`, `1456`)
- **Permanencia:** Permanentes en el sistema
- **Fuente:** API externa de socios

### Códigos de Familiares
- **Formato:** 4 dígitos + guion + letras (ej: `0001-A`, `0234-B`, `1456-FAM`)
- **Vinculación:** Los primeros 4 dígitos corresponden al socio titular
- **Permanencia:** Permanentes mientras mantengan relación con el socio
- **Fuente:** API externa de socios

### Códigos de Invitados Temporales (No Eventos)
- **Formato:** 4 dígitos numéricos (ej: `0500`, `0789`)
- **Permanencia:** **NO permanentes** - Son temporales
- **Propósito:** Acceso a actividades específicas o reservas con socios
- **Fuente:** Registros externos/temporales (NO están en API externa)
- **Almacenamiento:** Solo en `entrada_club` cuando ingresan

### Códigos de Participantes en Eventos
- **Socios/Familiares:** Mantienen su código original (`0001` o `0001-A`)
- **Invitados de Evento:** Código socio + sufijo (ej: `0001-INV1`, `0001-INV2`)
- **Almacenamiento:** Tabla local `participantes_evento`

## 1. Fuentes de Datos

### 1.1 API Externa (Solo Lectura)
**Endpoint Base:** `/api/socios`

**Datos que proporciona:**
- Información de socios titulares (####)
- Información de familiares (####-XXX)
- **NO incluye:** Eventos, mesas, sillas, asignaciones, invitados temporales

**Endpoints:**
```
GET /api/socios - Lista todos los socios y familiares
GET /api/socios/{codigo} - Datos de un socio o familiar específico (#### o ####-XXX)
GET /api/socios/{codigo}/familiares - Familiares de un socio titular (retorna ####-XXX)
```

### 1.2 Base de Datos Local (Laravel)
**Propósito:** Almacenar toda la gestión de eventos

**Tablas necesarias:**

```sql
-- Eventos creados
CREATE TABLE eventos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    area VARCHAR(100) NOT NULL,
    capacidad_total INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Mesas por evento
CREATE TABLE mesas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    evento_id BIGINT NOT NULL,
    numero_mesa VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    UNIQUE KEY (evento_id, numero_mesa)
);

-- Participantes registrados en eventos
CREATE TABLE participantes_evento (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    evento_id BIGINT NOT NULL,
    mesa_id BIGINT NULL,
    numero_silla INT NULL,
    tipo ENUM('socio', 'invitado') NOT NULL,
    codigo_socio VARCHAR(50) NOT NULL, -- Código del socio titular (####) o familiar (####-XXX)
    codigo_participante VARCHAR(50) NOT NULL, -- Socios: #### o ####-XXX | Invitados evento: ####-INV1
    dni VARCHAR(20) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    n_recibo VARCHAR(100) NULL,
    n_boleta VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON DELETE SET NULL,
    UNIQUE KEY (evento_id, mesa_id, numero_silla),
    UNIQUE KEY (evento_id, codigo_participante)
);

-- Registro de entrada al CLUB (general, no a eventos)
CREATE TABLE entrada_club (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    codigo_participante VARCHAR(50) NOT NULL, -- ####, ####-XXX (API) o #### temporal (NO API)
    tipo ENUM('socio', 'invitado') NOT NULL, -- 'socio' = API (####/####-XXX), 'invitado' = temporal (####)
    nombre VARCHAR(255) NOT NULL,
    dni VARCHAR(20) NULL,
    fecha_hora TIMESTAMP NOT NULL,
    area VARCHAR(100) NULL, -- Actividad o área visitada
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_codigo (codigo_participante)
);

-- Registro de entrada a EVENTOS específicos
CREATE TABLE entrada_evento (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    participante_evento_id BIGINT NOT NULL,
    entrada_club BOOLEAN DEFAULT 0, -- Si ya entró al club
    entrada_evento BOOLEAN DEFAULT 0, -- Si ya entró al evento específico
    fecha_hora_club TIMESTAMP NULL,
    fecha_hora_evento TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (participante_evento_id) REFERENCES participantes_evento(id) ON DELETE CASCADE,
    UNIQUE KEY (participante_evento_id)
);
```

## 2. Flujo de Datos por Módulo

### 2.1 REGISTRO (Pestaña Registro)

**Propósito:** Crear eventos y registrar participantes con asignación de mesas/sillas

**Flujo:**
1. **Crear Evento:**
   - Usuario crea evento (nombre, fecha, área)
   - Se guarda en tabla `eventos` local
   - Se inicializa capacidad en 0

2. **Crear Mesa:**
   - Usuario crea mesa para un evento
   - Se guarda en tabla `mesas` con referencia al evento
   - Se actualiza capacidad total del evento

3. **Registrar Participante:**
   - Usuario ingresa código de socio
   - Sistema hace llamada a API externa: `GET /api/socios/{codigo}`
   - Obtiene datos del socio (DNI, nombre)
   - Usuario selecciona evento, mesa, silla
   - Se guarda en tabla `participantes_evento`
   - Se crea registro en tabla `entrada_evento` con ambos flags en 0
   - Si es invitado: código será S001-INV1, S001-INV2, etc.

**APIs Locales Necesarias:**
```
POST   /api/eventos/crear
PUT    /api/eventos/{id}/editar
DELETE /api/eventos/{id}/eliminar
GET    /api/eventos/listar

POST   /api/mesas/crear
PUT    /api/mesas/{id}/editar
DELETE /api/mesas/{id}/eliminar
GET    /api/mesas/evento/{evento_id}

POST   /api/participantes/registrar
DELETE /api/participantes/{id}/eliminar
GET    /api/participantes/evento/{evento_id}
GET    /api/participantes/buscar?codigo={codigo}
```

### 2.2 ENTRADA CLUB (Pestaña Entrada)

**Propósito:** Registro de asistencia general al club (cualquier persona: socios, familiares, invitados temporales)

**Tipos de Participantes:**
- **Socios (####):** Permanentes en API externa
- **Familiares (####-XXX):** Permanentes en API externa, vinculados a socio titular
- **Invitados Temporales (####):** NO en API, registrados externamente, no permanentes

**Flujo:**
1. Usuario busca por código o nombre
2. Sistema consulta EN PARALELO:
   - **API externa:** Socios (####) y Familiares (####-XXX)
   - **BD local `entrada_club`:** Historial incluyendo invitados temporales (####)
3. Sistema combina resultados y elimina duplicados
4. Muestra lista de coincidencias
5. Usuario selecciona o registra nuevo invitado temporal
6. Se registra en tabla `entrada_club` con timestamp
7. **Si la persona también está en un evento:** Se actualiza `entrada_evento.entrada_club = 1`

**APIs Locales Necesarias:**
```
POST /api/entrada-club/buscar
  {
    "termino": "0001" // Código o nombre
  }
  Retorna: [ 
    { codigo: "0001", tipo: "socio", nombre: "...", fuente: "api" },
    { codigo: "0001-A", tipo: "socio", nombre: "...", fuente: "api" },
    { codigo: "0500", tipo: "invitado", nombre: "...", fuente: "db" }
  ]

POST /api/entrada-club/registrar
  {
    "codigo_participante": "0001" | "0001-A" | "0500",
    "tipo": "socio" | "invitado", // socio = API, invitado = temporal
    "nombre": "...",
    "dni": "...",
    "area": "Piscina"
  }

GET /api/entrada-club/estadisticas?fecha={fecha}
GET /api/entrada-club/export-pdf?fecha={fecha}&area={area}
```

### 2.3 ENTRADA EVENTO (Pestaña Eventos)

**Propósito:** Control de asistencia específico a eventos creados en Registro

**Flujo:**
1. Usuario selecciona un evento del dropdown
2. Sistema carga participantes SOLO de ese evento desde `participantes_evento`
3. Muestra tabla con información de mesa/silla
4. Usuario marca asistencia con dos checkboxes:
   - **Entrada Club:** Registra que llegó al club
   - **Entrada Evento:** Registra que entró al evento específico
5. Se actualiza tabla `entrada_evento`

**APIs Locales Necesarias:**
```
GET /api/eventos/listar-activos
GET /api/entrada/evento/{evento_id}/participantes
POST /api/entrada/evento/registrar
  {
    "participante_evento_id": 123,
    "entrada_club": true,
    "entrada_evento": true
  }

GET /api/entrada/evento/{evento_id}/estadisticas
GET /api/entrada/evento/{evento_id}/export-pdf
```

**Estructura de Respuesta:**
```json
{
  "evento": {
    "id": 1,
    "nombre": "Cena Anual 2025",
    "fecha": "2025-12-15",
    "area": "Eventos Sociales"
  },
  "participantes": [
    {
      "id": 1,
      "codigo": "S001",
      "tipo": "socio",
      "dni": "12345678",
      "nombre": "Juan Pérez García",
      "mesa": {
        "numero": "Mesa 1",
        "silla": 1
      },
      "entrada": {
        "entrada_club": false,
        "entrada_evento": false,
        "fecha_hora_club": null,
        "fecha_hora_evento": null
      }
    },
    {
      "id": 2,
      "codigo": "S001-INV1",
      "tipo": "invitado",
      "dni": "87654321",
      "nombre": "María López Martínez",
      "codigo_socio_anfitrion": "S001",
      "mesa": {
        "numero": "Mesa 1",
        "silla": 2
      },
      "entrada": {
        "entrada_club": false,
        "entrada_evento": false,
        "fecha_hora_club": null,
        "fecha_hora_evento": null
      }
    }
  ],
  "estadisticas": {
    "total": 2,
    "entrada_club": 0,
    "entrada_evento": 0
  }
}
```

## 3. Migraciones de Laravel

### Comando para crear migraciones:
```bash
php artisan make:migration create_eventos_table
php artisan make:migration create_mesas_table
php artisan make:migration create_participantes_evento_table
php artisan make:migration create_entrada_club_table
php artisan make:migration create_entrada_evento_table
```

## 4. Modelos Eloquent

```
app/Models/Evento.php
app/Models/Mesa.php
app/Models/ParticipanteEvento.php
app/Models/EntradaClub.php
app/Models/EntradaEvento.php
```

## 5. Controladores

```
app/Http/Controllers/EventoController.php
app/Http/Controllers/MesaController.php
app/Http/Controllers/ParticipanteController.php
app/Http/Controllers/EntradaClubController.php
app/Http/Controllers/EntradaEventoController.php
app/Http/Controllers/SocioAPIController.php (proxy a API externa)
```

## 6. Servicios

```
app/Services/SocioAPIService.php - Maneja llamadas a API externa
app/Services/EventoService.php - Lógica de negocio de eventos
app/Services/EntradaService.php - Lógica de control de asistencia
app/Services/PDFExportService.php - Generación de PDFs
```

## 7. Rutas (routes/web.php y routes/api.php)

Ver archivos de rutas para implementación completa.

## 8. Validaciones Importantes

1. **Al registrar participante:**
   - Validar que el código de socio exista en API externa
   - Validar que la silla no esté ocupada en la mesa
   - Validar capacidad de la mesa

2. **Al editar mesa:**
   - No permitir reducir capacidad por debajo de sillas ocupadas
   - Actualizar capacidad total del evento

3. **Al eliminar evento:**
   - Cascade delete a mesas y participantes

4. **Al marcar entrada:**
   - Si marca "Entrada Evento", automáticamente marcar "Entrada Club"
   - Registrar timestamps

## 9. Integración Frontend-Backend

### JavaScript (AJAX Calls):
```javascript
// Ejemplo en registro_scripts.blade.php
async function buscarSocio(codigo) {
    const response = await fetch(`/api/socios/${codigo}`);
    const socio = await response.json();
    
    // Llenar formulario con datos del socio
    document.getElementById('dni').value = socio.dni;
    document.getElementById('nombre').value = socio.nombre;
}

async function registrarParticipante(formData) {
    const response = await fetch('/api/participantes/registrar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    });
    
    return await response.json();
}
```

## 10. Consideraciones Adicionales

1. **Cache:** Cachear respuestas de API externa para mejorar rendimiento
2. **Validación:** Middleware para validar tokens CSRF
3. **Autorización:** Middleware para verificar que usuario esté autenticado
4. **Logs:** Registrar todas las operaciones de entrada/salida
5. **Backup:** Backup automático de BD local
6. **Sincronización:** Job para verificar que códigos de socios en BD local aún existen en API
