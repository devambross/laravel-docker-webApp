# Formatos de Códigos del Sistema

## Resumen Visual

```
┌─────────────────────────────────────────────────────────────────┐
│                    TIPOS DE CÓDIGOS                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  📋 SOCIOS TITULARES                                           │
│  ├─ Formato:      #### (4 dígitos numéricos)                   │
│  ├─ Ejemplos:     0001, 0234, 1456                            │
│  ├─ Fuente:       API Externa                                  │
│  ├─ Permanencia:  Permanente                                   │
│  └─ Uso:          Club + Eventos                               │
│                                                                 │
│  👨‍👩‍👧‍👦 FAMILIARES DE SOCIOS                                        │
│  ├─ Formato:      ####-XXX (4 dígitos + guion + letras)       │
│  ├─ Ejemplos:     0001-A, 0234-B, 1456-FAM                    │
│  ├─ Fuente:       API Externa                                  │
│  ├─ Permanencia:  Permanente (vinculado a socio)              │
│  ├─ Vinculación:  Primeros 4 dígitos = código socio titular   │
│  └─ Uso:          Club + Eventos                               │
│                                                                 │
│  🎫 INVITADOS TEMPORALES (No Eventos)                          │
│  ├─ Formato:      #### (4 dígitos numéricos)                   │
│  ├─ Ejemplos:     0500, 0789, 0999                            │
│  ├─ Fuente:       Registro Externo (NO en API)                │
│  ├─ Permanencia:  NO permanente - Temporal                     │
│  ├─ Propósito:    Actividades/reservas específicas con socios │
│  └─ Uso:          Solo Entrada Club                           │
│                                                                 │
│  🎉 INVITADOS DE EVENTO                                        │
│  ├─ Formato:      ####-INV# (código socio + sufijo)           │
│  ├─ Ejemplos:     0001-INV1, 0001-INV2, 0234-INV1            │
│  ├─ Fuente:       Base de Datos Local                         │
│  ├─ Permanencia:  Por duración del evento                      │
│  ├─ Vinculación:  Primeros 4 dígitos = código socio anfitrión │
│  └─ Uso:          Solo eventos específicos                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## Detalles por Tipo

### 1. Socios Titulares

**Características:**
- Formato: `####` (exactamente 4 dígitos numéricos)
- Rango válido: `0001` a `9999`
- Son los dueños principales de la membresía

**Validación Regex:**
```javascript
/^\d{4}$/
```

**Ejemplos Válidos:**
- ✅ `0001`
- ✅ `0234`
- ✅ `1456`
- ✅ `9999`

**Ejemplos Inválidos:**
- ❌ `001` (menos de 4 dígitos)
- ❌ `00001` (más de 4 dígitos)
- ❌ `0001-A` (tiene sufijo, es familiar)
- ❌ `S001` (tiene letras)

**Fuente de Datos:**
- API Externa: `GET /api/socios/{codigo}`
- Retorna: nombre, DNI, email, teléfono, área

**Almacenamiento:**
- NO se almacenan localmente
- Solo referencias en `participantes_evento` cuando participan en eventos
- Historial en `entrada_club` cuando ingresan

---

### 2. Familiares de Socios

**Características:**
- Formato: `####-XXX` (4 dígitos + guion + letras)
- Los primeros 4 dígitos corresponden al socio titular
- El sufijo (letras después del guion) identifica al familiar
- Sufijos comunes: `A`, `B`, `C`, `FAM`, `ESP` (esposa), `HIJ1` (hijo 1)

**Validación Regex:**
```javascript
/^\d{4}-[A-Z]+$/
```

**Ejemplos Válidos:**
- ✅ `0001-A` (familiar A del socio 0001)
- ✅ `0234-B` (familiar B del socio 0234)
- ✅ `1456-FAM` (familiar del socio 1456)
- ✅ `0001-ESP` (esposa del socio 0001)
- ✅ `0001-HIJ1` (hijo 1 del socio 0001)

**Ejemplos Inválidos:**
- ❌ `0001` (falta sufijo)
- ❌ `0001-1` (sufijo debe ser letras, no números)
- ❌ `0001-a` (letras deben ser mayúsculas)
- ❌ `001-A` (menos de 4 dígitos)

**Fuente de Datos:**
- API Externa: `GET /api/socios/{codigo}` o `GET /api/socios/{codigo_socio}/familiares`
- Retorna: nombre, DNI, parentesco, código del socio titular

**Relación con Socio Titular:**
```javascript
const codigoFamiliar = "0001-A";
const codigoSocio = codigoFamiliar.substring(0, 4); // "0001"
```

