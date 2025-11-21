# Guía de Testing Local - Sistema de Gestión de Eventos

## 🚀 Entorno Preparado

### ✅ Estado Actual
- ✅ Contenedor Docker corriendo en `http://localhost:8080`
- ✅ Base de datos SQLite configurada
- ✅ 5 tablas creadas (eventos, mesas, participantes_evento, entrada_club, entrada_evento)
- ✅ Datos de prueba cargados
- ✅ 38 rutas API registradas

## 📊 Datos de Prueba Disponibles

### Eventos Creados
1. **Cena Anual 2025** (31/12/2025) - Salón Principal
   - 5 mesas (1-5)
   - Capacidad total: 40 personas
   - 5 participantes registrados

2. **Fiesta de Fin de Año** (25/12/2025) - Terraza

3. **Torneo de Tenis** (+7 días) - Canchas

### Participantes en Eventos
| Código | Tipo | Nombre | Mesa | Silla |
|--------|------|--------|------|-------|
| 0001 | Socio | Juan Pérez | 1 | 1 |
| 0001-A | Familiar | María Pérez | 1 | 2 |
| 0001-INV1 | Invitado | Pedro García | 1 | 3 |
| 0234 | Socio | Ana López | 1 | 4 |
| 0456 | Socio | Carlos Rodríguez | 2 | 1 |

### Historial de Entrada al Club
| Código | Tipo | Nombre | Área | Fecha |
|--------|------|--------|------|-------|
| 0001 | Socio | Juan Pérez | Piscina | Hace 2 días |
| 0001-A | Familiar | María Pérez | Gimnasio | Ayer |
| 0500 | Invitado Temporal | Invitado 1 | Cancha Tenis | Hace 3 días |
| 0789 | Invitado Temporal | Invitado 2 | Restaurante | Ayer |

## 🧪 Testing del Sistema

### 1. Verificar Login
```
URL: http://localhost:8080
Usuario: (configurar en la tabla users)
```

### 2. Testing de APIs con Browser Console

Abrir `http://localhost:8080/registro` y en la consola del navegador:

#### Listar Eventos
```javascript
fetch('/api/eventos')
  .then(r => r.json())
  .then(console.log);
```

#### Buscar en Entrada Club (API + DB)
```javascript
fetch('/api/entrada-club/buscar', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({ termino: '0001' })
})
.then(r => r.json())
.then(console.log);
```

#### Obtener Mesas de un Evento
```javascript
fetch('/api/mesas/evento/1')
  .then(r => r.json())
  .then(console.log);
```

#### Estadísticas de Entrada Club
```javascript
fetch('/api/entrada-club/estadisticas')
  .then(r => r.json())
  .then(console.log);
```

### 3. Testing de Módulos

#### Módulo: Registro
- URL: `http://localhost:8080/registro`
- **Probar:**
  - ✅ Crear nuevo evento
  - ✅ Crear mesa para evento
  - ✅ Editar mesa (validación de capacidad)
  - ✅ Buscar socio (simular API)
  - ✅ Registrar participante

#### Módulo: Entrada
- URL: `http://localhost:8080/entrada`
- **Probar:**
  - ✅ Buscar por código: `0001`, `0001-A`, `0500`
  - ✅ Ver historial de entradas
  - ✅ Estadísticas del día
  - ✅ Registrar nueva entrada

#### Módulo: Eventos
- URL: `http://localhost:8080/eventos`
- **Probar:**
  - ✅ Seleccionar evento "Cena Anual 2025"
  - ✅ Buscar participante: `0001`, `Pedro`
  - ✅ Marcar entrada club (checkbox)
  - ✅ Marcar entrada evento (checkbox)
  - ✅ Ver estadísticas

## 🔧 Comandos Útiles

### Acceder al Contenedor
```bash
docker exec -it laravel-app bash
```

### Ver Logs de Laravel
```bash
docker exec -it laravel-app tail -f storage/logs/laravel.log
```

### Ejecutar Comandos Artisan
```bash
docker exec -it laravel-app php artisan [comando]
```

### Ver Base de Datos
```bash
docker exec -it laravel-app php artisan tinker
```
Luego en tinker:
```php
DB::table('eventos')->get();
DB::table('participantes_evento')->get();
DB::table('entrada_club')->get();
```

### Recargar Datos de Prueba
```bash
docker exec -it laravel-app php artisan db:seed --class=TestDataSeeder
```

### Limpiar Cachés
```bash
docker exec -it laravel-app php artisan optimize:clear
```

## 🐛 Troubleshooting

