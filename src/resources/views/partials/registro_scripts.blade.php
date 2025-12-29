<script>
    // Sistema de notificaciones
    function mostrarNotificacion(mensaje, tipo = 'success', titulo = null) {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        notification.className = `notification ${tipo}`;

        // Títulos por defecto según el tipo
        const titulos = {
            'success': titulo || '✓ Éxito',
            'error': titulo || '✗ Error',
            'warning': titulo || '⚠ Advertencia',
            'info': titulo || 'ℹ Información'
        };

        // Iconos SVG según tipo
        const iconos = {
            'success': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>',
            'error': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            'warning': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'info': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        notification.innerHTML = `
            <div class="notification-icon">
                ${iconos[tipo]}
            </div>
            <div class="notification-content">
                <div class="notification-title">${titulos[tipo]}</div>
                <div class="notification-message">${mensaje}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(notification);

        // Auto-eliminar después de 3 segundos
        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Funciones para manejar estado de carga en botones
    function setButtonLoading(button, isLoading) {
        if (!button) return;

        if (isLoading) {
            button.disabled = true;
            button.dataset.originalContent = button.innerHTML;
            button.innerHTML = `
                <div class="spinner-busqueda" style="display:inline-block; width: 14px; height: 14px; margin-right: 6px; vertical-align: middle;"></div>
                <span>Procesando...</span>
            `;
        } else {
            button.disabled = false;
            if (button.dataset.originalContent) {
                button.innerHTML = button.dataset.originalContent;
                delete button.dataset.originalContent;
            }
        }
    }

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

        // Mostrar spinners iniciales
        const capacityWrapper = document.querySelector('.capacity-scroll-wrapper');
        const mesasWrapper = document.querySelector('.mesas-scroll-wrapper');

        capacityWrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando eventos...</p></div>';
        mesasWrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando mesas...</p></div>';

        // Cargar datos iniciales
        cargarEventosEnSelectores();
        cargarCapacidadEventos();
        cargarMesas();
        cargarDisposicionMesas();
        cargarFiltroEventos();

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
                // Formatear fecha(s)
                let fechaDisplay;
                if (evento.fecha && evento.fecha_fin) {
                    if (evento.fecha === evento.fecha_fin) {
                        fechaDisplay = evento.fecha;
                    } else {
                        fechaDisplay = `${evento.fecha} - ${evento.fecha_fin}`;
                    }
                } else {
                    fechaDisplay = evento.fecha || 'Sin fecha';
                }

                const option1 = document.createElement('option');
                option1.value = evento.id;
                option1.textContent = `${evento.nombre} - ${fechaDisplay}`;
                selectRegistro.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = evento.id;
                option2.textContent = `${evento.nombre} - ${fechaDisplay}`;
                selectMesa.appendChild(option2);
            });
        } catch (error) {
            console.error('[Registro] Error cargando eventos:', error);
        }
    }

    async function cargarCapacidadEventos(mostrarSpinner = false) {
        try {
            const wrapper = document.querySelector('.capacity-scroll-wrapper');

            if (mostrarSpinner) {
                wrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando eventos...</p></div>';
            }

            // Llamada optimizada: una sola petición en lugar de múltiples
            const response = await fetch('/api/eventos/capacidad-todos');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Error al cargar eventos');
            }

            const eventos = result.data;
            let html = '';

            if (eventos.length === 0) {
                html += '<p style="text-align:center; color:#999; padding:1rem;">No hay eventos registrados</p>';
            } else {
                for (const evento of eventos) {
                    const capacidadTotal = evento.capacidad_total || 0;
                    const ocupados = evento.ocupados || 0;
                    const libres = evento.libres || 0;
                    const totalMesas = evento.total_mesas || 0;

                    // Mostrar evento incluso si no tiene mesas
                    let mesasInfo;
                    if (totalMesas > 0) {
                        mesasInfo = `<span class="capacity-fill">Libres: ${libres}</span>
                                     <span class="capacity-total">Ocupados: ${ocupados}/${capacidadTotal}</span>`;
                    } else {
                        mesasInfo = `<span class="capacity-fill" style="color: #999;">Sin mesas asignadas</span>`;
                    }

                    // Formatear fecha(s) para mostrar
                    let fechaDisplay;
                    if (evento.fecha && evento.fecha_fin) {
                        if (evento.fecha === evento.fecha_fin) {
                            fechaDisplay = evento.fecha;
                        } else {
                            fechaDisplay = `${evento.fecha} - ${evento.fecha_fin}`;
                        }
                    } else {
                        fechaDisplay = evento.fecha || 'Sin fecha';
                    }

                    html += `
                        <div class="event-card">
                            <div class="event-header">
                                <div class="event-info">
                                    <h4>${evento.nombre}</h4>
                                    <span class="event-date">${fechaDisplay}</span>
                                </div>
                                <div class="event-actions">
                                    <button class="btn-icon-action view" title="Ver Disposición" onclick="abrirDisposicionEvento(${evento.id}, '${evento.nombre}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <div class="export-dropdown">
                                        <button class="btn-icon-action export" title="Exportar" onclick="toggleExportMenu(event, ${evento.id}, '${evento.nombre}')">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                        </button>
                                        <div class="export-menu" id="export-menu-${evento.id}">
                                            <button onclick="exportarEvento(${evento.id}, '${evento.nombre}', 'pdf')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                </svg>
                                                PDF
                                            </button>
                                            <button onclick="exportarEvento(${evento.id}, '${evento.nombre}', 'excel')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                                    <polyline points="10 9 9 9 8 9"/>
                                                </svg>
                                                Excel
                                            </button>
                                        </div>
                                    </div>
                                    <button class="btn-icon-action edit" title="Editar" onclick="abrirEditarEvento(${evento.id}, '${evento.nombre}', '${evento.fecha}', '${evento.fecha_fin || ''}')">
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
            console.error('[Registro] Error cargando capacidad de eventos:', error);
            const wrapper = document.querySelector('.capacity-scroll-wrapper');
            if (wrapper) {
                wrapper.innerHTML = '<p style="text-align:center; color:#e74c3c; padding:1rem;">Error al cargar los eventos. Intente nuevamente.</p>';
            }
        }
    }

    async function cargarMesas(mostrarSpinner = false) {
        try {
            const wrapper = document.querySelector('.mesas-scroll-wrapper');

            if (mostrarSpinner) {
                wrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando mesas...</p></div>';
            }

            // Llamada optimizada: una sola petición en lugar de múltiples
            const response = await fetch('/api/mesas/todas');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Error al cargar mesas');
            }

            const mesas = result.data;
            
            // Ordenar mesas numéricamente por número de mesa
            mesas.sort((a, b) => parseInt(a.numero) - parseInt(b.numero));
            
            let html = '';

            for (const mesa of mesas) {
                const ocupados = mesa.ocupados || 0;
                const libres = mesa.disponibles || 0;

                html += `
                    <div class="mesa-card">
                        <div class="mesa-header">
                            <span class="mesa-number">Mesa ${mesa.numero}</span>
                            <span class="mesa-event">${mesa.evento.nombre}</span>
                            <div class="mesa-actions">
                                <button class="btn-icon-action edit" title="Editar"
                                        onclick="openEditMesaModal(${mesa.id}, ${mesa.numero}, '${mesa.evento.nombre}', ${mesa.evento.id}, ${mesa.capacidad}, ${ocupados})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button class="btn-icon-action delete" title="Eliminar" onclick="eliminarMesa(${mesa.id}, '${mesa.evento.nombre}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mesa-details">
                            <span class="mesa-date">${mesa.evento.fecha}</span>
                            <div class="capacity-indicator">
                                <span class="capacity-fill">Libres: ${libres}</span>
                                <span class="capacity-total">Ocupados: ${ocupados}/${mesa.capacidad}</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            if (html === '') {
                html += '<p style="text-align:center; color:#999; padding:1rem;">No hay mesas registradas</p>';
            } else {
                // Añadir botón flotante para crear mesa individual
                html += `
                    <div style="position: sticky; bottom: 10px; text-align: center; margin-top: 1rem;">
                        <button onclick="abrirMesaIndividualModal()" class="btn-create" style="width: auto; padding: 0.6rem 1.5rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Agregar Mesa Individual
                        </button>
                    </div>
                `;
            }

            wrapper.innerHTML = html;
        } catch (error) {
            console.error('[Registro] Error cargando mesas:', error);
            const wrapper = document.querySelector('.mesas-scroll-wrapper');
            if (wrapper) {
                wrapper.innerHTML = '<p style="text-align:center; color:#e74c3c; padding:1rem;">Error al cargar las mesas. Intente nuevamente.</p>';
            }
        }
    }

    async function cargarDisposicionMesas() {
        // Esta función ahora se mantiene solo para compatibilidad
        // La disposición se mostrará en el modal por evento
    }

    // Cargar eventos en el filtro de gestión de mesas
    async function cargarFiltroEventos() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const select = document.getElementById('filtro_evento_mesas');
            if (!select) return;

            // Mantener opción "Todos"
            select.innerHTML = '<option value="">Todos los eventos</option>';

            eventos.forEach(evento => {
                // Formatear fecha(s)
                let fechaDisplay;
                if (evento.fecha && evento.fecha_fin) {
                    if (evento.fecha === evento.fecha_fin) {
                        fechaDisplay = evento.fecha;
                    } else {
                        fechaDisplay = `${evento.fecha} - ${evento.fecha_fin}`;
                    }
                } else {
                    fechaDisplay = evento.fecha || 'Sin fecha';
                }

                const option = document.createElement('option');
                option.value = evento.id;
                option.textContent = `${evento.nombre} - ${fechaDisplay}`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('[Registro] Error cargando filtro eventos:', error);
        }
    }

    // Filtrar mesas por evento seleccionado
    async function filtrarMesasPorEvento() {
        try {
            const eventoId = document.getElementById('filtro_evento_mesas')?.value;
            const wrapper = document.querySelector('.mesas-scroll-wrapper');
            let html = '';

            if (!eventoId) {
                // Si no hay filtro, mostrar todas las mesas con spinner
                await cargarMesas(true);
                return;
            }

            wrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Filtrando mesas...</p></div>';

            // Obtener evento
            const eventoResp = await fetch('/api/eventos');
            const eventos = await eventoResp.json();
            const evento = eventos.find(e => e.id == eventoId);

            if (!evento) {
                wrapper.innerHTML = '<p style="text-align:center; color:#999; padding:1rem;">Evento no encontrado</p>';
                return;
            }

            // Obtener mesas del evento
            const mesasResp = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesas = await mesasResp.json();
            const mesasArray = Array.isArray(mesas) ? mesas : [];

            if (mesasArray.length === 0) {
                wrapper.innerHTML = '<p style="text-align:center; color:#999; padding:1rem;">No hay mesas para este evento</p>';
                return;
            }

            // Obtener participantes del evento
            const participantesResp = await fetch(`/api/participantes/evento/${eventoId}`);
            const participantesData = await participantesResp.json();
            const todosParticipantes = participantesData.success ? participantesData.data : (Array.isArray(participantesData) ? participantesData : []);

            for (const mesa of mesasArray) {
                const ocupados = todosParticipantes.filter(p => {
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

            // Añadir botón flotante para crear mesa individual
            html += `
                <div style="position: sticky; bottom: 10px; text-align: center; margin-top: 1rem;">
                    <button onclick="abrirMesaIndividualModal()" class="btn-create" style="width: auto; padding: 0.6rem 1.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Agregar Mesa Individual
                    </button>
                </div>
            `;

            wrapper.innerHTML = html;
        } catch (error) {
            console.error('[Registro] Error filtrando mesas:', error);
            const wrapper = document.querySelector('.mesas-scroll-wrapper');
            wrapper.innerHTML = '<p style="text-align:center; color:#e74c3c; padding:1rem;">Error al filtrar mesas</p>';
        }
    }

    // Función para abrir modal de disposición de un evento
    async function abrirDisposicionEvento(eventoId, nombreEvento) {
        try {
            // Guardar referencia del evento actual para poder refrescar después de eliminar
            window.currentDisposicionEvento = { id: eventoId, nombre: nombreEvento };

            const modal = document.getElementById('modalDisposicionEvento');
            document.getElementById('disposicion_evento_nombre').textContent = nombreEvento;

            const tbody = document.querySelector('#modalDisposicionEvento .disposition-table tbody');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;"><div class="spinner-busqueda" style="display:inline-block;"></div> Cargando...</td></tr>';

            modal.style.display = 'flex';

            const participantesResp = await fetch(`/api/participantes/evento/${eventoId}`);
            const result = await participantesResp.json();

            // El API puede devolver {success: true, data: [...]} o directamente [...]
            const participantes = result.success ? result.data : (Array.isArray(result) ? result : []);

            tbody.innerHTML = '';

            if (participantes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#999;">No hay participantes registrados en este evento</td></tr>';
                return;
            }

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
        } catch (error) {
            console.error('[Registro] Error cargando disposición:', error);
            const tbody = document.querySelector('#modalDisposicionEvento .disposition-table tbody');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#e74c3c;">Error al cargar participantes</td></tr>';
        }
    }

    function closeDisposicionEventoModal() {
        document.getElementById('modalDisposicionEvento').style.display = 'none';
    }

    // Funciones para eliminar
    async function eliminarMesa(mesaId, eventoNombre) {
        // Mostrar modal de confirmación
        const modal = document.getElementById('confirmDeleteMesaModal');
        document.getElementById('confirmDeleteMesaText').textContent =
            `¿Está seguro de eliminar esta mesa del evento "${eventoNombre}"?`;

        // Guardar referencias para el botón confirmar
        window.pendingDeleteMesa = { mesaId, eventoNombre };
        modal.classList.add('show');
    }

    function closeConfirmDeleteMesaModal() {
        const modal = document.getElementById('confirmDeleteMesaModal');
        modal.classList.remove('show');
        window.pendingDeleteMesa = null;
    }

    async function confirmarEliminarMesa() {
        if (!window.pendingDeleteMesa) return;

        const { mesaId, eventoNombre } = window.pendingDeleteMesa;
        closeConfirmDeleteMesaModal();

        try {
            const response = await fetch(`/api/mesas/${mesaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Error al eliminar');

            mostrarNotificacion('La mesa ha sido eliminada correctamente', 'success');
            cargarMesas();
            cargarCapacidadEventos();
        } catch (error) {
            console.error('[Registro] Error eliminando mesa:', error);
            mostrarNotificacion('No se pudo eliminar la mesa', 'error');
        }
    }

    async function eliminarParticipante(participanteId, nombre) {
        // Mostrar modal de confirmación
        const modal = document.getElementById('confirmDeleteParticipanteModal');
        document.getElementById('confirmDeleteParticipanteText').textContent =
            `¿Está seguro de eliminar al participante "${nombre}"?`;

        // Guardar referencias para el botón confirmar
        window.pendingDeleteParticipante = { participanteId, nombre };
        modal.classList.add('show');
    }

    function closeConfirmDeleteParticipanteModal() {
        const modal = document.getElementById('confirmDeleteParticipanteModal');
        modal.classList.remove('show');
        window.pendingDeleteParticipante = null;
    }

    async function confirmarEliminarParticipante() {
        if (!window.pendingDeleteParticipante) return;

        const { participanteId, nombre } = window.pendingDeleteParticipante;
        closeConfirmDeleteParticipanteModal();

        try {
            const response = await fetch(`/api/participantes/${participanteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Error al eliminar');

            mostrarNotificacion('El participante ha sido eliminado correctamente', 'success');
            cargarDisposicionMesas();
            cargarCapacidadEventos();

            // Verificar si hay filtro activo
            const filtroActivo = document.getElementById('filtro_evento_mesas')?.value;
            if (filtroActivo) {
                await filtrarMesasPorEvento();
            } else {
                cargarMesas();
            }

            // Refrescar el modal de disposición si está abierto
            if (window.currentDisposicionEvento) {
                await abrirDisposicionEvento(
                    window.currentDisposicionEvento.id,
                    window.currentDisposicionEvento.nombre
                );
            }
        } catch (error) {
            console.error('[Registro] Error eliminando participante:', error);
            mostrarNotificacion('No se pudo eliminar el participante', 'error');
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

        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);

        const tipo = document.getElementById('tipo').value;
        const codigoSocio = document.getElementById('codigo_socio').value;

        // Para invitados, generar un código único con timestamp
        let codigoParticipante = codigoSocio;
        if (tipo === 'invitado') {
            const timestamp = Date.now().toString().slice(-6); // Últimos 6 dígitos del timestamp
            codigoParticipante = `${codigoSocio}-INV${timestamp}`;
        }

        const formData = {
            tipo: tipo,
            codigo_socio: codigoSocio,
            codigo_participante: codigoParticipante,
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

            mostrarNotificacion('El participante ha sido registrado correctamente', 'success');

            // Limpiar formulario usando la función dedicada
            limpiarFormularioRegistro();

            // Recargar datos
            cargarDisposicionMesas();
            cargarCapacidadEventos();

            // Verificar si hay filtro activo
            const filtroActivo = document.getElementById('filtro_evento_mesas')?.value;
            if (filtroActivo) {
                await filtrarMesasPorEvento();
            } else {
                cargarMesas();
            }
        } catch (error) {
            console.error('[Registro] Error:', error);
            mostrarNotificacion('Error al registrar: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
        }
    });

    // Manejar envío de formulario de nuevo evento
    document.getElementById('formNuevoEvento')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);

        const formData = {
            nombre: document.getElementById('nombre_evento').value,
            fecha: document.getElementById('fecha_evento').value,
            fecha_fin: document.getElementById('fecha_fin_evento').value || null,
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

            mostrarNotificacion('El evento ha sido creado correctamente', 'success');
            closeEventModal();
            document.getElementById('formNuevoEvento').reset();

            // Mostrar spinner y recargar
            const capacityWrapper = document.querySelector('.capacity-scroll-wrapper');
            capacityWrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Actualizando eventos...</p></div>';

            // Recargar todos los selectores y secciones
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();
            await cargarFiltroEventos();

            // Actualizar selector de entrada evento
            await cargarEventosEnSelectorEntradaEvento();
        } catch (error) {
            console.error('[Nuevo Evento] Error:', error);
            mostrarNotificacion('Error al crear evento: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
        }
    });

    // Manejar envío de formulario de nueva mesa EN MASA
    document.getElementById('formNuevaMesa')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);

        const eventoId = document.getElementById('evento_mesa').value;
        const numeroMesas = parseInt(document.getElementById('numero_mesas').value);
        const totalPersonas = parseInt(document.getElementById('total_personas').value);

        console.log('[Nueva Mesa Masa] Crear', numeroMesas, 'mesas para', totalPersonas, 'personas');

        try {
            // Calcular distribución de sillas
            const sillasPorMesa = Math.floor(totalPersonas / numeroMesas);
            const sillasSobrantes = totalPersonas % numeroMesas;

            // Obtener el último número de mesa del evento
            const mesasResp = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesasExistentes = await mesasResp.json();
            let numeroInicial = mesasExistentes.length > 0
                ? Math.max(...mesasExistentes.map(m => m.numero)) + 1
                : 1;

            // Crear las mesas EN LOTES PARALELOS (más rápido)
            let mesasCreadas = 0;
            const LOTE_SIZE = 5; // Crear 5 mesas a la vez
            console.log(`[Nueva Mesa Masa] Iniciando creación de ${numeroMesas} mesas en lotes de ${LOTE_SIZE}`);

            for (let i = 0; i < numeroMesas; i += LOTE_SIZE) {
                const loteActual = Math.min(LOTE_SIZE, numeroMesas - i);
                const promesas = [];

                console.log(`[Lote ${Math.floor(i/LOTE_SIZE) + 1}] Creando ${loteActual} mesas en paralelo...`);

                for (let j = 0; j < loteActual; j++) {
                    const indice = i + j;
                    const capacidad = indice === numeroMesas - 1
                        ? sillasPorMesa + sillasSobrantes
                        : sillasPorMesa;

                    const mesaData = {
                        numero_mesa: String(numeroInicial + indice),
                        evento_id: eventoId,
                        capacidad: capacidad
                    };

                    promesas.push(
                        fetch('/api/mesas', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(mesaData)
                        }).then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(`Mesa ${numeroInicial + indice}: ${text.substring(0, 100)}`);
                                });
                            }
                            mesasCreadas++;
                            return response.json();
                        })
                    );
                }

                try {
                    await Promise.all(promesas);
                    console.log(`[Lote ${Math.floor(i/LOTE_SIZE) + 1}] ✓ ${loteActual} mesas creadas exitosamente`);
                } catch (error) {
                    console.error(`[Lote ${Math.floor(i/LOTE_SIZE) + 1}] Error:`, error);
                    throw error;
                }
            }

            console.log(`[Nueva Mesa Masa] Completado: ${mesasCreadas} mesas creadas`);
            const filtroActual = document.getElementById('filtro_evento_mesas')?.value;            mostrarNotificacion(`Se crearon ${mesasCreadas} mesas correctamente`, 'success');
            closeMesaModal();
            document.getElementById('formNuevaMesa').reset();

            // Recargar datos
            if (filtroActual) {
                if (filtroActual == eventoId) {
                    const wrapper = document.querySelector('.mesas-scroll-wrapper');
                    wrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando mesas...</p></div>';
                }
                await filtrarMesasPorEvento();
            } else {
                await cargarMesas(true);
            }

            await cargarCapacidadEventos();
            await cargarMesasEnSelector();
        } catch (error) {
            console.error('[Nueva Mesa Masa] Error:', error);
            mostrarNotificacion('Error al crear mesas: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
        }
    });

    // Manejar envío de formulario de editar mesa
    document.getElementById('formEditarMesa')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');

        const modal = document.getElementById('modalEditarMesa');
        const mesaId = modal.dataset.mesaId;
        const capacidad = document.getElementById('edit_capacidad_mesa').value;
        const minCapacity = parseInt(document.getElementById('edit_capacidad_mesa').min);

        // Validar capacidad mínima
        if (parseInt(capacidad) < minCapacity) {
            mostrarNotificacion(`La capacidad no puede ser menor a ${minCapacity} (sillas ocupadas actualmente)`, 'warning');
            return;
        }

        setButtonLoading(submitBtn, true);

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

            mostrarNotificacion('La mesa ha sido actualizada correctamente', 'success');
            closeEditMesaModal();

            // Recargar datos
            cargarMesas();
            cargarCapacidadEventos();
        } catch (error) {
            console.error('[Editar Mesa] Error:', error);
            mostrarNotificacion('Error al actualizar mesa: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
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
        document.getElementById('tipo').value = '';
        document.getElementById('codigo_socio').value = '';
        document.getElementById('dni').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('n_recibo').value = '';
        document.getElementById('n_boleta').value = '';
        document.getElementById('evento').value = '';
        document.getElementById('mesa').value = '';
        document.getElementById('n_silla').value = '';

        // Resetear el selector visual de mesa y silla
        const selector = document.getElementById('mesa_silla_selector');
        const placeholder = selector?.querySelector('.mesa-silla-placeholder');
        if (placeholder) {
            placeholder.textContent = 'Seleccione mesa y silla';
            selector.classList.remove('selected');
        }

        // Ocultar mensajes y errores
        document.getElementById('invitado_mensaje')?.classList.remove('show');
        document.getElementById('error_codigo').style.display = 'none';
        document.getElementById('btn_buscar_socio').style.display = 'none';

        // Limpiar datos globales
        socioDataGlobal = null;

        console.log('[Registro] Formulario limpiado');
    }

    // ===== FUNCIONES PARA EDITAR EVENTO =====
    function abrirEditarEvento(eventoId, nombre, fecha, fechaFin) {
        document.getElementById('edit_evento_id').value = eventoId;
        document.getElementById('edit_nombre_evento').value = nombre;
        document.getElementById('edit_fecha_evento').value = fecha;
        document.getElementById('edit_fecha_fin_evento').value = fechaFin || '';
        document.getElementById('modalEditarEvento').classList.add('show');
    }

    function closeEditEventoModal() {
        document.getElementById('modalEditarEvento').classList.remove('show');
    }

    document.getElementById('formEditarEvento')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);

        const eventoId = document.getElementById('edit_evento_id').value;
        const formData = {
            nombre: document.getElementById('edit_nombre_evento').value,
            fecha: document.getElementById('edit_fecha_evento').value,
            fecha_fin: document.getElementById('edit_fecha_fin_evento').value || null
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

            mostrarNotificacion('El evento ha sido actualizado correctamente', 'success');
            closeEditEventoModal();

            // Recargar datos
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();
        } catch (error) {
            console.error('[Editar Evento] Error:', error);
            mostrarNotificacion('Error al actualizar evento: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
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
            mostrarNotificacion('Error al obtener información del evento', 'error');
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

            mostrarNotificacion('El evento ha sido eliminado correctamente', 'success');
            closeEliminarEventoModal();

            // Recargar datos
            await cargarEventosEnSelectores();
            await cargarCapacidadEventos();
            await cargarMesas();
            await cargarDisposicionMesas();
        } catch (error) {
            console.error('[Eliminar Evento] Error:', error);
            mostrarNotificacion('Error al eliminar evento: ' + error.message, 'error');
        }
    }

    // ===== FUNCIÓN PARA EXPORTAR EVENTO =====
    function exportarEvento(eventoId, nombreEvento, formato = 'pdf') {
        // Cerrar el menú
        const menu = document.getElementById(`export-menu-${eventoId}`);
        if (menu) menu.classList.remove('show');

        // Abrir la URL de exportación en una nueva pestaña
        const url = formato === 'excel'
            ? `/api/eventos/${eventoId}/exportar-excel`
            : `/api/eventos/${eventoId}/exportar`;

        window.open(url, '_blank');
        mostrarNotificacion(`Exportando evento a ${formato.toUpperCase()}...`, 'info');
    }

    function toggleExportMenu(event, eventoId, nombreEvento) {
        event.stopPropagation();

        // Cerrar otros menús abiertos
        document.querySelectorAll('.export-menu.show').forEach(menu => {
            if (menu.id !== `export-menu-${eventoId}`) {
                menu.classList.remove('show');
            }
        });

        // Toggle del menú actual
        const menu = document.getElementById(`export-menu-${eventoId}`);
        menu.classList.toggle('show');
    }

    // Cerrar menús al hacer click fuera
    document.addEventListener('click', function() {
        document.querySelectorAll('.export-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    // ===== FUNCIONES PARA SELECTOR VISUAL DE MESA Y SILLA =====
    let mesaSeleccionada = null;
    let sillaSeleccionada = null;

    async function abrirSelectorMesaSilla() {
        const eventoId = document.getElementById('evento').value;

        if (!eventoId) {
            mostrarNotificacion('Primero debe seleccionar un evento', 'warning');
            return;
        }

        const modal = document.getElementById('modalSelectorMesaSilla');
        const container = document.getElementById('mesas_disponibles_container');

        modal.style.display = 'flex';
        container.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando mesas...</p></div>';

        try {
            // Obtener mesas del evento
            const [mesasResp, participantesResp] = await Promise.all([
                fetch(`/api/mesas/evento/${eventoId}`),
                fetch(`/api/participantes/evento/${eventoId}`)
            ]);

            const mesas = await mesasResp.json();
            const participantesData = await participantesResp.json();
            const participantes = participantesData.success ? participantesData.data : (Array.isArray(participantesData) ? participantesData : []);

            if (!Array.isArray(mesas) || mesas.length === 0) {
                container.innerHTML = '<p style="text-align:center; color:#999; padding:2rem;">No hay mesas disponibles para este evento</p>';
                return;
            }

            let html = '';
            mesas.forEach(mesa => {
                // Filtrar participantes de esta mesa específica
                const participantesMesa = participantes.filter(p => parseInt(p.mesa_id) === parseInt(mesa.id));

                // Usar los datos que vienen del backend
                const ocupados = mesa.ocupados || 0;
                const disponibles = mesa.disponibles || (mesa.capacidad - ocupados);
                const estaLlena = mesa.completa || disponibles === 0;

                console.log(`[Selector] Mesa ${mesa.numero}: ocupados=${ocupados}, capacidad=${mesa.capacidad}, participantesMesa=`, participantesMesa);

                html += `
                    <div class="mesa-card ${estaLlena ? 'disabled' : ''}" onclick="${estaLlena ? '' : `seleccionarMesa(${mesa.id}, '${mesa.numero}', ${mesa.capacidad})`}">
                        <div class="mesa-card-header">
                            <span class="mesa-numero">Mesa ${mesa.numero}</span>
                            <span class="mesa-capacidad ${estaLlena ? 'llena' : ''}">${ocupados}/${mesa.capacidad}</span>
                        </div>
                        <div class="sillas-grid">
                `;

                // Generar las sillas
                for (let i = 1; i <= mesa.capacidad; i++) {
                    const participanteEnSilla = participantesMesa.find(p => parseInt(p.numero_silla) === i);
                    const ocupada = !!participanteEnSilla;

                    html += `
                        <div class="silla-item ${ocupada ? 'ocupada' : ''}"
                             data-mesa-id="${mesa.id}"
                             data-silla="${i}"
                             onclick="event.stopPropagation(); ${ocupada ? '' : `seleccionarSilla(${mesa.id}, ${mesa.numero}, ${i})`}">
                            <span class="silla-numero">${i}</span>
                            <span class="silla-estado">${ocupada ? 'Ocupada' : 'Libre'}</span>
                        </div>
                    `;
                }

                html += '</div>';

                // Mostrar participantes si los hay
                if (participantesMesa.length > 0) {
                    html += `
                        <div class="participantes-preview">
                            <h4>Participantes:</h4>
                    `;
                    participantesMesa.forEach(p => {
                        html += `
                            <div class="participante-item">
                                <span class="participante-nombre">${p.nombre}</span>
                                <span class="participante-silla">Silla ${p.numero_silla}</span>
                            </div>
                        `;
                    });
                    html += '</div>';
                }

                html += '</div>';
            });

            container.innerHTML = html;

        } catch (error) {
            console.error('[Selector Mesa] Error:', error);
            container.innerHTML = '<p style="text-align:center; color:#e74c3c; padding:2rem;">Error al cargar las mesas</p>';
        }
    }

    function seleccionarMesa(mesaId, mesaNumero, capacidad) {
        mesaSeleccionada = { id: mesaId, numero: mesaNumero };
        // No cerrar el modal, permitir que seleccione silla
    }

    function seleccionarSilla(mesaId, mesaNumero, silla) {
        // Actualizar los valores ocultos
        document.getElementById('mesa').value = mesaId;
        document.getElementById('n_silla').value = silla;

        // Actualizar el texto del selector
        const selector = document.getElementById('mesa_silla_selector');
        const placeholder = selector.querySelector('.mesa-silla-placeholder');
        placeholder.textContent = `Mesa ${mesaNumero} - Silla ${silla}`;
        selector.classList.add('selected');

        // Cerrar modal
        closeSelectorMesaSilla();
        mostrarNotificacion(`Seleccionado: Mesa ${mesaNumero}, Silla ${silla}`, 'info');
    }

    function closeSelectorMesaSilla() {
        document.getElementById('modalSelectorMesaSilla').style.display = 'none';
    }

    // ===== FUNCIONES PARA MESA INDIVIDUAL =====
    function abrirMesaIndividualModal() {
        document.getElementById('modalNuevaMesaIndividual').style.display = 'flex';
        cargarEventosEnSelector('evento_mesa_individual');
    }

    function closeMesaIndividualModal() {
        document.getElementById('modalNuevaMesaIndividual').style.display = 'none';
        document.getElementById('formNuevaMesaIndividual').reset();
    }

    // Manejar envío de formulario de nueva mesa individual
    document.getElementById('formNuevaMesaIndividual')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);

        const formData = {
            numero_mesa: document.getElementById('numero_mesa_individual').value,
            evento_id: document.getElementById('evento_mesa_individual').value,
            capacidad: document.getElementById('capacidad_mesa_individual').value
        };

        console.log('[Nueva Mesa Individual] Formulario enviado:', formData);

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

            const eventoIdCreado = formData.evento_id;
            const filtroActual = document.getElementById('filtro_evento_mesas')?.value;

            mostrarNotificacion('La mesa ha sido creada correctamente', 'success');
            closeMesaIndividualModal();

            // Recargar datos
            if (filtroActual) {
                if (filtroActual == eventoIdCreado) {
                    const wrapper = document.querySelector('.mesas-scroll-wrapper');
                    wrapper.innerHTML = '<div style="text-align:center; padding:2rem;"><div class="spinner-busqueda" style="display:inline-block;"></div><p style="color:#999; margin-top:0.5rem;">Cargando mesas...</p></div>';
                }
                await filtrarMesasPorEvento();
            } else {
                await cargarMesas(true);
            }

            await cargarCapacidadEventos();
        } catch (error) {
            console.error('[Nueva Mesa Individual] Error:', error);
            mostrarNotificacion('Error al crear mesa: ' + error.message, 'error');
        } finally {
            setButtonLoading(submitBtn, false);
        }
    });

    // Función auxiliar para cargar eventos en cualquier selector
    async function cargarEventosEnSelector(selectorId) {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();
            const select = document.getElementById(selectorId);

            if (!select) return;

            select.innerHTML = '<option value="">Seleccione un evento</option>';
            eventos.forEach(evento => {
                // Formatear fecha(s)
                let fechaDisplay;
                if (evento.fecha && evento.fecha_fin) {
                    if (evento.fecha === evento.fecha_fin) {
                        fechaDisplay = evento.fecha;
                    } else {
                        fechaDisplay = `${evento.fecha} - ${evento.fecha_fin}`;
                    }
                } else {
                    fechaDisplay = evento.fecha || 'Sin fecha';
                }

                const option = document.createElement('option');
                option.value = evento.id;
                option.textContent = `${evento.nombre} - ${fechaDisplay}`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('[Cargar Eventos] Error:', error);
        }
    }

    // Función para asignar número de mesa automático (individual)
    async function asignarNumeroMesaAutomatico() {
        const eventoId = document.getElementById('evento_mesa_individual')?.value;
        const numeroInput = document.getElementById('numero_mesa_individual');

        if (!eventoId || !numeroInput) return;

        try {
            const response = await fetch(`/api/mesas/evento/${eventoId}`);
            const mesas = await response.json();
            const ultimoNumero = mesas.length > 0 ? Math.max(...mesas.map(m => m.numero)) : 0;
            numeroInput.value = ultimoNumero + 1;
        } catch (error) {
            console.error('[Asignar Número Mesa] Error:', error);
            numeroInput.value = 1;
        }
    }

    // Función para calcular y mostrar previsualización de distribución de mesas
    function calcularDistribucionMesas() {
        const numeroMesas = parseInt(document.getElementById('numero_mesas')?.value) || 0;
        const totalPersonas = parseInt(document.getElementById('total_personas')?.value) || 0;
        const previewDiv = document.getElementById('preview_distribucion');
        const previewText = document.getElementById('preview_text');

        if (numeroMesas <= 0 || totalPersonas <= 0) {
            previewDiv.style.display = 'none';
            return;
        }

        // Calcular distribución
        const sillasPorMesa = Math.floor(totalPersonas / numeroMesas);
        const sillasSobrantes = totalPersonas % numeroMesas;

        let mensaje = '';

        if (sillasSobrantes === 0) {
            // Distribución perfecta
            mensaje = `✓ <strong>${numeroMesas} mesas</strong> con <strong>${sillasPorMesa} sillas</strong> cada una.`;
        } else {
            // Distribución con ajuste
            const mesasNormales = numeroMesas - 1;
            const sillasUltimaMesa = sillasPorMesa + sillasSobrantes;

            mensaje = `✓ <strong>${mesasNormales} mesas</strong> con <strong>${sillasPorMesa} sillas</strong> cada una.<br>`;
            mensaje += `✓ <strong>1 mesa</strong> (la última) con <strong>${sillasUltimaMesa} sillas</strong>.`;
        }

        mensaje += `<br><br><strong>Total: ${totalPersonas} sillas</strong> distribuidas en <strong>${numeroMesas} mesas</strong>.`;

        previewText.innerHTML = mensaje;
        previewDiv.style.display = 'block';
    }
</script>

