<script>
    // Manejo de pestañas
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');

                // Remover active de todos
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Activar el seleccionado
                this.classList.add('active');
                document.getElementById(tabName + '-tab').classList.add('active');
            });
        });

        // Cargar datos iniciales
        cargarEventosEnSelectores();
        cargarCapacidadEventos();
        cargarMesas();
        cargarDisposicionMesas();

        // Agregar listeners para autocompletado de socio
        configurarAutocompletadoSocio();
    });

    // Funciones para cargar datos dinámicamente
    async function cargarEventosEnSelectores() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            // Selector del formulario de registro
            const selectRegistro = document.getElementById('evento');
            selectRegistro.innerHTML = '<option value="">Seleccione un evento</option>';

            // Selector del modal de nueva mesa
            const selectMesa = document.getElementById('evento_mesa');
            selectMesa.innerHTML = '<option value="">Seleccione un evento</option>';

            eventos.forEach(evento => {
                const option1 = document.createElement('option');
                option1.value = evento.id;
                option1.textContent = `${evento.nombre} - ${evento.fecha}`;
                selectRegistro.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = evento.id;
                option2.textContent = `${evento.nombre} - ${evento.fecha}`;
                selectMesa.appendChild(option2);
            });
        } catch (error) {
            console.error('[Registro] Error cargando eventos:', error);
        }
    }

    async function cargarCapacidadEventos() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const wrapper = document.querySelector('.capacity-scroll-wrapper');
            let html = '';

            if (eventos.length === 0) {
                html += '<p style="text-align:center; color:#999; padding:1rem;">No hay eventos registrados</p>';
            } else {
                for (const evento of eventos) {
                    // Obtener participantes del evento
                    const participantesResp = await fetch(`/api/participantes/evento/${evento.id}`);
                    const participantesData = await participantesResp.json();

                    // Extraer data del objeto {success: true, data: [...]}
                    const participantes = participantesData.success ? participantesData.data : (Array.isArray(participantesData) ? participantesData : []);

                    // Obtener mesas del evento
                    const mesasResp = await fetch(`/api/mesas/evento/${evento.id}`);
                    const mesas = await mesasResp.json();

                    // Validar que mesas sea un array
                    const mesasArray = Array.isArray(mesas) ? mesas : [];
                    const participantesArray = Array.isArray(participantes) ? participantes : [];

                    const capacidadTotal = mesasArray.reduce((sum, mesa) => sum + (parseInt(mesa.capacidad) || 0), 0);
                    const ocupados = participantesArray.length;
                    const libres = capacidadTotal - ocupados;

                    // Mostrar evento incluso si no tiene mesas
                    let mesasInfo;
                    if (mesasArray.length > 0) {
                        mesasInfo = `<span class="capacity-fill">Libres: ${libres}</span>
                                     <span class="capacity-total">Ocupados: ${ocupados}/${capacidadTotal}</span>`;
                    } else {
                        mesasInfo = `<span class="capacity-fill" style="color: #999;">Sin mesas asignadas</span>`;
                    }

                    html += `
                        <div class="event-card">
                            <div class="event-header">
                                <div class="event-info">
                                    <h4>${evento.nombre}</h4>
                                    <span class="event-date">${evento.fecha}</span>
                                </div>
                                <div class="event-actions">
                                    <button class="btn-icon-action export" title="Exportar a PDF" onclick="exportarEvento(${evento.id}, '${evento.nombre}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                    </button>
                                    <button class="btn-icon-action edit" title="Editar" onclick="abrirEditarEvento(${evento.id}, '${evento.nombre}', '${evento.fecha}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <button class="btn-icon-action delete" title="Eliminar" onclick="abrirEliminarEvento(${evento.id}, '${evento.nombre}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="capacity-badge">
                                ${mesasInfo}
                            </div>
                        </div>
                    `;
                }
            }

            wrapper.innerHTML = html;
        } catch (error) {
            console.error('[Registro] Error cargando capacidad:', error);
        }
    }

    async function cargarMesas() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const wrapper = document.querySelector('.mesas-scroll-wrapper');
            let html = '';

            for (const evento of eventos) {
                const mesasResp = await fetch(`/api/mesas/evento/${evento.id}`);
                const mesas = await mesasResp.json();

                // Validar que mesas sea un array
                const mesasArray = Array.isArray(mesas) ? mesas : [];

                for (const mesa of mesasArray) {
                    // Contar participantes en esta mesa
                    const participantesResp = await fetch(`/api/participantes/evento/${evento.id}`);
                    const participantesData = await participantesResp.json();

                    // Extraer data del objeto {success: true, data: [...]}
                    const todosParticipantes = participantesData.success ? participantesData.data : (Array.isArray(participantesData) ? participantesData : []);
                    const participantesArray = Array.isArray(todosParticipantes) ? todosParticipantes : [];

                    // Contar participantes filtrando por número de mesa
                    // Ya que mesa_silla viene como "Mesa 1 - Silla 1", extraemos el número
                    const ocupados = participantesArray.filter(p => {
                        if (p.mesa_silla) {
                            const match = p.mesa_silla.match(/Mesa (\d+)/);
                            if (match) {
                                return match[1] === String(mesa.numero);
                            }
                        }
                        return false;
                    }).length;

                    const libres = (parseInt(mesa.capacidad) || 0) - ocupados;

                    html += `
                        <div class="mesa-card">
                            <div class="mesa-header">
                                <span class="mesa-number">Mesa ${mesa.numero}</span>
                                <span class="mesa-event">${evento.nombre}</span>
                                <div class="mesa-actions">
                                    <button class="btn-icon-action edit" title="Editar"
                                            onclick="openEditMesaModal(${mesa.id}, ${mesa.numero}, '${evento.nombre}', ${evento.id}, ${mesa.capacidad}, ${ocupados})">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <button class="btn-icon-action delete" title="Eliminar" onclick="eliminarMesa(${mesa.id}, '${evento.nombre}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mesa-details">
                                <span class="mesa-date">${evento.fecha}</span>
                                <div class="capacity-indicator">
                                    <span class="capacity-fill">Libres: ${libres}</span>
                                    <span class="capacity-total">Ocupados: ${ocupados}/${mesa.capacidad}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            if (html === '') {
                html += '<p style="text-align:center; color:#999; padding:1rem;">No hay mesas registradas</p>';
            }

            wrapper.innerHTML = html;
        } catch (error) {
            console.error('[Registro] Error cargando mesas:', error);
        }
    }

    async function cargarDisposicionMesas() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const tbody = document.querySelector('.disposition-table tbody');
            tbody.innerHTML = '';

            for (const evento of eventos) {
                const participantesResp = await fetch(`/api/participantes/evento/${evento.id}`);
                const result = await participantesResp.json();

                // El API puede devolver {success: true, data: [...]} o directamente [...]
                const participantes = result.success ? result.data : (Array.isArray(result) ? result : []);

                participantes.forEach(p => {
                    // Usar codigo_participante en lugar de codigo_socio
                    const codigo = p.codigo_participante || p.codigo_socio || 'N/A';
                    const tipo = codigo.includes('-INV') ? 'invitado' :
                                 (codigo.includes('-') ? 'familiar' : 'socio');

                    // Usar mesa_silla directamente si existe, sino construirlo
                    const mesaSilla = p.mesa_silla ||
                                     (p.mesa_numero ? `Mesa ${p.mesa_numero} - Silla ${p.numero_silla || 'N/A'}` : 'No asignado');

                    const row = `
                        <tr>
                            <td>${codigo}</td>
                            <td>${p.nombre}</td>
                            <td><span class="badge-type ${tipo}">${tipo}</span></td>
                            <td>${mesaSilla}</td>
                            <td>
                                <button class="btn-remove" onclick="eliminarParticipante(${p.id}, '${p.nombre}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            }

            if (tbody.innerHTML === '') {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#999;">No hay participantes registrados</td></tr>';
            }
        } catch (error) {
            console.error('[Registro] Error cargando disposición:', error);
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#e74c3c;">Error al cargar participantes</td></tr>';
        }
    }

    // Funciones para eliminar
    async function eliminarMesa(mesaId, eventoNombre) {
        if (!confirm(`¿Está seguro de eliminar esta mesa del evento "${eventoNombre}"?`)) {
            return;
        }

        try {
            const response = await fetch(`/api/mesas/${mesaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Error al eliminar');

            alert('Mesa eliminada exitosamente');
            cargarMesas();
            cargarCapacidadEventos();
        } catch (error) {
            console.error('[Registro] Error eliminando mesa:', error);
            alert('Error al eliminar la mesa');
        }
    }

    async function eliminarParticipante(participanteId, nombre) {
        if (!confirm(`¿Está seguro de eliminar a "${nombre}"?`)) {
            return;
        }

        try {
            const response = await fetch(`/api/participantes/${participanteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Error al eliminar');

            alert('Participante eliminado exitosamente');
            cargarDisposicionMesas();
            cargarCapacidadEventos();
            cargarMesas();
        } catch (error) {
            console.error('[Registro] Error eliminando participante:', error);
            alert('Error al eliminar el participante');
        }
    }

    // Cargar mesas cuando se selecciona un evento
    document.getElementById('evento')?.addEventListener('change', async function() {
        const eventoId = this.value;
        const selectMesa = document.getElementById('mesa');
        const selectSilla = document.getElementById('n_silla');

        selectMesa.innerHTML = '<option value="">Seleccione mesa</option>';
        selectSilla.innerHTML = '<option value="">Primero seleccione mesa</option>';

        if (!eventoId) return;

        try {
            const response = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesas = await response.json();

            mesas.forEach(mesa => {
                const option = document.createElement('option');
                option.value = mesa.id;
                option.textContent = `Mesa ${mesa.numero} (${mesa.ocupados || 0}/${mesa.capacidad})`;
                selectMesa.appendChild(option);
            });
        } catch (error) {
            console.error('[Registro] Error cargando mesas del evento:', error);
        }
    });

    // Cargar sillas disponibles cuando se selecciona una mesa
    async function cargarSillasDisponibles() {
        const mesaId = document.getElementById('mesa').value;
        const eventoId = document.getElementById('evento').value;
        const selectSilla = document.getElementById('n_silla');

        selectSilla.innerHTML = '<option value="">Seleccione silla</option>';

        if (!mesaId || !eventoId) {
            selectSilla.innerHTML = '<option value="">Primero seleccione mesa</option>';
            return;
        }

        try {
            // Obtener la mesa para saber su capacidad
            const mesaResponse = await fetch(`/api/mesas/${mesaId}`);

            if (!mesaResponse.ok) {
                throw new Error('Error al obtener mesa');
            }

            const mesaData = await mesaResponse.json();

            // La mesa puede venir directamente o dentro de un objeto
            const capacidad = parseInt(mesaData.capacidad) || 0;

            // Obtener participantes del evento para saber qué sillas están ocupadas
            const participantesResponse = await fetch(`/api/participantes/evento/${eventoId}`);

            if (!participantesResponse.ok) {
                throw new Error('Error al obtener participantes');
            }

            const participantesData = await participantesResponse.json();
            const participantesArray = Array.isArray(participantesData?.data) ? participantesData.data :
                                      Array.isArray(participantesData) ? participantesData : [];

            // Obtener el número de la mesa seleccionada
            const mesaNumero = mesaData.numero || (mesaData.data && mesaData.data.numero);

            // Filtrar participantes de esta mesa usando el campo mesa_silla
            const sillasOcupadas = [];
            participantesArray.forEach(p => {
                if (p.mesa_silla) {
                    const match = p.mesa_silla.match(/Mesa (\d+) - Silla (\d+)/);
                    if (match && parseInt(match[1]) === parseInt(mesaNumero)) {
                        sillasOcupadas.push(parseInt(match[2]));
                    }
                }
            });

            // Generar opciones solo para sillas disponibles
            for (let i = 1; i <= capacidad; i++) {
                if (!sillasOcupadas.includes(i)) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `Silla ${i}`;
                    selectSilla.appendChild(option);
                }
            }

            if (selectSilla.options.length === 1) {
                selectSilla.innerHTML = '<option value="">No hay sillas disponibles</option>';
            }
        } catch (error) {
            console.error('[Registro] Error cargando sillas disponibles:', error);
            selectSilla.innerHTML = '<option value="">Error al cargar sillas</option>';
        }
    }

    // Funciones para modales
    function openEventModal() {
        document.getElementById('modalNuevoEvento').classList.add('show');
    }

    function closeEventModal() {
        document.getElementById('modalNuevoEvento').classList.remove('show');
    }

    function openMesaModal() {
        document.getElementById('modalNuevaMesa').classList.add('show');
    }

    function closeMesaModal() {
        document.getElementById('modalNuevaMesa').classList.remove('show');
    }

    // Auto-asignar número de mesa
    async function asignarNumeroMesaAutomatico() {
        const eventoId = document.getElementById('evento_mesa').value;
        const numeroMesaInput = document.getElementById('numero_mesa');

        if (!eventoId) {
            numeroMesaInput.value = '';
            return;
        }

        try {
            const response = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesas = await response.json();

            // Encontrar el número más alto y sumar 1
            const maxNumero = mesas.length > 0
                ? Math.max(...mesas.map(m => parseInt(m.numero) || 0))
                : 0;

            numeroMesaInput.value = maxNumero + 1;
        } catch (error) {
            console.error('[Auto-asignar Mesa] Error:', error);
            numeroMesaInput.value = '1';
        }
    }

    // Función para actualizar selector de eventos en Entrada Evento
    async function cargarEventosEnSelectorEntradaEvento() {
        try {
            const select = document.getElementById('evento_selector');
            if (!select) return; // No estamos en la pestaña de Entrada Evento

            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const valorActual = select.value;
            select.innerHTML = '<option value="">Seleccione un evento</option>';

            eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = evento.id;
                option.textContent = `${evento.nombre} - ${evento.fecha}`;
                select.appendChild(option);
            });

            // Restaurar selección si aún existe
            if (valorActual && eventos.find(e => e.id == valorActual)) {
                select.value = valorActual;
            }
        } catch (error) {
            console.error('[Entrada Evento] Error actualizando selector:', error);
        }
    }

    // Función para actualizar selector de mesas en formulario de registro
    async function cargarMesasEnSelector() {
        const eventoId = document.getElementById('evento')?.value;
        if (!eventoId) return;

        try {
            const response = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesas = await response.json();

            const selectMesa = document.getElementById('mesa');
            const valorActual = selectMesa.value;
            selectMesa.innerHTML = '<option value="">Seleccione una mesa</option>';

            mesas.forEach(mesa => {
                const option = document.createElement('option');
                option.value = mesa.id;
                option.textContent = `Mesa ${mesa.numero} (${mesa.ocupados || 0}/${mesa.capacidad})`;
                selectMesa.appendChild(option);
            });

            // Restaurar selección si aún existe
            if (valorActual && mesas.find(m => m.id == valorActual)) {
                selectMesa.value = valorActual;
            }
        } catch (error) {
            console.error('[Registro] Error actualizando selector de mesas:', error);
        }
    }

    function openEditMesaModal(mesaId, numeroMesa, eventoNombre, eventoId, capacidad, ocupados) {
        const modal = document.getElementById('modalEditarMesa');

        // Guardar el ID de la mesa para el submit
        modal.dataset.mesaId = mesaId;
        modal.dataset.eventoId = eventoId;

        // Llenar los datos del formulario
        document.getElementById('edit_numero_mesa').value = numeroMesa;
        document.getElementById('edit_evento_mesa').innerHTML = `<option value="${eventoId}">${eventoNombre}</option>`;
        document.getElementById('edit_capacidad_mesa').value = capacidad;
        document.getElementById('edit_capacidad_mesa').min = ocupados;

        // Actualizar información
        document.getElementById('edit_min_capacity').textContent = ocupados;
        document.getElementById('edit_occupied_seats').textContent = ocupados + '/' + capacidad;

        modal.classList.add('show');
    }

    function closeEditMesaModal() {
        document.getElementById('modalEditarMesa').classList.remove('show');
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    });

    // Manejar envío de formulario de registro de participante
    document.getElementById('formRegistroParticipante')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            tipo: document.getElementById('tipo').value,
            codigo_socio: document.getElementById('codigo_socio').value,
            dni: document.getElementById('dni').value,
            nombre: document.getElementById('nombre').value,
            n_recibo: document.getElementById('n_recibo').value,
            n_boleta: document.getElementById('n_boleta').value,
            evento_id: document.getElementById('evento').value,
            mesa_id: document.getElementById('mesa').value,
            numero_silla: document.getElementById('n_silla').value
        };

        console.log('[Registro] Formulario enviado:', formData);

        try {
            const response = await fetch('/api/participantes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al registrar');
            }

            alert('Participante registrado exitosamente');

            // Limpiar formulario usando la función dedicada
            limpiarFormularioRegistro();

            // Recargar datos
            cargarDisposicionMesas();
            cargarCapacidadEventos();
            cargarMesas();
        } catch (error) {
            console.error('[Registro] Error:', error);
            alert('Error al registrar participante: ' + error.message);
        }
    });

    // Manejar envío de formulario de nuevo evento
    document.getElementById('formNuevoEvento')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            nombre: document.getElementById('nombre_evento').value,
            fecha: document.getElementById('fecha_evento').value,
            area: document.getElementById('area_evento').value
        };

        console.log('[Nuevo Evento] Formulario enviado:', formData);

        try {
            const response = await fetch('/api/eventos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al crear');
            }

            alert('Evento creado exitosamente');
            closeEventModal();
            document.getElementById('formNuevoEvento').reset();

            // Recargar todos los selectores y secciones
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();

            // Actualizar selector de entrada evento
            await cargarEventosEnSelectorEntradaEvento();
        } catch (error) {
            console.error('[Nuevo Evento] Error:', error);
            alert('Error al crear evento: ' + error.message);
        }
    });

    // Manejar envío de formulario de nueva mesa
    document.getElementById('formNuevaMesa')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            numero_mesa: document.getElementById('numero_mesa').value,
            evento_id: document.getElementById('evento_mesa').value,
            capacidad: document.getElementById('capacidad_mesa').value
        };

        console.log('[Nueva Mesa] Formulario enviado:', formData);

        try {
            const response = await fetch('/api/mesas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al crear');
            }

            alert('Mesa creada exitosamente');
            closeMesaModal();
            document.getElementById('formNuevaMesa').reset();

            // Recargar todos los selectores y secciones
            await cargarMesas();
            await cargarCapacidadEventos();
            await cargarMesasEnSelector();
        } catch (error) {
            console.error('[Nueva Mesa] Error:', error);
            alert('Error al crear mesa: ' + error.message);
        }
    });

    // Manejar envío de formulario de editar mesa
    document.getElementById('formEditarMesa')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const modal = document.getElementById('modalEditarMesa');
        const mesaId = modal.dataset.mesaId;
        const capacidad = document.getElementById('edit_capacidad_mesa').value;
        const minCapacity = parseInt(document.getElementById('edit_capacidad_mesa').min);

        // Validar capacidad mínima
        if (parseInt(capacidad) < minCapacity) {
            alert(`La capacidad no puede ser menor a ${minCapacity} (sillas ocupadas actualmente)`);
            return;
        }

        const formData = {
            capacidad: capacidad
        };

        console.log('[Editar Mesa] Formulario enviado:', formData);

        try {
            const response = await fetch(`/api/mesas/${mesaId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al actualizar');
            }

            alert('Mesa actualizada exitosamente');
            closeEditMesaModal();

            // Recargar datos
            cargarMesas();
            cargarCapacidadEventos();
        } catch (error) {
            console.error('[Editar Mesa] Error:', error);
            alert('Error al actualizar mesa: ' + error.message);
        }
    });

    // ========== AUTOCOMPLETADO DE SOCIO ==========

    let socioDataGlobal = null;

    function configurarAutocompletadoSocio() {
        const tipoSelect = document.getElementById('tipo');
        const codigoInput = document.getElementById('codigo_socio');
        const btnBuscar = document.getElementById('btn_buscar_socio');
        const dniInput = document.getElementById('dni');
        const nombreInput = document.getElementById('nombre');
        const invitadoMensaje = document.getElementById('invitado_mensaje');
        const errorMensaje = document.getElementById('error_codigo');
        const spinnerCodigo = document.getElementById('spinner_codigo');

        let timeoutId = null;

        // Listener para cambio de tipo
        tipoSelect?.addEventListener('change', function() {
            // Limpiar campos al cambiar tipo
            codigoInput.value = '';
            dniInput.value = '';
            nombreInput.value = '';
            invitadoMensaje.classList.remove('show');
            errorMensaje.style.display = 'none';
            socioDataGlobal = null;

            // Mostrar/ocultar botón según tipo
            if (this.value === 'socio') {
                btnBuscar.style.display = 'flex';
            } else {
                btnBuscar.style.display = 'none';
            }
        });

        // Listener para el botón de búsqueda
        btnBuscar?.addEventListener('click', function() {
            const codigo = codigoInput.value.trim();
            if (codigo && codigo.length >= 4) {
                buscarSocioYMostrarModal(codigo);
            }
        });

        // Listener para código de socio (con debounce) - solo para invitados
        codigoInput?.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const codigo = this.value.trim();
            const tipo = tipoSelect.value;

            // Limpiar mensajes
            invitadoMensaje.classList.remove('show');
            errorMensaje.style.display = 'none';

            if (!codigo || codigo.length < 4) {
                return;
            }

            // Solo auto-búsqueda para invitados
            if (tipo === 'invitado') {
                timeoutId = setTimeout(async () => {
                    await mostrarNombreSocioInvitado(codigo);
                }, 800);
            }
        });

        // Enter en el input ejecuta búsqueda si es socio
        codigoInput?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && tipoSelect.value === 'socio') {
                e.preventDefault();
                const codigo = this.value.trim();
                if (codigo && codigo.length >= 4) {
                    buscarSocioYMostrarModal(codigo);
                }
            }
        });
    }

    async function buscarSocioYMostrarModal(codigo) {
        const spinnerCodigo = document.getElementById('spinner_codigo');
        const btnBuscar = document.getElementById('btn_buscar_socio');
        const errorMensaje = document.getElementById('error_codigo');
        const errorTexto = document.getElementById('error_codigo_texto');

        // Mostrar spinner
        spinnerCodigo.style.display = 'block';
        btnBuscar.disabled = true;
        errorMensaje.style.display = 'none';

        try {
            const response = await fetch(`/api/socios-externos/buscar/${codigo}`);

            if (!response.ok) {
                throw new Error('Socio no encontrado');
            }

            const data = await response.json();
            socioDataGlobal = data;

            // Ocultar spinner
            spinnerCodigo.style.display = 'none';
            btnBuscar.disabled = false;

            // Mostrar modal con opciones
            mostrarModalSeleccionSocio(data);

        } catch (error) {
            console.error('[Autocompletado] Error buscando socio:', error);

            // Ocultar spinner
            spinnerCodigo.style.display = 'none';
            btnBuscar.disabled = false;

            // Mostrar mensaje de error
            errorTexto.textContent = `Código "${codigo}" no encontrado. Por favor, verifique e intente nuevamente.`;
            errorMensaje.style.display = 'flex';
        }
    }

    function mostrarModalSeleccionSocio(data) {
        const modal = document.getElementById('modalSeleccionSocio');
        const container = modal.querySelector('.selector-personas-container');

        container.innerHTML = '';

        // Agregar socio principal
        const socioPrincipal = data.socio_principal;
        const cardPrincipal = document.createElement('div');
        cardPrincipal.className = 'persona-card';
        cardPrincipal.innerHTML = `
            <div class="persona-info">
                <div class="persona-nombre">${socioPrincipal.nombre}</div>
                <div class="persona-codigo">Código: ${socioPrincipal.codigo}</div>
                <div class="persona-dni">DNI: ${socioPrincipal.dni}</div>
                <span class="persona-principal-badge">SOCIO PRINCIPAL</span>
            </div>
        `;
        cardPrincipal.onclick = () => seleccionarPersona(socioPrincipal);
        container.appendChild(cardPrincipal);

        // Agregar familiares
        if (data.familiares && data.familiares.length > 0) {
            data.familiares.forEach(familiar => {
                const cardFamiliar = document.createElement('div');
                cardFamiliar.className = 'persona-card';
                cardFamiliar.innerHTML = `
                    <div class="persona-info">
                        <div class="persona-nombre">${familiar.nombre}</div>
                        <div class="persona-codigo">Código: ${familiar.codigo}</div>
                        <div class="persona-dni">DNI: ${familiar.dni}</div>
                        <span class="persona-parentesco">${familiar.parentesco} - ${familiar.edad} años</span>
                    </div>
                `;
                cardFamiliar.onclick = () => seleccionarPersona(familiar);
                container.appendChild(cardFamiliar);
            });
        }

        // Mostrar modal
        modal.classList.add('show');
    }

    function seleccionarPersona(persona) {
        // Llenar campos del formulario
        document.getElementById('codigo_socio').value = persona.codigo;
        document.getElementById('dni').value = persona.dni;
        document.getElementById('nombre').value = persona.nombre;

        // Cerrar modal
        closeSeleccionSocioModal();
    }

    async function mostrarNombreSocioInvitado(codigo) {
        const spinnerCodigo = document.getElementById('spinner_codigo');
        const errorMensaje = document.getElementById('error_codigo');
        const errorTexto = document.getElementById('error_codigo_texto');
        const mensajeDiv = document.getElementById('invitado_mensaje');
        const nombreSpan = document.getElementById('invitado_de_nombre');

        // Extraer código base (sin sufijo -INV)
        const codigoBase = codigo.split('-')[0];

        // Mostrar spinner
        spinnerCodigo.style.display = 'block';
        errorMensaje.style.display = 'none';
        mensajeDiv.classList.remove('show');

        try {
            const response = await fetch(`/api/socios-externos/nombre/${codigoBase}`);

            if (!response.ok) {
                throw new Error('Socio no encontrado');
            }

            const data = await response.json();

            // Ocultar spinner
            spinnerCodigo.style.display = 'none';

            // Mostrar mensaje debajo del input
            nombreSpan.textContent = data.nombre;
            mensajeDiv.classList.add('show');

        } catch (error) {
            console.error('[Autocompletado] Error buscando nombre del socio:', error);

            // Ocultar spinner
            spinnerCodigo.style.display = 'none';

            // Mostrar mensaje de error
            errorTexto.textContent = `Código de socio "${codigoBase}" no encontrado. El invitado debe proporcionar un código válido.`;
            errorMensaje.style.display = 'flex';
        }
    }

    function closeSeleccionSocioModal() {
        document.getElementById('modalSeleccionSocio').classList.remove('show');
    }

    // Función para limpiar formulario de registro
    function limpiarFormularioRegistro() {
        // Limpiar todos los campos del formulario
        document.getElementById('tipo').value = 'socio';
        document.getElementById('codigo_socio').value = '';
        document.getElementById('dni').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('n_recibo').value = '';
        document.getElementById('n_boleta').value = '';
        document.getElementById('evento').value = '';
        document.getElementById('mesa').value = '';
        document.getElementById('n_silla').value = '';

        // Limpiar selectores de mesa y silla
        document.getElementById('mesa').innerHTML = '<option value="">Seleccione mesa</option>';
        document.getElementById('n_silla').innerHTML = '<option value="">Primero seleccione mesa</option>';

        // Ocultar mensajes y errores
        document.getElementById('invitado_mensaje')?.classList.remove('show');
        document.getElementById('error_codigo').style.display = 'none';
        document.getElementById('btn_buscar_socio').style.display = 'none';

        // Limpiar datos globales
        socioDataGlobal = null;

        console.log('[Registro] Formulario limpiado');
    }

    // ===== FUNCIONES PARA EDITAR EVENTO =====
    function abrirEditarEvento(eventoId, nombre, fecha) {
        document.getElementById('edit_evento_id').value = eventoId;
        document.getElementById('edit_nombre_evento').value = nombre;
        document.getElementById('edit_fecha_evento').value = fecha;
        document.getElementById('modalEditarEvento').classList.add('show');
    }

    function closeEditEventoModal() {
        document.getElementById('modalEditarEvento').classList.remove('show');
    }

    document.getElementById('formEditarEvento')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const eventoId = document.getElementById('edit_evento_id').value;
        const formData = {
            nombre: document.getElementById('edit_nombre_evento').value,
            fecha: document.getElementById('edit_fecha_evento').value
        };

        try {
            const response = await fetch(`/api/eventos/${eventoId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Error al actualizar');
            }

            alert('Evento actualizado exitosamente');
            closeEditEventoModal();

            // Recargar datos
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();
        } catch (error) {
            console.error('[Editar Evento] Error:', error);
            alert('Error al actualizar evento: ' + error.message);
        }
    });

    // ===== FUNCIONES PARA ELIMINAR EVENTO =====
    let eventoIdToDelete = null;

    async function abrirEliminarEvento(eventoId, nombreEvento) {
        eventoIdToDelete = eventoId;

        // Obtener información del evento
        try {
            const [mesasResp, participantesResp] = await Promise.all([
                fetch(`/api/mesas/evento/${eventoId}`),
                fetch(`/api/participantes/evento/${eventoId}`)
            ]);

            const mesas = await mesasResp.json();
            const participantesData = await participantesResp.json();
            const participantes = participantesData.success ? participantesData.data : (Array.isArray(participantesData) ? participantesData : []);

            // Actualizar modal
            document.getElementById('delete_evento_nombre').textContent = nombreEvento;
            document.getElementById('delete_mesas_count').textContent = Array.isArray(mesas) ? mesas.length : 0;
            document.getElementById('delete_participantes_count').textContent = Array.isArray(participantes) ? participantes.length : 0;

            document.getElementById('modalEliminarEvento').classList.add('show');
        } catch (error) {
            console.error('[Eliminar Evento] Error:', error);
            alert('Error al obtener información del evento');
        }
    }

    function closeEliminarEventoModal() {
        document.getElementById('modalEliminarEvento').classList.remove('show');
        eventoIdToDelete = null;
    }

    async function confirmarEliminarEvento() {
        if (!eventoIdToDelete) return;

        try {
            const response = await fetch(`/api/eventos/${eventoIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Error al eliminar');
            }

            alert('Evento eliminado exitosamente');
            closeEliminarEventoModal();

            // Recargar datos
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();
            await cargarDisposicionMesas();
        } catch (error) {
            console.error('[Eliminar Evento] Error:', error);
            alert('Error al eliminar evento: ' + error.message);
        }
    }

    // ===== FUNCIÓN PARA EXPORTAR EVENTO A PDF =====
    function exportarEvento(eventoId, nombreEvento) {
        // Abrir la URL de exportación en una nueva pestaña
        window.open(`/api/eventos/${eventoId}/exportar`, '_blank');
    }
</script>