**Almacenamiento:**
- Igual que socios: NO se almacenan localmente
- Referencias en `participantes_evento` y `entrada_club`

---

### 3. Invitados Temporales (No Eventos)

**Características:**
- Formato: `####` (mismo formato que socios, pero NO permanentes)
- **NO están en la API externa**
- Son registros temporales creados externamente
- Usados para actividades puntuales o reservas con socios

**Diferencia con Socios:**
- Aunque el formato es idéntico (`####`), **NO están en la API**
- Son temporales y no permanentes
- Se distinguen por el campo `tipo = 'invitado'` en `entrada_club`

**Validación Regex:**
```javascript
/^\d{4}$/
```

**Ejemplos Válidos:**
- ✅ `0500`
- ✅ `0789`
- ✅ `0999`

**Fuente de Datos:**
- **NO están en API Externa**
- Registrados por sistemas externos (ej: sistema de reservas)
- Se almacenan **solo** cuando ingresan al club

**Almacenamiento:**
- Tabla `entrada_club` con `tipo = 'invitado'`
- NO en `participantes_evento`

**Ciclo de Vida:**
```
1. Sistema Externo registra invitado temporal (código ####)
2. Invitado llega al club
3. Personal busca el código
4. Sistema encuentra el código en historial de entrada_club
5. Se registra nueva entrada
6. Después de la actividad, el código queda inactivo
```

---

### 4. Invitados de Evento

**Características:**
- Formato: `####-INV#` (código socio + sufijo INV + número)
- Siempre vinculados a un socio anfitrión
- Solo existen para eventos específicos
- Se crean al registrar participantes en el módulo "Registro"

**Validación Regex:**
```javascript
/^\d{4}-INV\d+$/
```

**Ejemplos Válidos:**
- ✅ `0001-INV1` (invitado 1 del socio 0001)
- ✅ `0001-INV2` (invitado 2 del socio 0001)
- ✅ `0234-INV1` (invitado 1 del socio 0234)

**Ejemplos Inválidos:**
- ❌ `0001-INV` (falta número)
- ❌ `0001-inv1` (INV debe ser mayúsculas)
- ❌ `001-INV1` (menos de 4 dígitos)

**Fuente de Datos:**
- Base de Datos Local (tabla `participantes_evento`)
- Creados manualmente por personal del club

**Relación con Socio Anfitrión:**
```javascript
const codigoInvitado = "0001-INV1";
const codigoSocio = codigoInvitado.substring(0, 4); // "0001"
```

**Almacenamiento:**
- Tabla `participantes_evento`:
  - `codigo_participante`: `0001-INV1`
  - `codigo_socio`: `0001`
  - `tipo`: `'invitado'`
- Tabla `entrada_evento` (para control de asistencia)

---

## Tabla Comparativa

