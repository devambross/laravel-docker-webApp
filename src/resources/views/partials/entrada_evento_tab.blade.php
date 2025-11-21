<!-- Contenido de la pestaña Entrada Evento -->
<div class="entrada-evento-wrapper">
    <!-- Header con título y botón exportar -->
    <div class="entrada-evento-header">
        <div class="header-text">
            <h2>Control de Asistencia - Evento</h2>
            <p class="header-subtitle">Registre la asistencia de participantes en el evento específico</p>
        </div>
        <button class="btn-export-pdf" onclick="exportarPDFEvento()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
            Exportar PDF
        </button>
    </div>

    <!-- Seleccionar Evento y búsquedas -->
    <div class="search-controls-row">
        <div class="search-group selector-evento">
            <label for="evento_selector">Seleccionar Evento <span class="required">*</span></label>
            <select id="evento_selector" onchange="loadEventoParticipantes()">
                <option value="">Seleccione un evento</option>
                <option value="cena-anual-2025" selected>Cena Anual 2025 - 2025-12-15</option>
                <option value="torneo-tenis">Torneo de Tenis</option>
            </select>
        </div>

        <div class="search-group">
            <label for="evento_search_codigo">Buscar por Código</label>
            <input type="text" id="evento_search_codigo" placeholder="Ej: S001">
        </div>

        <div class="search-group">
            <label for="evento_search_nombre">Buscar por Nombre</label>
            <input type="text" id="evento_search_nombre" placeholder="Ej: Juan Pérez">
        </div>
    </div>

    <!-- Información del Evento y Estadísticas -->
    <div class="evento-info-stats-container">
        <div class="evento-info-box">
            <h3>Información del Evento</h3>
            <div class="info-details">
                <p><strong>Evento:</strong> <span id="info_evento_nombre">Cena Anual 2025</span></p>
                <p><strong>Fecha:</strong> <span id="info_evento_fecha">2025-12-15</span></p>
                <p><strong>Área:</strong> <span id="info_evento_area">Eventos Sociales</span></p>
            </div>
        </div>

        <div class="estadisticas-box">
            <h3>Estadísticas de Asistencia</h3>
            <div class="stats-inline">
                <span class="stat-item">Total: <strong>3</strong></span>
                <span class="stat-item entrada-club">Entrada Club: <strong id="stats_entrada_club">0</strong></span>
                <span class="stat-item entrada-evento">Entrada Evento: <strong id="stats_entrada_evento">0</strong></span>
            </div>
        </div>
    </div>

    <!-- Spinner de carga -->
    <div id="spinner_entrada_evento" class="loading-spinner-large" style="display:none;">
        <div class="spinner-busqueda"></div>
        <p style="margin-top: 10px; color: #666;">Cargando participantes...</p>
    </div>

    <!-- Mensaje de error -->
    <div id="error_entrada_evento" class="error-message-section" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span id="error_entrada_evento_texto">Error al cargar los participantes</span>
    </div>

    <!-- Tabla de participantes -->
    <div class="participants-table-container">
        <div class="table-wrapper">
            <table class="participants-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Nombre Completo</th>
                        <th>Mesa/Silla</th>
                        <th>Entrada Club</th>
                        <th>Entrada Evento</th>
                    </tr>
                </thead>
                <tbody id="evento_participants_tbody">
                    <tr>
                        <td>S001</td>
                        <td><span class="badge-type socio">socio</span></td>
                        <td>
                            <div class="participant-name">Juan Pérez García</div>
                            <div class="participant-dni">DNI: 12345678</div>
                        </td>
                        <td>
                            <div class="mesa-silla-info">
                                <span class="mesa-badge">Mesa 1</span>
                                <span class="silla-badge">Silla 1</span>
                            </div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaClub(this, 'S001')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaEvento(this, 'S001')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>S001-INV1<br><span class="invitado-de">(Inv. de S001)</span></td>
                        <td><span class="badge-type invitado">invitado</span></td>
                        <td>
                            <div class="participant-name">María López Martínez</div>
                            <div class="participant-dni">DNI: 87654321</div>
                        </td>
                        <td>
                            <div class="mesa-silla-info">
                                <span class="mesa-badge">Mesa 1</span>
                                <span class="silla-badge">Silla 2</span>
                            </div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaClub(this, 'S001-INV1')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaEvento(this, 'S001-INV1')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>S002</td>
                        <td><span class="badge-type socio">socio</span></td>
                        <td>
                            <div class="participant-name">Carlos Rodríguez Silva</div>
                            <div class="participant-dni">DNI: 23456789</div>
                        </td>
                        <td>
                            <div class="mesa-silla-info">
                                <span class="mesa-badge">Mesa 1</span>
                                <span class="silla-badge">Silla 3</span>
                            </div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaClub(this, 'S002')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleEntradaEvento(this, 'S002')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .entrada-evento-wrapper {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .entrada-evento-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .entrada-evento-header .header-text h2 {
        color: #333;
        font-size: 1.5rem;
        margin: 0 0 0.3rem 0;
        font-weight: 600;
    }

    .entrada-evento-header .header-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin: 0;
    }

    .btn-export-pdf {
        padding: 0.6rem 1.2rem;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-export-pdf:hover {
        background: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    /* Search Controls Row */
    .search-controls-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }

    .search-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .search-group label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #333;
    }

    .required {
        color: #e74c3c;
    }

    .search-group input,
    .search-group select {
        padding: 0.6rem 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        background: white;
    }

    .search-group input:focus,
    .search-group select:focus {
        outline: none;
        border-color: #78B548;
        box-shadow: 0 0 0 3px rgba(120, 181, 72, 0.1);
    }

    /* Evento Info y Estadísticas */
    .evento-info-stats-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .evento-info-box,
    .estadisticas-box {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e0e0e0;
    }

    .evento-info-box h3,
    .estadisticas-box h3 {
        margin: 0 0 0.8rem 0;
        font-size: 0.95rem;
        color: #333;
        font-weight: 600;
    }

    .info-details p {
        margin: 0.4rem 0;
        font-size: 0.9rem;
        color: #555;
    }

    .info-details strong {
        color: #333;
        font-weight: 600;
    }

    .stats-inline {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .stat-item {
        font-size: 0.85rem;
        color: #666;
    }

    .stat-item strong {
        font-size: 1.1rem;
        font-weight: 700;
        margin-left: 0.3rem;
    }

    .stat-item.entrada-club strong {
        color: #3498db;
    }

    .stat-item.entrada-evento strong {
        color: #27ae60;
    }

    /* Tabla de participantes */
    .participants-table-container {
        background: white;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .participants-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .participants-table thead {
        background: #f8f9fa;
    }

    .participants-table th {
        padding: 0.8rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
        font-size: 0.85rem;
    }

    .participants-table td {
        padding: 0.8rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .participants-table tbody tr:hover {
        background: #f8f9fa;
    }

    .participants-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Participant info in table */
    .participant-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.2rem;
    }

    .participant-dni {
        font-size: 0.8rem;
        color: #888;
    }

    .invitado-de {
        font-size: 0.75rem;
        color: #888;
    }

    /* Mesa/Silla badges */
    .mesa-silla-info {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .mesa-badge,
    .silla-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .mesa-badge {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .silla-badge {
        background: #e3f2fd;
        color: #1565c0;
    }

    /* Badge types */
    .badge-type {
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-type.socio {
        background: #2c3e50;
        color: white;
    }

    .badge-type.invitado {
        background: #ecf0f1;
        color: #555;
    }

    /* Checkbox cell */
    .checkbox-cell {
        text-align: center;
    }

    /* Custom checkbox */
    .checkbox-container {
        display: inline-block;
        position: relative;
        cursor: pointer;
        user-select: none;
    }

    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        display: inline-block;
        width: 20px;
        height: 20px;
        background: #f0f0f0;
        border: 2px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .checkbox-container:hover .checkmark {
        background: #e8e8e8;
        border-color: #78B548;
    }

    .checkbox-container input:checked ~ .checkmark {
        background: #78B548;
        border-color: #78B548;
    }

    .checkbox-container input:checked ~ .checkmark:after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .search-controls-row {
            grid-template-columns: 1fr;
        }

        .evento-info-stats-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .entrada-evento-wrapper {
            padding: 1rem;
        }

        .entrada-evento-header {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-export-pdf {
            align-self: flex-start;
        }

        .stats-inline {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .participants-table {
            font-size: 0.8rem;
        }

        .participants-table th,
        .participants-table td {
            padding: 0.6rem 0.4rem;
        }

        .mesa-silla-info {
            flex-direction: column;
            gap: 0.3rem;
        }
    }
</style>

<script>
    async function cargarEventosEnSelector() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const select = document.getElementById('evento_selector');
            select.innerHTML = '<option value="">Seleccione un evento</option>';

            eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = evento.id;
                option.textContent = `${evento.nombre} - ${evento.fecha}`;
                select.appendChild(option);
            });

            // Seleccionar el primer evento si existe
            if (eventos.length > 0) {
                select.value = eventos[0].id;
                loadEventoParticipantes();
            }
        } catch (error) {
            console.error('[Entrada Evento] Error cargando eventos:', error);
        }
    }

    async function loadEventoParticipantes() {
        const eventoId = document.getElementById('evento_selector').value;
        const spinner = document.getElementById('spinner_entrada_evento');
        const errorDiv = document.getElementById('error_entrada_evento');
        const errorTexto = document.getElementById('error_entrada_evento_texto');

        console.log('[Entrada Evento] Cargando participantes para:', eventoId);

        if (!eventoId) {
            document.getElementById('evento_participants_tbody').innerHTML =
                '<tr><td colspan="6" style="text-align:center; color:#999;">Seleccione un evento</td></tr>';
            return;
        }

        // Mostrar spinner
        spinner.style.display = 'flex';
        errorDiv.style.display = 'none';

        try {
            // Cargar info del evento
            const eventoResponse = await fetch(`/api/eventos/${eventoId}`);

            if (!eventoResponse.ok) {
                throw new Error('Error al cargar información del evento');
            }

            const evento = await eventoResponse.json();

            document.getElementById('info_evento_nombre').textContent = evento.nombre;
            document.getElementById('info_evento_fecha').textContent = evento.fecha;
            document.getElementById('info_evento_area').textContent = evento.area || 'General';

            // Cargar participantes del evento
            const participantesResponse = await fetch(`/api/participantes/evento/${eventoId}`);

            if (!participantesResponse.ok) {
                throw new Error('Error al cargar participantes');
            }

            const participantes = await participantesResponse.json();

            // Ocultar spinner
            spinner.style.display = 'none';

            mostrarParticipantesEvento(participantes);
            actualizarEstadisticasEvento(participantes);
        } catch (error) {
            console.error('[Entrada Evento] Error cargando datos:', error);

            // Ocultar spinner
            spinner.style.display = 'none';

            // Mostrar error
            errorTexto.textContent = 'Error al cargar los datos del evento. Por favor, intente nuevamente.';
            errorDiv.style.display = 'flex';

            document.getElementById('evento_participants_tbody').innerHTML =
                '<tr><td colspan="6" style="text-align:center; color:#e74c3c;">Error al cargar participantes</td></tr>';
        }
    }

    function mostrarParticipantesEvento(participantes) {
        const tbody = document.getElementById('evento_participants_tbody');
        tbody.innerHTML = '';

        if (!participantes || participantes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#999;">No hay participantes en este evento</td></tr>';
            return;
        }

        participantes.forEach(p => {
            const tipo = p.codigo_socio.includes('-INV') ? 'invitado' :
                         (p.codigo_socio.includes('-') ? 'familiar' : 'socio');

            const invitadoDe = tipo === 'invitado' || tipo === 'familiar' ?
                `<br><span class="invitado-de">(${tipo === 'invitado' ? 'Inv.' : 'Fam.'} de ${p.codigo_socio.split('-')[0]})</span>` : '';

            const mesaSilla = p.mesa_numero ?
                `<div class="mesa-silla-info">
                    <span class="mesa-badge">Mesa ${p.mesa_numero}</span>
                    <span class="silla-badge">Silla ${p.numero_silla || 'N/A'}</span>
                </div>` :
                '<span style="color:#999;">No asignado</span>';

            const row = `
                <tr>
                    <td>${p.codigo_socio}${invitadoDe}</td>
                    <td><span class="badge-type ${tipo}">${tipo}</span></td>
                    <td>
                        <div class="participant-name">${p.nombre}</div>
                        <div class="participant-dni">DNI: ${p.dni || 'N/A'}</div>
                    </td>
                    <td>${mesaSilla}</td>
                    <td class="checkbox-cell">
                        <label class="checkbox-container">
                            <input type="checkbox" ${p.entrada_club ? 'checked' : ''}
                                   onchange="toggleEntradaClub(this, '${p.codigo_socio}', ${p.id})">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td class="checkbox-cell">
                        <label class="checkbox-container">
                            <input type="checkbox" ${p.entrada_evento ? 'checked' : ''}
                                   onchange="toggleEntradaEvento(this, '${p.codigo_socio}', ${p.id})">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function actualizarEstadisticasEvento(participantes) {
        const total = participantes.length;
        const entradaClub = participantes.filter(p => p.entrada_club).length;
        const entradaEvento = participantes.filter(p => p.entrada_evento).length;

        document.querySelector('.stat-item strong').textContent = total;
        document.getElementById('stats_entrada_club').textContent = entradaClub;
        document.getElementById('stats_entrada_evento').textContent = entradaEvento;
    }

    function buscarEntradaEvento() {
        const eventoId = document.getElementById('evento_selector').value;
        const codigo = document.getElementById('evento_search_codigo').value.trim();
        const nombre = document.getElementById('evento_search_nombre').value.trim();

        if (!eventoId) {
            alert('Por favor seleccione un evento primero');
            return;
        }

        console.log('[Entrada Evento] Buscando:', { eventoId, codigo, nombre });

        // Aquí iría la llamada AJAX al backend para filtrar participantes
    }

    async function toggleEntradaClub(checkbox, codigoParticipante, participanteId) {
        const isPresent = checkbox.checked;
        console.log('[Entrada Evento] Toggle Entrada Club:', codigoParticipante, isPresent);

        const statsEl = document.getElementById('stats_entrada_club');
        let count = parseInt(statsEl.textContent);

        if (isPresent) {
            count++;
        } else {
            count--;
        }

        statsEl.textContent = count;

        // Guardar en backend
        try {
            const eventoId = document.getElementById('evento_selector').value;
            const response = await fetch('/api/entrada-evento/marcar-entrada-club', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    evento_id: eventoId,
                    codigo: codigoParticipante,
                    entrada_club: isPresent
                })
            });

            if (!response.ok) throw new Error('Error al guardar');
            console.log('[Entrada Evento] Entrada Club guardada');
        } catch (error) {
            console.error('[Entrada Evento] Error:', error);
            checkbox.checked = !isPresent;
            statsEl.textContent = isPresent ? count - 1 : count + 1;
            alert('Error al guardar. Intente nuevamente.');
        }
    }

    async function toggleEntradaEvento(checkbox, codigoParticipante, participanteId) {
        const isPresent = checkbox.checked;
        console.log('[Entrada Evento] Toggle Entrada Evento:', codigoParticipante, isPresent);

        const statsEl = document.getElementById('stats_entrada_evento');
        let count = parseInt(statsEl.textContent);

        if (isPresent) {
            count++;
        } else {
            count--;
        }

        statsEl.textContent = count;

        // Guardar en backend
        try {
            const eventoId = document.getElementById('evento_selector').value;
            const response = await fetch('/api/entrada-evento/marcar-entrada-evento', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    evento_id: eventoId,
                    codigo: codigoParticipante,
                    entrada_evento: isPresent
                })
            });

            if (!response.ok) throw new Error('Error al guardar');
            console.log('[Entrada Evento] Entrada Evento guardada');
        } catch (error) {
            console.error('[Entrada Evento] Error:', error);
            checkbox.checked = !isPresent;
            statsEl.textContent = isPresent ? count - 1 : count + 1;
            alert('Error al guardar. Intente nuevamente.');
        }
    }

    function exportarPDFEvento() {
        const eventoId = document.getElementById('evento_selector').value;

        if (!eventoId) {
            alert('Por favor seleccione un evento primero');
            return;
        }

        console.log('[Entrada Evento] Exportando PDF para evento:', eventoId);
        alert('Exportando lista de asistencia del evento a PDF...');
        // window.location.href = '/api/entrada-evento/' + eventoId + '/export-pdf';
    }

    // Cargar eventos al iniciar
    document.addEventListener('DOMContentLoaded', function() {
        cargarEventosEnSelector();

        // Búsqueda en tiempo real
        let searchTimeout;
        document.getElementById('evento_search_codigo').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaEvento, 500);
        });

        document.getElementById('evento_search_nombre').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaEvento, 500);
        });
    });
</script>
