# Guía de Autocompletado de Socios

## 🎯 Funcionalidad Implementada

Se ha implementado un sistema de autocompletado inteligente en el formulario de registro de participantes que simula la integración con una API externa de socios del club.

---

## 📋 Características

### 1. **Autocompletado para Socios**

Cuando se selecciona **Tipo: Socio** y se ingresa un código:

1. El sistema busca automáticamente en la API simulada (después de 800ms de inactividad)
2. Muestra un **modal popup** con:
   - El socio principal
   - Todos sus familiares registrados
3. Al seleccionar una persona del modal:
   - Se autocompleta el campo **DNI**
   - Se autocompleta el campo **Nombre**
   - Se mantiene el código seleccionado

### 2. **Información para Invitados**

Cuando se selecciona **Tipo: Invitado** y se ingresa un código:

1. El sistema extrae el código base (sin sufijo -INV)
2. Busca el nombre del socio titular
3. Muestra un mensaje debajo del input:
   ```
   📋 Invitado de: Juan Pérez García
   ```

---

## 🔌 APIs Simuladas Disponibles

### Buscar Socio con Familiares
```http
GET /api/socios-externos/buscar/{codigo}
```

**Ejemplo:**
```bash
curl http://localhost:8080/api/socios-externos/buscar/0001
```

**Respuesta:**
```json
{
  "socio_principal": {
    "codigo": "0001",
    "dni": "12345678",
    "nombre": "Juan Pérez García",
    "tipo": "principal"
  },
  "familiares": [
    {
      "codigo": "0001-A",
      "dni": "87654321",
      "nombre": "María Pérez López",
      "parentesco": "Esposa",
      "edad": 35,
      "tipo": "familiar"
    },
    {
      "codigo": "0001-B",
      "dni": "11223344",
      "nombre": "Carlos Pérez López",
      "parentesco": "Hijo",
      "edad": 12,
      "tipo": "familiar"
    }
  ]
}
```

### Obtener Nombre del Socio
```http
GET /api/socios-externos/nombre/{codigo}
```

**Ejemplo:**
```bash
curl http://localhost:8080/api/socios-externos/nombre/0001
```

**Respuesta:**
```json
{
  "codigo": "0001",
  "nombre": "Juan Pérez García"
}
```

### Verificar Existencia de Socio
```http
GET /api/socios-externos/existe/{codigo}
```

**Ejemplo:**
```bash
curl http://localhost:8080/api/socios-externos/existe/0001
```

**Respuesta:**
```json
{
  "existe": true,
  "codigo": "0001"
}
```

---

## 👥 Datos de Prueba Disponibles

### Socio 0001 - Juan Pérez García
```
Código Principal: 0001
DNI: 12345678
Familiares:
  - 0001-A: María Pérez López (Esposa, 35 años) - DNI: 87654321
  - 0001-B: Carlos Pérez López (Hijo, 12 años) - DNI: 11223344
  - 0001-C: Ana Pérez López (Hija, 8 años) - DNI: 44332211
```

### Socio 0002 - Carlos Rodríguez Silva
```
Código Principal: 0002
DNI: 23456789
Familiares:
  - 0002-A: Laura Silva Mendoza (Esposa, 32 años) - DNI: 98765432
```

### Socio 0003 - Ana Martínez Torres
```
Código Principal: 0003
DNI: 34567890
Familiares:
  - 0003-A: Pedro Martínez Ruiz (Esposo, 42 años) - DNI: 56789012
  - 0003-B: Sofia Martínez Ruiz (Hija, 15 años) - DNI: 67890123
```

### Socio 0234 - Roberto Sánchez Díaz
```
Código Principal: 0234
DNI: 45678901
Familiares: Ninguno
```

### Socio 0500 - Luis García Morales
```
Código Principal: 0500
DNI: 78901234
Familiares:
  - 0500-A: Carmen Morales Vega (Esposa, 38 años) - DNI: 89012345
```

---

## 🎮 Cómo Usar

### Escenario 1: Registrar Socio Principal

1. Ir a `/registro`
2. Seleccionar **Tipo: Socio**
3. Ingresar código: `0001`
4. Esperar 800ms (debounce)
5. **Se abre modal automáticamente** mostrando:
   - Juan Pérez García (SOCIO PRINCIPAL)
   - María Pérez López (Esposa)
   - Carlos Pérez López (Hijo)
   - Ana Pérez López (Hija)
6. Click en "Juan Pérez García"
7. Los campos se llenan automáticamente:
   - Código: `0001`
   - DNI: `12345678`
   - Nombre: `Juan Pérez García`

### Escenario 2: Registrar Familiar

1. Ir a `/registro`
2. Seleccionar **Tipo: Socio**
3. Ingresar código: `0001`
4. Esperar que aparezca el modal
5. Click en "María Pérez López (Esposa)"
6. Los campos se llenan automáticamente:
   - Código: `0001-A`
   - DNI: `87654321`
   - Nombre: `María Pérez López`

