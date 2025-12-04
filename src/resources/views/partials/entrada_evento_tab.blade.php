<!-- Contenido de la pestaña Entrada Evento -->
<div class="entrada-evento-wrapper">
    <!-- Header con título y botón exportar -->
    <div class="entrada-evento-header">
        <div class="header-text">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Control de Asistencia - Evento
            </h2>
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
            <label for="evento_selector">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Seleccionar Evento <span class="required">*</span>
            </label>
            <select id="evento_selector" onchange="loadEventoParticipantes()">
                <option value="">Seleccione un evento</option>
            </select>
        </div>

        <div class="search-group">
            <label for="evento_search_codigo">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Buscar por Código
            </label>
            <input type="text" id="evento_search_codigo" placeholder="Ej: S001">
        </div>

        <div class="search-group">
            <label for="evento_search_nombre">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                Buscar por Nombre
            </label>
            <input type="text" id="evento_search_nombre" placeholder="Ej: Juan Pérez">
        </div>
    </div>

    <!-- Información del Evento y Estadísticas -->
    <div class="evento-info-stats-container">
        <div class="evento-info-box">
            <h3>Información del Evento</h3>
            <div class="info-details">
                <p><strong>Evento:</strong> <span id="info_evento_nombre">-</span></p>
                <p><strong>Fecha:</strong> <span id="info_evento_fecha">-</span></p>
                <p><strong>Área:</strong> <span id="info_evento_area">-</span></p>
            </div>
        </div>

        <div class="estadisticas-box">
            <h3>Estadísticas de Asistencia</h3>
            <div class="stats-inline">
                <span class="stat-item">Total: <strong id="stats_total">0</strong></span>
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
                        <td colspan="6" style="text-align:center; padding: 40px; color:#999;">
                            Seleccione un evento para ver los participantes
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .entrada-evento-wrapper {
        padding: 2rem;
        background: #f8f9fa;
        min-height: calc(100vh - 200px);
    }

    /* Header */
    .entrada-evento-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #78B548 0%, #6aa03a 100%);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
        border: none;
    }

    .entrada-evento-header .header-text h2 {
        color: #ffffff;
        font-size: 1.75rem;
        margin: 0 0 0.3rem 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .entrada-evento-header .header-text h2 svg {
        color: #ffffff;
    }

    .entrada-evento-header .header-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        margin: 0;
        font-weight: 400;
    }

    .btn-export-pdf {
        padding: 0.65rem 1.3rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(10px);
    }

    .btn-export-pdf:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Search Controls Row */
    .search-controls-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
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

    .search-group .required {
        color: #e74c3c;
    }

    .search-group select,
    .search-group input {
        padding: 0.6rem 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        background: white;
    }

    .search-group select:focus,
    .search-group input:focus {
        outline: none;
        border-color: #78B548;
        box-shadow: 0 0 0 3px rgba(120, 181, 72, 0.1);
    }

    /* Evento Info and Stats */
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
        color: #333;
    }

    .info-details strong {
        color: #555;
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

    /* Loading Spinner */
    .loading-spinner-large {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
    }

    .spinner-busqueda {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Error Message */
    .error-message-section {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #e74c3c;
        gap: 0.75rem;
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
        max-height: 315px;
        overflow-y: auto;
    }

    .table-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .participants-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .participants-table thead {
        background: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .participants-table th {
        padding: 0.8rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
        font-size: 0.85rem;
        background: #f8f9fa;
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

    .badge-type.familiar {
        background: #3498db;
        color: white;
    }

    .badge-type.invitado {
        background: #ecf0f1;
        color: #555;
    }

    .participant-name {
        font-weight: 500;
        color: #333;
    }

    .participant-dni {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.2rem;
    }

    .invitado-de {
        font-size: 0.75rem;
        color: #888;
        font-style: italic;
    }

    .mesa-silla-info {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .mesa-badge,
    .silla-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #555;
    }

    /* Checkbox */
    .checkbox-cell {
        text-align: center;
    }

    .checkbox-container {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
    }

    .checkbox-container input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #78B548;
    }

    .checkbox-container input[type="checkbox"]:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .loading-cell {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f4f6;
        border-top-color: #3498db;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .entrada-evento-wrapper {
            padding: 1rem;
        }

        .search-controls-row {
            grid-template-columns: 1fr;
        }

        .evento-info-stats-container {
            grid-template-columns: 1fr;
        }

        .entrada-evento-header {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-export-pdf {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    let participantesGlobales = [];
    let eventoActual = null;

    async function cargarEventosEnSelector() {
        try {
            const response = await fetch('/api/eventos/activos');
            const eventos = await response.json();

            const select = document.getElementById('evento_selector');
            if (!select) return;

            select.innerHTML = '<option value="">Seleccione un evento</option>';

            eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = evento.id;
                option.textContent = `${evento.nombre} - ${evento.fecha}`;
                select.appendChild(option);
            });

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

        spinner.style.display = 'flex';
        errorDiv.style.display = 'none';

        try {
            const response = await fetch(`/api/entrada-evento/${eventoId}/listar`);

            if (!response.ok) {
                throw new Error('Error al cargar participantes');
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Error al cargar datos');
            }

            eventoActual = data.evento;
            participantesGlobales = data.data;

            document.getElementById('info_evento_nombre').textContent = eventoActual.nombre;
            document.getElementById('info_evento_fecha').textContent = eventoActual.fecha +
                (eventoActual.fecha_fin ? ` al ${eventoActual.fecha_fin}` : '');
            document.getElementById('info_evento_area').textContent = eventoActual.area || 'General';

            spinner.style.display = 'none';

            mostrarParticipantesEvento(participantesGlobales);
            actualizarEstadisticasEvento();
        } catch (error) {
            console.error('[Entrada Evento] Error cargando datos:', error);

            spinner.style.display = 'none';
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
            const invitadoDe = (p.tipo === 'invitado' || p.tipo === 'familiar') && p.codigo_participante.includes('-') ?
                `<br><span class="invitado-de">(${p.tipo === 'invitado' ? 'Inv.' : 'Fam.'} de ${p.codigo_participante.split('-')[0]})</span>` : '';

            const mesaSilla = p.mesa_numero ?
                `<div class="mesa-silla-info">
                    <span class="mesa-badge">Mesa ${p.mesa_numero}</span>
                    <span class="silla-badge">Silla ${p.silla_numero || 'N/A'}</span>
                </div>` :
                '<span style="color:#999;">No asignado</span>';

            const row = `
                <tr id="row_${p.id}">
                    <td>${p.codigo_participante}${invitadoDe}</td>
                    <td><span class="badge-type ${p.tipo}">${p.tipo}</span></td>
                    <td>
                        <div class="participant-name">${p.nombre}</div>
                        <div class="participant-dni">DNI: ${p.dni || 'N/A'}</div>
                    </td>
                    <td>${mesaSilla}</td>
                    <td class="checkbox-cell" id="cell_club_${p.id}">
                        <label class="checkbox-container">
                            <input type="checkbox"
                                   ${p.entrada_club ? 'checked' : ''}
                                   ${p.entrada_club || p.ya_asistio_club ? 'disabled' : ''}
                                   onchange="toggleEntradaClub(this, ${p.id})">
                        </label>
                    </td>
                    <td class="checkbox-cell" id="cell_evento_${p.id}">
                        <label class="checkbox-container">
                            <input type="checkbox"
                                   ${p.entrada_evento ? 'checked disabled' : ''}
                                   onchange="toggleEntradaEvento(this, ${p.id})">
                        </label>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function actualizarEstadisticasEvento() {
        const total = participantesGlobales.length;
        const entradaClub = participantesGlobales.filter(p => p.entrada_club).length;
        const entradaEvento = participantesGlobales.filter(p => p.entrada_evento).length;

        document.getElementById('stats_total').textContent = total;
        document.getElementById('stats_entrada_club').textContent = entradaClub;
        document.getElementById('stats_entrada_evento').textContent = entradaEvento;
    }

    async function toggleEntradaClub(checkbox, participanteId) {
        const cellId = `cell_club_${participanteId}`;
        const cell = document.getElementById(cellId);
        const originalHTML = cell.innerHTML;

        try {
            cell.innerHTML = '<div class="loading-cell"></div>';

            const response = await fetch(`/api/entrada-evento/${participanteId}/entrada-club`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Error al registrar');
            }

            // Actualizar estado local
            const participante = participantesGlobales.find(p => p.id === participanteId);
            if (participante) {
                participante.entrada_club = true;
                participante.fecha_hora_club = result.data.fecha_hora_club;
            }

            // Deshabilitar checkbox permanentemente
            cell.innerHTML = `
                <label class="checkbox-container">
                    <input type="checkbox" checked disabled>
                </label>
            `;

            actualizarEstadisticasEvento();

            try {
                mostrarNotificacion('Entrada al club registrada correctamente', 'success');
            } catch (e) {
                console.log('[Entrada Evento] Entrada Club registrada');
            }

        } catch (error) {
            console.error('[Entrada Evento] Error:', error);
            cell.innerHTML = originalHTML;
            try {
                mostrarNotificacion('Error al registrar entrada al club', 'error');
            } catch (e) {
                alert('Error al registrar. Intente nuevamente.');
            }
        }
    }

    async function toggleEntradaEvento(checkbox, participanteId) {
        const cellId = `cell_evento_${participanteId}`;
        const cell = document.getElementById(cellId);
        const originalHTML = cell.innerHTML;

        try {
            cell.innerHTML = '<div class="loading-cell"></div>';

            const response = await fetch(`/api/entrada-evento/${participanteId}/entrada-evento`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Error al registrar');
            }

            // Actualizar estado local
            const participante = participantesGlobales.find(p => p.id === participanteId);
            if (participante) {
                participante.entrada_evento = true;
                participante.fecha_hora_evento = result.data.fecha_hora_evento;
            }

            // Deshabilitar checkbox permanentemente
            cell.innerHTML = `
                <label class="checkbox-container">
                    <input type="checkbox" checked disabled>
                </label>
            `;

            actualizarEstadisticasEvento();

            try {
                mostrarNotificacion('Entrada al evento registrada correctamente', 'success');
            } catch (e) {
                console.log('[Entrada Evento] Entrada Evento registrada');
            }

        } catch (error) {
            console.error('[Entrada Evento] Error:', error);
            cell.innerHTML = originalHTML;
            try {
                mostrarNotificacion('Error al registrar entrada al evento', 'error');
            } catch (e) {
                alert('Error al registrar. Intente nuevamente.');
            }
        }
    }

    function buscarEntradaEvento() {
        const codigo = document.getElementById('evento_search_codigo').value.trim().toLowerCase();
        const nombre = document.getElementById('evento_search_nombre').value.trim().toLowerCase();

        if (!codigo && !nombre) {
            mostrarParticipantesEvento(participantesGlobales);
            return;
        }

        const filtrados = participantesGlobales.filter(p => {
            const matchCodigo = !codigo || p.codigo_participante.toLowerCase().includes(codigo);
            const matchNombre = !nombre || p.nombre.toLowerCase().includes(nombre);
            return matchCodigo && matchNombre;
        });

        mostrarParticipantesEvento(filtrados);
    }

    function exportarPDFEvento() {
        const eventoId = document.getElementById('evento_selector').value;

        if (!eventoId) {
            alert('Por favor seleccione un evento primero');
            return;
        }

        console.log('[Entrada Evento] Exportando PDF para evento:', eventoId);
        window.location.href = `/api/entrada-evento/${eventoId}/exportar-pdf`;
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        cargarEventosEnSelector();

        // Búsqueda en tiempo real
        let searchTimeout;
        document.getElementById('evento_search_codigo').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaEvento, 300);
        });

        document.getElementById('evento_search_nombre').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaEvento, 300);
        });
    });
</script>