| Característica | Socio (####) | Familiar (####-XXX) | Invitado Temp (####) | Invitado Evento (####-INV#) |
|----------------|--------------|---------------------|---------------------|----------------------------|
| **Formato** | 4 dígitos | 4 dígitos-letras | 4 dígitos | 4 dígitos-INV-número |
| **Ejemplo** | `0001` | `0001-A` | `0500` | `0001-INV1` |
| **En API** | ✅ Sí | ✅ Sí | ❌ No | ❌ No |
| **Permanente** | ✅ Sí | ✅ Sí | ❌ No | Solo durante evento |
| **Vinculado** | - | Socio titular | - | Socio anfitrión |
| **Entrada Club** | ✅ Sí | ✅ Sí | ✅ Sí | ❌ No (solo evento) |
| **Entrada Evento** | ✅ Sí | ✅ Sí | ❌ No | ✅ Sí |
| **DB Local** | Referencias | Referencias | entrada_club | participantes_evento |

---

## Flujos de Uso

### Entrada al Club

**Búsqueda:**
1. Usuario ingresa código o nombre
2. Sistema busca en:
   - API Externa → Socios (`####`) + Familiares (`####-XXX`)
   - DB Local → Invitados temporales (`####`) del historial
3. Combina y muestra resultados

**Registro:**
```sql
INSERT INTO entrada_club (codigo_participante, tipo, nombre, dni, area, fecha_hora)
VALUES 
  ('0001', 'socio', 'Juan Pérez', '12345678', 'Piscina', NOW()),      -- Socio
  ('0001-A', 'socio', 'María Pérez', '87654321', 'Piscina', NOW()),   -- Familiar
  ('0500', 'invitado', 'Pedro Gómez', '11111111', 'Cancha', NOW());   -- Temporal
```

### Registro de Evento

**Socio/Familiar:**
```sql
INSERT INTO participantes_evento 
  (evento_id, tipo, codigo_socio, codigo_participante, dni, nombre)
VALUES 
  (1, 'socio', '0001', '0001', '12345678', 'Juan Pérez'),      -- Socio
  (1, 'socio', '0001-A', '0001-A', '87654321', 'María Pérez'); -- Familiar usa su propio código
```

**Invitado de Evento:**
```sql
INSERT INTO participantes_evento 
  (evento_id, tipo, codigo_socio, codigo_participante, dni, nombre)
VALUES 
  (1, 'invitado', '0001', '0001-INV1', '99999999', 'Invitado 1'),
  (1, 'invitado', '0001', '0001-INV2', '88888888', 'Invitado 2');
```

---

## Validaciones en Código

### JavaScript/Frontend

```javascript
// Validar código de socio titular
function esSocioTitular(codigo) {
    return /^\d{4}$/.test(codigo);
}

// Validar código de familiar
function esFamiliar(codigo) {
    return /^\d{4}-[A-Z]+$/.test(codigo);
}

// Validar código de invitado de evento
function esInvitadoEvento(codigo) {
    return /^\d{4}-INV\d+$/.test(codigo);
}

// Extraer código del socio titular
function extraerCodigoSocio(codigo) {
    if (esFamiliar(codigo) || esInvitadoEvento(codigo)) {
        return codigo.substring(0, 4);
    }
    return codigo;
}

// Ejemplos
console.log(esSocioTitular('0001'));        // true
console.log(esFamiliar('0001-A'));          // true
console.log(esInvitadoEvento('0001-INV1')); // true
console.log(extraerCodigoSocio('0001-A'));  // '0001'
```

### PHP/Laravel

```php
// En SocioAPIService.php
public function esSocioTitular($codigo) {
    return preg_match('/^\d{4}$/', $codigo) === 1;
}

public function esFamiliar($codigo) {
    return preg_match('/^\d{4}-[A-Z]+$/', $codigo) === 1;
}

public function esInvitadoEvento($codigo) {
    return preg_match('/^\d{4}-INV\d+$/', $codigo) === 1;
}

public function extraerCodigoSocio($codigo) {
    if ($this->esFamiliar($codigo) || $this->esInvitadoEvento($codigo)) {
        return substr($codigo, 0, 4);
    }
    return $codigo;
}
```

---

## Casos de Uso Prácticos

### Caso 1: Familia en Evento
```
Socio titular:     0001 (Juan Pérez)
Familiar 1:        0001-A (María Pérez - esposa)
Familiar 2:        0001-B (Pedro Pérez - hijo)
Invitado evento:   0001-INV1 (Amigo de Juan)
```

### Caso 2: Entrada al Club
```
✅ 0001       → Socio de API
✅ 0001-A     → Familiar de API
✅ 0500       → Invitado temporal (NO en API, del historial)
❌ 0001-INV1  → NO permitido (solo para eventos)
```

### Caso 3: Búsqueda en Sistema
```
Input: "0001"
Resultados:
- API: Juan Pérez (0001) - Socio
- API: María Pérez (0001-A) - Familiar
- API: Pedro Pérez (0001-B) - Familiar
- DB Local (participantes_evento): 0001-INV1, 0001-INV2 (solo si están en eventos)
```

---

## Preguntas Frecuentes

**P: ¿Por qué socios e invitados temporales tienen el mismo formato?**  
R: Aunque ambos usan `####`, se diferencian por:
- **Fuente:** Socios están en API, invitados temporales NO
- **Campo tipo:** `'socio'` vs `'invitado'` en tabla `entrada_club`
- **Permanencia:** Socios son permanentes, invitados son temporales

**P: ¿Un familiar puede tener invitados de evento?**  
R: Sí, si el familiar actúa como anfitrión, sus invitados usarían su código:
- Familiar: `0001-A`
- Invitado: `0001-A-INV1` (pero esto debe validarse con el negocio)

**P: ¿Qué pasa si un código `0500` existe como socio en API y como invitado temporal en DB?**  
R: El de API tiene prioridad. El sistema primero busca en API, y solo si no encuentra, usa el historial local.

**P: ¿Los invitados temporales pueden participar en eventos?**  
R: No directamente. Si necesitan participar en un evento, deben ser registrados como invitados del socio anfitrión con formato `####-INV#`.