### Escenario 3: Registrar Invitado

1. Ir a `/registro`
2. Seleccionar **Tipo: Invitado**
3. Ingresar código del socio: `0001`
4. Esperar 800ms
5. **Aparece mensaje debajo del input:**
   ```
   📋 Invitado de: Juan Pérez García
   ```
6. Completar manualmente:
   - DNI del invitado
   - Nombre del invitado

---

## 🎨 Componentes UI

### Modal de Selección
- **Diseño**: Cards clicables con hover effect
- **Contenido por card**:
  - Nombre (destacado)
  - Código
  - DNI
  - Badge de tipo (Principal/Parentesco)
- **Interacción**:
  - Hover: Borde verde, fondo claro, elevación
  - Click: Autocompleta y cierra modal
  
### Mensaje de Invitado
- **Estilo**: Banner verde con borde izquierdo
- **Animación**: Slide down al aparecer
- **Contenido**: "Invitado de: [Nombre del Socio]"
- **Comportamiento**: Se oculta al cambiar tipo o código

---

## ⚙️ Configuración Técnica

### Debounce
```javascript
timeout: 800ms
// Evita hacer requests mientras el usuario está escribiendo
```

### Archivos Modificados

1. **`app/Services/SocioAPISimulada.php`** (NUEVO)
   - Servicio con datos simulados de socios
   - Métodos de búsqueda y validación

2. **`routes/web.php`**
   - Rutas API: `/api/socios-externos/*`

3. **`resources/views/registro.blade.php`**
   - Modal de selección de socio/familiar
   - Mensaje de invitado en formulario

4. **`resources/views/partials/registro_styles.blade.php`**
   - Estilos para modal y mensaje
   - Animaciones y efectos hover

5. **`resources/views/partials/registro_scripts.blade.php`**
   - Función `configurarAutocompletadoSocio()`
   - Función `buscarSocioYMostrarModal()`
   - Función `mostrarModalSeleccionSocio()`
   - Función `seleccionarPersona()`
   - Función `mostrarNombreSocioInvitado()`

---

## 🔄 Flujo de Datos

### Flujo para Socios
```
Usuario ingresa código
    ↓ (800ms debounce)
Fetch GET /api/socios-externos/buscar/{codigo}
    ↓
Recibe socio + familiares
    ↓
Muestra modal con opciones
    ↓
Usuario selecciona persona
    ↓
Autocompleta DNI y Nombre
    ↓
Cierra modal
```

### Flujo para Invitados
```
Usuario ingresa código
    ↓ (800ms debounce)
Extrae código base (sin -INV)
    ↓
Fetch GET /api/socios-externos/nombre/{codigo}
    ↓
Recibe nombre del socio
    ↓
Muestra mensaje "Invitado de: [Nombre]"
    ↓
Usuario completa DNI y Nombre manualmente
```

---

## 🧪 Testing

### Probar API desde Terminal

**Buscar socio con familiares:**
```powershell
curl http://localhost:8080/api/socios-externos/buscar/0001 | ConvertFrom-Json | ConvertTo-Json -Depth 5
```

**Obtener nombre:**
```powershell
curl http://localhost:8080/api/socios-externos/nombre/0002 | ConvertFrom-Json
```

**Verificar existencia:**
```powershell
curl http://localhost:8080/api/socios-externos/existe/0003 | ConvertFrom-Json
```

### Probar UI

1. **Modal de Selección:**
   - Tipo: Socio → Código: `0001` → Verificar modal con 4 opciones
   - Tipo: Socio → Código: `0234` → Verificar modal con solo 1 opción (sin familiares)

2. **Mensaje de Invitado:**
   - Tipo: Invitado → Código: `0001` → Verificar mensaje "Juan Pérez García"
   - Tipo: Invitado → Código: `0500` → Verificar mensaje "Luis García Morales"

3. **Código inexistente:**
   - Código: `9999` → No debe mostrar nada (console log mostrará "no encontrado")

---

## 🚀 Ventajas del Sistema

1. ✅ **Reduce errores**: Datos autocompletados desde fuente confiable
2. ✅ **Ahorra tiempo**: No necesita escribir DNI y nombre
3. ✅ **Mejora UX**: Interfaz intuitiva con modal visual
4. ✅ **Validación implícita**: Solo permite códigos existentes
5. ✅ **Información contextual**: Muestra relación para invitados
6. ✅ **Performance optimizado**: Debounce evita requests innecesarios

---

## 📝 Notas Importantes

- La API es **simulada** para desarrollo/testing
- En producción, reemplazar `SocioAPISimulada` con llamadas a API real
- El debounce de 800ms puede ajustarse según necesidad
- Los códigos deben tener al menos 4 caracteres para activar búsqueda
- El modal se puede cerrar con el botón X o ESC (implementar si necesario)

---

**Fecha de Implementación**: 21 de Noviembre, 2025  
**Versión**: 1.0  
**Framework**: Laravel 10 + JavaScript Vanilla