### Error 500 - Internal Server Error
1. Ver logs: `docker exec -it laravel-app tail -f storage/logs/laravel.log`
2. Verificar permisos: `docker exec -it laravel-app chmod -R 777 storage`
3. Limpiar caché: `docker exec -it laravel-app php artisan optimize:clear`

### CSRF Token Mismatch
- Verificar que `<meta name="csrf-token">` existe en el HTML
- Limpiar cookies del navegador
- Verificar que APP_KEY está configurado en .env

### API Externa No Disponible
- La API de socios está configurada en: `SOCIO_API_URL=http://api-externa.com`
- Por ahora, las búsquedas de socios retornarán vacío (esperado)
- Se puede simular respuestas en el código para testing

### No se Muestran Datos
1. Verificar que el seeder se ejecutó: 
   ```bash
   docker exec -it laravel-app php artisan db:seed --class=TestDataSeeder
   ```
2. Verificar en tinker:
   ```bash
   docker exec -it laravel-app php artisan tinker
   >>> \App\Models\Evento::count();
   ```

## 📝 Endpoints Principales

### Eventos
- `GET /api/eventos` - Listar todos
- `POST /api/eventos` - Crear nuevo
- `GET /api/eventos/{id}` - Ver detalles
- `PUT /api/eventos/{id}` - Actualizar
- `DELETE /api/eventos/{id}` - Eliminar

### Mesas
- `POST /api/mesas` - Crear mesa
- `PUT /api/mesas/{id}` - Editar mesa
- `DELETE /api/mesas/{id}` - Eliminar mesa
- `GET /api/mesas/evento/{eventoId}` - Mesas de un evento

### Participantes
- `POST /api/participantes` - Registrar participante
- `GET /api/participantes/evento/{eventoId}` - Listar participantes
- `POST /api/participantes/buscar-socio` - Buscar en API externa
- `GET /api/participantes/socio/{codigo}/familiares` - Obtener familiares
- `PUT /api/participantes/{id}/mesa` - Cambiar mesa/silla
- `DELETE /api/participantes/{id}` - Eliminar participante

### Entrada Club
- `POST /api/entrada-club/buscar` - Buscar participantes (API + DB)
- `POST /api/entrada-club` - Registrar entrada
- `GET /api/entrada-club/estadisticas` - Estadísticas del día
- `GET /api/entrada-club/listar` - Listar entradas

### Entrada Evento
- `POST /api/entrada-evento/buscar` - Buscar en evento
- `POST /api/entrada-evento/{participanteId}/entrada-club` - Marcar entrada club
- `POST /api/entrada-evento/{participanteId}/entrada-evento` - Marcar entrada evento
- `GET /api/entrada-evento/{eventoId}/estadisticas` - Estadísticas
- `GET /api/entrada-evento/{eventoId}/listar` - Listar participantes

## 🎯 Checklist de Testing

### Backend
- [ ] Todas las migraciones ejecutadas
- [ ] Datos de prueba cargados
- [ ] Rutas API accesibles
- [ ] Modelos Eloquent funcionando
- [ ] Validaciones de capacidad de mesa
- [ ] Códigos únicos en eventos

### Frontend
- [ ] Login funcional
- [ ] Navegación entre módulos
- [ ] Modales abren/cierran correctamente
- [ ] Búsquedas con debounce
- [ ] Tablas muestran datos
- [ ] Checkboxes de asistencia
- [ ] Estadísticas se actualizan
- [ ] Diseño responsive

### Integración
- [ ] CSRF token en peticiones AJAX
- [ ] Manejo de errores consistente
- [ ] Mensajes de éxito/error
- [ ] Loading states
- [ ] Actualización de datos en tiempo real

## 🌐 URLs de Acceso

- **Sistema Principal:** http://localhost:8080
- **Login:** http://localhost:8080/
- **Registro:** http://localhost:8080/registro
- **Entrada:** http://localhost:8080/entrada
- **Eventos:** http://localhost:8080/eventos

## 📚 Documentación Relacionada

- `ARQUITECTURA.md` - Diseño completo del sistema
- `FORMATOS_CODIGOS.md` - Guía de códigos de socios/familiares/invitados
- `EJEMPLOS_API.md` - Ejemplos de uso de endpoints
- `INSTALACION.md` - Guía de instalación
- `RESUMEN_IMPLEMENTACION.md` - Resumen de lo implementado

---

**¡El sistema está listo para testing! 🎉**

Cualquier problema, revisar los logs en `storage/logs/laravel.log`
