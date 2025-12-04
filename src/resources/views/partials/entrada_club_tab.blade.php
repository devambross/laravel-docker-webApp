<!-- Contenido de la pestaña Entrada Club -->
<div class="entrada-club-wrapper">
    <!-- Header con título y botón exportar -->
    <div class="entrada-club-header">
        <div class="header-text">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Control de Asistencia - Entrada del Club
            </h2>
            <p class="header-subtitle">Registre la asistencia de socios e invitados en la entrada del club</p>
        </div>
        <button class="btn-export-pdf" onclick="exportarPDFClub()">
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

    <!-- Búsquedas y filtros -->
    <div class="search-controls-row">
        <div class="search-group">
            <label for="club_search_codigo">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Buscar por Código de Socio
            </label>
            <input type="text" id="club_search_codigo" placeholder="Ej: S001">
        </div>

        <div class="search-group">
            <label for="club_search_nombre">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                Buscar por Nombre
            </label>
            <input type="text" id="club_search_nombre" placeholder="Ej: Juan Pérez">
        </div>

        <div class="search-group">
            <label for="club_filter_area">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
                Filtrar por Área/Evento
            </label>
            <select id="club_filter_area">
                <option value="">Todos</option>
            </select>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas-box">
        <h3>Estadísticas del Día</h3>
        <div class="stats-inline">
            <span class="stat-item">Total Asistencias: <strong id="club_total_asistencias">0</strong></span>
            <span class="stat-item socios-stat">Socios: <strong id="club_socios">0</strong></span>
            <span class="stat-item familiares-stat">Familiares: <strong id="club_familiares">0</strong></span>
            <span class="stat-item invitados-stat">Invitados: <strong id="club_invitados">0</strong></span>
        </div>
    </div>

    <!-- Spinner de carga -->
    <div id="spinner_entrada_club" class="loading-spinner-large" style="display:none;">
        <div class="spinner-busqueda"></div>
        <p style="margin-top: 10px; color: #666;">Cargando datos...</p>
    </div>

    <!-- Mensaje de error -->
    <div id="error_entrada_club" class="error-message-section" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span id="error_entrada_club_texto">Error al cargar los datos</span>
    </div>

    <!-- Modal de exportación PDF -->
    <div id="modalExportarPDFClub" class="modal-overlay">
        <div class="modal-container modal-md">
            <div class="modal-header">
                <h3>Exportar Reporte de Asistencias</h3>
                <button class="modal-close" onclick="cerrarModalExportarPDF()">×</button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 1.5rem; color: #666;">Seleccione el día del cual desea exportar el reporte:</p>

                <!-- Spinner de carga para las opciones -->
                <div id="spinner_opciones_exportar" class="loading-spinner-small" style="display:none; text-align: center; padding: 2rem;">
                    <div class="spinner-busqueda"></div>
                    <p style="margin-top: 10px; color: #666;">Cargando fechas...</p>
                </div>

                <div id="opciones_exportar" class="opciones-exportar">
                    <!-- Las opciones se cargarán dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de participantes -->
    <div class="participants-table-container">
        <div class="table-wrapper">
            <table class="participants-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>DNI</th>
                        <th>Nombre Completo</th>
                        <th>Evento/Área</th>
                        <th>Asistencia</th>
                    </tr>
                </thead>
                <tbody id="club_participants_tbody">
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 40px; color:#999;">
                            Cargando datos...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .entrada-club-wrapper {
        padding: 2rem;
        background: #f8f9fa;
        min-height: calc(100vh - 200px);
    }

    /* Header */
    .entrada-club-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        border: none;
    }

    .entrada-club-header .header-text h2 {
        color: #ffffff;
        font-size: 1.75rem;
        margin: 0 0 0.3rem 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .entrada-club-header .header-text h2 svg {
        color: #ffffff;
    }

    .entrada-club-header .header-subtitle {
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
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    /* Search Controls Row */
    .search-controls-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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

    /* Estadísticas */
    .estadisticas-box {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .estadisticas-box h3 {
        margin: 0 0 0.8rem 0;
        font-size: 0.95rem;
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

    .stat-item.presentes strong {
        color: #27ae60;
    }

    .stat-item.ausentes strong {
        color: #e74c3c;
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

    /* Evento/Area info in table */
    .evento-info {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.2rem;
    }

    .area-info {
        font-size: 0.8rem;
        color: #888;
    }

    .invitado-de {
        font-size: 0.75rem;
        color: #888;
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

    /* Badge de ya asistió */
    .ya-asistio-badge {
        display: inline-block;
        margin-left: 8px;
        padding: 0.2rem 0.6rem;
        background: #27ae60;
        color: white;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .ya-asistio-row {
        background: #f0fff4;
    }

    .ya-asistio-row:hover {
        background: #e8f8f0 !important;
    }

    /* Botón registrar asistencia */
    .btn-registrar-asistencia {
        padding: 0.5rem 1rem;
        background: #78B548;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-registrar-asistencia:hover {
        background: #6a9f3f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
    }

    .btn-registrar-asistencia:active {
        transform: translateY(0);
    }

    /* Estadísticas mejoradas */
    .stat-item.socios-stat strong {
        color: #2c3e50;
    }

    .stat-item.familiares-stat strong {
        color: #3498db;
    }

    .stat-item.invitados-stat strong {
        color: #95a5a6;
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
    }

    /* Animación de spinner */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .entrada-club-wrapper {
            padding: 1rem;
        }

        .entrada-club-header {
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
    }

    /* Estilos para modal de exportar PDF */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #999;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
        max-height: calc(90vh - 100px);
    }

    .opciones-exportar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .opcion-fecha {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .opcion-fecha:hover {
        border-color: #78B548;
        background: #f8fdf4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.2);
    }

    .icono-calendario {
        width: 48px;
        height: 48px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
    }

    .opcion-fecha:hover .icono-calendario {
        background: #78B548;
    }

    .opcion-fecha:hover .icono-calendario svg {
        stroke: white;
    }

    .info-fecha {
        flex: 1;
    }

    .info-fecha h4 {
        margin: 0 0 0.3rem 0;
        font-size: 1.1rem;
        color: #333;
    }

    .info-fecha p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    .badge-actualizable {
        display: inline-block;
        margin-top: 0.5rem;
        padding: 0.3rem 0.6rem;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-sin-registros {
        display: inline-block;
        margin-top: 0.5rem;
        margin-left: 0.5rem;
        padding: 0.3rem 0.6rem;
        background: #fff3e0;
        color: #e65100;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .loading-spinner-small {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    // Cargar eventos en el filtro de área
    async function cargarEventosEnFiltro() {
        try {
            const response = await fetch('/api/eventos');
            const eventos = await response.json();

            const select = document.getElementById('club_filter_area');
            select.innerHTML = '<option value="">Todos</option>';

            eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = 'evento_' + evento.id;
                option.textContent = evento.nombre + ' - ' + evento.fecha;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('[Entrada Club] Error cargando eventos:', error);
        }
    }

    let todosLosParticipantes = []; // Cache de todos los participantes/socios
    let estadisticasAsistencias = {}; // Estadísticas del día

    async function buscarEntradaClub() {
        const codigo = document.getElementById('club_search_codigo').value.trim().toLowerCase();
        const nombre = document.getElementById('club_search_nombre').value.trim().toLowerCase();
        const areaFiltro = document.getElementById('club_filter_area').value;

        console.log('[Entrada Club] Filtrando:', { codigo, nombre, areaFiltro });

        // Si no hay filtros, mostrar todos
        if (!codigo && !nombre && !areaFiltro) {
            mostrarResultadosClub(todosLosParticipantes);
            return;
        }

        // Filtrar localmente
        const filtrados = todosLosParticipantes.filter(p => {
            const matchCodigo = !codigo || (p.codigo_socio || '').toLowerCase().includes(codigo);
            const matchNombre = !nombre || (p.nombre || '').toLowerCase().includes(nombre);

            let matchArea = true;
            if (areaFiltro) {
                if (areaFiltro.startsWith('evento_')) {
                    const eventoId = parseInt(areaFiltro.replace('evento_', ''));
                    matchArea = p.evento_id === eventoId;
                }
            }

            return matchCodigo && matchNombre && matchArea;
        });

        mostrarResultadosClub(filtrados);
    }

    async function cargarTodasLasEntradas() {
        const spinner = document.getElementById('spinner_entrada_club');
        const errorDiv = document.getElementById('error_entrada_club');
        const errorTexto = document.getElementById('error_entrada_club_texto');

        // Mostrar spinner
        spinner.style.display = 'flex';
        errorDiv.style.display = 'none';

        try {
            // Obtener todos los socios/familiares + participantes de eventos del día
            const response = await fetch('/api/entrada-club/listar');

            console.log('[Entrada Club] Response status:', response.status);
            console.log('[Entrada Club] Response ok:', response.ok);

            if (!response.ok) {
                const errorData = await response.text();
                console.error('[Entrada Club] Error response:', errorData);
                throw new Error(`HTTP ${response.status}: ${errorData}`);
            }

            const result = await response.json();
            console.log('[Entrada Club] Result:', result);

            const participantes = result.success ? result.data : (Array.isArray(result) ? result : []);

            // Guardar en cache global
            todosLosParticipantes = participantes;

            // Cargar estadísticas del día
            await cargarEstadisticasDelDia();

            // Ocultar spinner
            spinner.style.display = 'none';

            mostrarResultadosClub(participantes);
        } catch (error) {
            console.error('[Entrada Club] Error cargando participantes:', error);

            // Ocultar spinner
            spinner.style.display = 'none';

            // Mostrar error
            errorTexto.textContent = 'Error al cargar los participantes. Por favor, recargue la página.';
            errorDiv.style.display = 'flex';
        }
    }

    async function cargarEstadisticasDelDia() {
        try {
            const response = await fetch('/api/entrada-club/estadisticas');
            if (!response.ok) return;

            const result = await response.json();
            estadisticasAsistencias = result.success ? result.data : {};

            // Actualizar UI
            document.getElementById('club_total_asistencias').textContent = estadisticasAsistencias.total || 0;
            document.getElementById('club_socios').textContent = estadisticasAsistencias.socios || 0;
            document.getElementById('club_familiares').textContent = estadisticasAsistencias.familiares || 0;
            document.getElementById('club_invitados').textContent = estadisticasAsistencias.invitados || 0;
        } catch (error) {
            console.error('[Entrada Club] Error cargando estadísticas:', error);
        }
    }    function mostrarResultadosClub(participantes) {
        const tbody = document.getElementById('club_participants_tbody');
        tbody.innerHTML = '';

        if (!participantes || participantes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#999;">No hay participantes registrados</td></tr>';
            return;
        }

        participantes.forEach(p => {
            const codigo = p.codigo_socio || 'N/A';
            const tipo = p.tipo || 'socio';
            const yaAsistioHoy = p.ya_asistio_hoy || false;
            const vecesAsistio = p.veces_asistio_hoy || 0;

            const invitadoDe = tipo === 'invitado' || tipo === 'familiar' ?
                `<br><span class="invitado-de">(${tipo === 'invitado' ? 'Inv.' : 'Fam.'} de ${codigo.split('-')[0]})</span>` : '';

            // Mostrar evento solo si tiene evento_nombre
            const eventoInfo = p.evento_nombre ?
                `<div class="evento-info">${p.evento_nombre}</div>
                 <div class="area-info">${p.mesa_silla || 'Mesa no asignada'}</div>` :
                `<div class="area-info">Entrada general</div>`;

            // Indicador visual si ya asistió hoy con número de veces
            const indicadorAsistencia = yaAsistioHoy ?
                `<span class="ya-asistio-badge" title="Ya registró asistencia hoy">✓ Asistió hoy ${vecesAsistio > 1 ? vecesAsistio + ' veces' : ''}</span>` : '';

            const row = `
                <tr ${yaAsistioHoy ? 'class="ya-asistio-row"' : ''}>
                    <td>
                        ${codigo}${invitadoDe}
                        ${indicadorAsistencia}
                    </td>
                    <td><span class="badge-type ${tipo}">${tipo}</span></td>
                    <td>${p.dni || 'N/A'}</td>
                    <td>${p.nombre}</td>
                    <td>${eventoInfo}</td>
                    <td class="checkbox-cell">
                        <button class="btn-registrar-asistencia"
                                onclick="registrarAsistencia('${codigo}', '${tipo}', '${p.nombre}', '${p.dni || ''}', ${p.evento_id || 'null'}, '${p.evento_nombre || ''}')"
                                ${yaAsistioHoy ? '' : ''}>
                            ${yaAsistioHoy ? 'Registrar otra entrada' : 'Registrar entrada'}
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    async function registrarAsistencia(codigo, tipo, nombre, dni, eventoId, eventoNombre) {
        console.log('[Entrada Club] Registrando asistencia:', { codigo, tipo, nombre, eventoId });

        // Encontrar la fila y el botón
        const filas = document.querySelectorAll('#club_participants_tbody tr');
        let botonTarget = null;
        let filaTarget = null;

        for (let fila of filas) {
            const celdaCodigo = fila.cells[0].textContent;
            if (celdaCodigo.includes(codigo)) {
                filaTarget = fila;
                botonTarget = fila.querySelector('.btn-registrar-asistencia');
                break;
            }
        }

        // Guardar texto original
        const textoOriginal = botonTarget ? botonTarget.innerHTML : '';

        try {
            // Mostrar icono de carga en el botón
            if (botonTarget) {
                botonTarget.disabled = true;
                botonTarget.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; vertical-align: middle;">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                        <path d="M12 2a10 10 0 0110 10" stroke-opacity="1"/>
                    </svg>
                    Registrando...
                `;
            }

            const response = await fetch('/api/entrada-club/registrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    codigo_socio: codigo,
                    tipo: tipo,
                    nombre: nombre,
                    dni: dni,
                    evento_id: eventoId,
                    evento_nombre: eventoNombre
                })
            });

            if (!response.ok) {
                throw new Error('Error al registrar asistencia');
            }

            const result = await response.json();
            console.log('[Entrada Club] Asistencia registrada:', result);

            // Mostrar notificación (si la función existe)
            try {
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion(`Asistencia registrada para ${nombre}`, 'success');
                }
            } catch (e) {
                console.log('[Entrada Club] Notificación no disponible');
            }

            // Actualizar estado en cache local
            const participante = todosLosParticipantes.find(p => p.codigo_socio === codigo);
            if (participante) {
                participante.ya_asistio_hoy = true;
                participante.veces_asistio_hoy = (participante.veces_asistio_hoy || 0) + 1;
            }

            // Actualizar solo la fila afectada
            if (filaTarget) {
                // Agregar clase de ya asistió
                filaTarget.classList.add('ya-asistio-row');

                // Actualizar o agregar badge "✓ Asistió hoy" en la primera celda
                const celdaCodigo = filaTarget.cells[0];
                const badgeExistente = celdaCodigo.querySelector('.ya-asistio-badge');
                const vecesTotal = (participante ? participante.veces_asistio_hoy : 1);
                const textoBadge = vecesTotal > 1 ? `✓ Asistió hoy ${vecesTotal} veces` : '✓ Asistió hoy';

                if (badgeExistente) {
                    badgeExistente.textContent = textoBadge;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'ya-asistio-badge';
                    badge.title = 'Ya registró asistencia hoy';
                    badge.textContent = textoBadge;
                    celdaCodigo.appendChild(badge);
                }

                // Actualizar botón
                if (botonTarget) {
                    botonTarget.disabled = false;
                    botonTarget.innerHTML = 'Registrar otra entrada';
                }
            }

            // Recargar estadísticas
            await cargarEstadisticasDelDia();

        } catch (error) {
            console.error('[Entrada Club] Error registrando asistencia:', error);

            // Mostrar notificación de error (si la función existe)
            try {
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion('Error al registrar la asistencia. Intente nuevamente.', 'error');
                } else {
                    alert('Error al registrar la asistencia. Intente nuevamente.');
                }
            } catch (e) {
                alert('Error al registrar la asistencia. Intente nuevamente.');
            }

            // Restaurar botón original
            if (botonTarget) {
                botonTarget.disabled = false;
                botonTarget.innerHTML = textoOriginal;
            }
        }
    }

    // Funciones para el modal de exportación
    function exportarPDFClub() {
        // Mostrar modal de selección de fecha
        const modal = document.getElementById('modalExportarPDFClub');
        modal.classList.add('show');

        // Cargar las opciones de fechas dinámicamente
        cargarOpcionesFechas();
    }

    function cerrarModalExportarPDF() {
        const modal = document.getElementById('modalExportarPDFClub');
        modal.classList.remove('show');
    }

    async function cargarOpcionesFechas() {
        const spinner = document.getElementById('spinner_opciones_exportar');
        const contenedor = document.getElementById('opciones_exportar');

        spinner.style.display = 'block';
        contenedor.innerHTML = '';

        try {
            // Obtener los últimos 3 días con registros
            const response = await fetch('/api/entrada-club/ultimos-dias?limite=3');
            const result = await response.json();

            if (!result.success) {
                throw new Error('Error al cargar fechas');
            }

            const fechasConRegistros = result.data || [];
            const hoy = new Date();
            const fechaHoyStr = hoy.toISOString().split('T')[0];

            // Helper para formatear fechas
            const formatoFecha = (fechaStr) => {
                const fecha = new Date(fechaStr + 'T00:00:00');
                const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                              'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                return `${dias[fecha.getDay()]}, ${fecha.getDate()} de ${meses[fecha.getMonth()]} de ${fecha.getFullYear()}`;
            };

            const formatoRelativo = (fechaStr) => {
                const fecha = new Date(fechaStr + 'T00:00:00');
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                const diffTime = hoy - fecha;
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays === 0) return 'Hoy';
                if (diffDays === 1) return 'Ayer';
                if (diffDays === 2) return 'Anteayer';
                return `Hace ${diffDays} días`;
            };

            // Crear opciones dinámicamente
            let htmlOpciones = '';

            // Siempre mostrar "Hoy" primero
            const horaActual = hoy.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            const tieneRegistrosHoy = fechasConRegistros.includes(fechaHoyStr);

            htmlOpciones += `
                <div class="opcion-fecha" onclick="exportarReportePDF('${fechaHoyStr}')">
                    <div class="icono-calendario">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="info-fecha">
                        <h4>Hoy</h4>
                        <p>${formatoFecha(fechaHoyStr)} - Desde las 00:00 hasta las ${horaActual}</p>
                        <span class="badge-actualizable">Se actualiza en tiempo real</span>
                        ${tieneRegistrosHoy ? '' : '<span class="badge-sin-registros">Sin registros aún</span>'}
                    </div>
                </div>
            `;

            // Mostrar los últimos días con registros (excluyendo hoy si ya está)
            let diasMostrados = 0;
            for (const fecha of fechasConRegistros) {
                if (fecha === fechaHoyStr) continue; // Ya mostramos hoy arriba
                if (diasMostrados >= 2) break; // Máximo 2 días adicionales

                htmlOpciones += `
                    <div class="opcion-fecha" onclick="exportarReportePDF('${fecha}')">
                        <div class="icono-calendario">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="info-fecha">
                            <h4>${formatoRelativo(fecha)}</h4>
                            <p>${formatoFecha(fecha)} - Todo el día</p>
                        </div>
                    </div>
                `;
                diasMostrados++;
            }

            // Si no hay suficientes días con registros, mostrar mensaje
            if (diasMostrados === 0) {
                htmlOpciones += `
                    <div style="text-align: center; padding: 2rem; color: #999;">
                        <p>No hay registros en días anteriores</p>
                    </div>
                `;
            }

            contenedor.innerHTML = htmlOpciones;
            spinner.style.display = 'none';

        } catch (error) {
            console.error('[Entrada Club] Error cargando fechas:', error);
            spinner.style.display = 'none';
            contenedor.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #e74c3c;">
                    <p>Error al cargar las fechas disponibles</p>
                </div>
            `;
        }
    }

    function exportarReportePDF(fecha) {
        console.log('[Entrada Club] Exportando reporte:', fecha);

        // Abrir el reporte en nueva ventana
        window.open(`/api/entrada-club/reporte?fecha=${fecha}&formato=pdf`, '_blank');

        // Cerrar modal
        cerrarModalExportarPDF();
    }    // Cerrar modal al hacer clic fuera
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('modalExportarPDFClub');
        if (event.target === modal) {
            cerrarModalExportarPDF();
        }
    });

    // Agregar listeners para búsqueda en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar eventos en filtro
        cargarEventosEnFiltro();

        // Cargar todas las entradas al inicio
        cargarTodasLasEntradas().then(() => {
            console.log('[Entrada Club] Participantes cargados:', todosLosParticipantes.length);
        });

        // Búsqueda en tiempo real con debounce
        let searchTimeout;
        document.getElementById('club_search_codigo').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaClub, 300);
        });

        document.getElementById('club_search_nombre').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(buscarEntradaClub, 300);
        });

        document.getElementById('club_filter_area').addEventListener('change', buscarEntradaClub);
    });
</script>
