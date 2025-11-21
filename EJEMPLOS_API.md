# Ejemplos de Integración API

Este documento muestra ejemplos prácticos de cómo usar los endpoints de la API desde el frontend.

## Configuración Inicial

Incluir el helper de AJAX en tu vista:
```blade
@include('partials.ajax_helper')
```

Este helper ya configura automáticamente el CSRF token en todas las peticiones AJAX.

## 1. Gestión de Eventos

### Crear Nuevo Evento
```javascript
function crearEvento() {
    const data = {
        nombre: $('#evento_nombre').val(),
        fecha: $('#evento_fecha').val(),
        area: $('#evento_area').val()
    };

    API.post('/api/eventos', data)
        .done(function(response) {
            showMessage('Evento creado exitosamente', 'success');
            closeEventModal();
            recargarListaEventos();
        })
        .fail(function(xhr) {
            API.handleError(xhr, 'Error al crear el evento');
        });
}
```

### Listar Eventos
```javascript
function cargarEventos(filtros = {}) {
    API.get('/api/eventos', filtros)
        .done(function(response) {
            if (response.success) {
                renderizarEventos(response.data);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

### Actualizar Evento
```javascript
function editarEvento(eventoId) {
    const data = {
        nombre: $('#edit_nombre').val(),
        fecha: $('#edit_fecha').val(),
        area: $('#edit_area').val()
    };

    API.put(`/api/eventos/${eventoId}`, data)
        .done(function(response) {
            showMessage('Evento actualizado exitosamente', 'success');
            recargarListaEventos();
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

### Eliminar Evento
```javascript
function eliminarEvento(eventoId) {
    if (!confirm('¿Está seguro de eliminar este evento?')) {
        return;
    }

    API.delete(`/api/eventos/${eventoId}`)
        .done(function(response) {
            showMessage('Evento eliminado exitosamente', 'success');
            recargarListaEventos();
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

## 2. Gestión de Mesas

### Crear Nueva Mesa
```javascript
function crearMesa() {
    const data = {
        evento_id: $('#evento_id').val(),
        numero_mesa: $('#numero_mesa').val(),
        capacidad: $('#capacidad').val()
    };

    API.post('/api/mesas', data)
        .done(function(response) {
            showMessage('Mesa creada exitosamente', 'success');
            closeMesaModal();
            recargarMesas();
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

### Editar Mesa (con validación de capacidad)
```javascript
function editarMesa(mesaId) {
    const data = {
        numero_mesa: $('#edit_numero_mesa').val(),
        capacidad: parseInt($('#edit_capacidad_mesa').val())
    };

    API.put(`/api/mesas/${mesaId}`, data)
        .done(function(response) {
            showMessage('Mesa actualizada exitosamente', 'success');
            closeEditMesaModal();
            recargarMesas();
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
                // Error de validación (ej: capacidad menor a ocupadas)
                API.handleError(xhr);
            } else {
                API.handleError(xhr, 'Error al actualizar la mesa');
            }
        });
}
```

### Listar Mesas de un Evento
```javascript
function cargarMesasEvento(eventoId) {
    API.get(`/api/mesas/evento/${eventoId}`)
        .done(function(response) {
            if (response.success) {
                renderizarMesas(response.data);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

## 3. Registro de Participantes

### Buscar Socio en API Externa
```javascript
function buscarSocio() {
    const codigo = $('#codigo_socio').val();
    
    if (!codigo) {
        alert('Ingrese un código de socio');
        return;
    }

    API.post('/api/participantes/buscar-socio', { codigo: codigo })
        .done(function(response) {
            if (response.success) {
                // Rellenar formulario con datos del socio o familiar
                // Códigos válidos: #### (socio) o ####-XXX (familiar)
                $('#nombre').val(response.data.nombre);
                $('#dni').val(response.data.dni);
                $('#tipo').val(response.data.tipo === 'familiar' ? 'socio' : 'socio');
            }
        })
        .fail(function(xhr) {
            if (xhr.status === 404) {
                showMessage('Socio no encontrado en API', 'error');
            } else {
                API.handleError(xhr);
            }
        });
}
```

### Obtener Familiares de un Socio Titular
```javascript
function cargarFamiliares(codigoSocio) {
    // codigoSocio debe ser formato #### (4 dígitos)
    API.get(`/api/participantes/socio/${codigoSocio}/familiares`)
        .done(function(response) {
            if (response.success) {
                // response.data contiene familiares con formato ####-XXX
                renderizarFamiliares(response.data);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

### Registrar Participante en Evento
```javascript
function registrarParticipante() {
    const data = {
        evento_id: $('#evento').val(),
        mesa_id: $('#mesa').val(),
        numero_silla: $('#n_silla').val(),
        tipo: $('#tipo').val(),
        codigo_socio: $('#codigo_socio').val(),
        codigo_participante: $('#codigo_participante').val(), // ej: S001 o S001-INV1
        dni: $('#dni').val(),
        nombre: $('#nombre').val(),
        n_recibo: $('#n_recibo').val(),
        n_boleta: $('#n_boleta').val()
    };

    API.post('/api/participantes', data)
        .done(function(response) {
            showMessage('Participante registrado exitosamente', 'success');
            limpiarFormulario();
            recargarParticipantes();
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
                // Validación: mesa completa, silla ocupada, etc.
                API.handleError(xhr);
            } else {
                API.handleError(xhr, 'Error al registrar participante');
            }
        });
}
```

### Actualizar Mesa/Silla de Participante
```javascript
function cambiarMesa(participanteId) {
    const data = {
        mesa_id: $('#nueva_mesa').val(),
        numero_silla: $('#nueva_silla').val()
    };

    API.put(`/api/participantes/${participanteId}/mesa`, data)
        .done(function(response) {
            showMessage('Asignación actualizada', 'success');
            recargarParticipantes();
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

## 4. Entrada al Club

### Buscar Participante (API + DB)
```javascript
function buscarParticipanteClub() {
    const termino = $('#buscar').val();
    
    if (!termino || termino.length < 2) {
        return;
    }

    API.post('/api/entrada-club/buscar', { termino: termino })
        .done(function(response) {
            if (response.success) {
                // response.data contiene:
                // - Socios (####) de API
                // - Familiares (####-XXX) de API
                // - Invitados temporales (####) de DB local
                mostrarResultados(response.data);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}

// Uso con debounce
let busquedaTimeout;
$('#buscar').on('input', function() {
    clearTimeout(busquedaTimeout);
    busquedaTimeout = setTimeout(buscarParticipanteClub, 300);
});
```

### Registrar Entrada al Club
```javascript
function registrarEntrada(participante) {
    const data = {
        codigo_participante: participante.codigo, // ####, ####-XXX, o #### temporal
        tipo: participante.tipo, // 'socio' (API) o 'invitado' (temporal)
        nombre: participante.nombre,
        dni: participante.dni,
        area: participante.area || 'General'
    };

    API.post('/api/entrada-club', data)
        .done(function(response) {
            showMessage('Entrada registrada', 'success');
            recargarTabla();
            actualizarEstadisticas();
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

### Obtener Estadísticas del Día
```javascript
function cargarEstadisticas(fecha = null) {
    const params = fecha ? { fecha: fecha } : {};
    
    API.get('/api/entrada-club/estadisticas', params)
        .done(function(response) {
            if (response.success) {
                $('#total').text(response.data.total);
                $('#socios').text(response.data.socios);
                $('#invitados').text(response.data.invitados);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

## 5. Entrada a Evento

### Buscar Participante en Evento
```javascript
function buscarEnEvento() {
    const eventoId = $('#evento_selector').val();
    const termino = $('#buscar_evento').val();

    if (!eventoId || !termino) {
        return;
    }

    API.post('/api/entrada-evento/buscar', {
        evento_id: eventoId,
        termino: termino
    })
    .done(function(response) {
        if (response.success) {
            mostrarParticipantes(response.data);
        }
    })
    .fail(function(xhr) {
        API.handleError(xhr);
    });
}
```

### Marcar Entrada al Club (desde evento)
```javascript
function marcarEntradaClub(participanteId, checkbox) {
    if (!$(checkbox).is(':checked')) {
        return; // Solo permitir marcar, no desmarcar
    }

    API.post(`/api/entrada-evento/${participanteId}/entrada-club`, {})
        .done(function(response) {
            showMessage('Entrada al club registrada', 'success');
            actualizarEstadisticasEvento();
        })
        .fail(function(xhr) {
            $(checkbox).prop('checked', false); // Revertir checkbox
            API.handleError(xhr);
        });
}
```

### Marcar Entrada al Evento
```javascript
function marcarEntradaEvento(participanteId, checkbox) {
    if (!$(checkbox).is(':checked')) {
        return;
    }

    API.post(`/api/entrada-evento/${participanteId}/entrada-evento`, {})
        .done(function(response) {
            showMessage('Entrada al evento registrada', 'success');
            actualizarEstadisticasEvento();
        })
        .fail(function(xhr) {
            $(checkbox).prop('checked', false);
            API.handleError(xhr);
        });
}
```

### Obtener Estadísticas del Evento
```javascript
function cargarEstadisticasEvento(eventoId) {
    API.get(`/api/entrada-evento/${eventoId}/estadisticas`)
        .done(function(response) {
            if (response.success) {
                $('#total_inscritos').text(response.data.total);
                $('#entrada_club').text(response.data.entrada_club);
                $('#entrada_evento').text(response.data.entrada_evento);
                $('#pendientes_club').text(response.data.pendientes_club);
                $('#pendientes_evento').text(response.data.pendientes_evento);
            }
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        });
}
```

## 6. Patrones Comunes

### Recargar Datos Después de una Operación
```javascript
function recargarDatos() {
    // Recargar eventos
    cargarEventos();
    
    // Recargar mesas del evento actual
    const eventoId = $('#evento_actual').val();
    if (eventoId) {
        cargarMesasEvento(eventoId);
        cargarParticipantesEvento(eventoId);
    }
}
```

### Validación Antes de Enviar
```javascript
function validarFormulario() {
    const errors = [];
    
    if (!$('#nombre').val()) {
        errors.push('El nombre es requerido');
    }
    
    if (!$('#evento').val()) {
        errors.push('Seleccione un evento');
    }
    
    if (errors.length > 0) {
        alert(errors.join('\n'));
        return false;
    }
    
    return true;
}

$('#formRegistro').on('submit', function(e) {
    e.preventDefault();
    
    if (!validarFormulario()) {
        return;
    }
    
    registrarParticipante();
});
```

### Manejo de Loading State
```javascript
function mostrarLoading(show = true) {
    if (show) {
        $('#loading').show();
        $('button').prop('disabled', true);
    } else {
        $('#loading').hide();
        $('button').prop('disabled', false);
    }
}

function cargarDatos() {
    mostrarLoading(true);
    
    API.get('/api/eventos')
        .done(function(response) {
            renderizarEventos(response.data);
        })
        .fail(function(xhr) {
            API.handleError(xhr);
        })
        .always(function() {
            mostrarLoading(false);
        });
}
```

## 7. Testing en Console

Puedes probar los endpoints directamente desde la consola del navegador:

```javascript
// Crear evento de prueba
API.post('/api/eventos', {
    nombre: 'Cena Anual 2025',
    fecha: '2025-12-31',
    area: 'Salón Principal'
}).done(console.log);

// Listar eventos
API.get('/api/eventos').done(console.log);

// Buscar socio (#### o ####-XXX)
API.post('/api/participantes/buscar-socio', {
    codigo: '0001'
}).done(console.log);

// Buscar familiar (formato ####-XXX)
API.post('/api/participantes/buscar-socio', {
    codigo: '0001-A'
}).done(console.log);

// Obtener familiares de un socio titular
API.get('/api/participantes/socio/0001/familiares').done(console.log);
```
