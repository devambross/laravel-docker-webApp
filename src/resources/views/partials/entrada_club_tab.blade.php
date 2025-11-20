<!-- Contenido de la pestaña Entrada Club -->
<div class="entrada-club-wrapper">
    <!-- Header con título y botón exportar -->
    <div class="entrada-club-header">
        <div class="header-text">
            <h2>Control de Asistencia - Entrada del Club</h2>
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
            <label for="club_search_codigo">Buscar por Código de Socio</label>
            <input type="text" id="club_search_codigo" placeholder="Ej: S001">
        </div>

        <div class="search-group">
            <label for="club_search_nombre">Buscar por Nombre</label>
            <input type="text" id="club_search_nombre" placeholder="Ej: Juan Pérez">
        </div>

        <div class="search-group">
            <label for="club_filter_area">Filtrar por Área/Evento</label>
            <select id="club_filter_area">
                <option value="">Todos</option>
                <option value="deportes">Deportes</option>
                <option value="eventos">Eventos Sociales</option>
                <option value="cultura">Cultura</option>
            </select>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas-box">
        <h3>Estadísticas</h3>
        <div class="stats-inline">
            <span class="stat-item">Total: <strong id="club_total">3</strong></span>
            <span class="stat-item presentes">Presentes: <strong id="club_presentes">0</strong></span>
            <span class="stat-item ausentes">Ausentes: <strong id="club_ausentes">3</strong></span>
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
                        <td>S001</td>
                        <td><span class="badge-type socio">socio</span></td>
                        <td>12345678</td>
                        <td>Juan Pérez García</td>
                        <td>
                            <div class="evento-info">Cena Anual 2025</div>
                            <div class="area-info">Eventos Sociales</div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleAsistenciaClub(this, 'S001')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>S001-INV1<br><span class="invitado-de">(Inv. de S001)</span></td>
                        <td><span class="badge-type invitado">invitado</span></td>
                        <td>87654321</td>
                        <td>María López Martínez</td>
                        <td>
                            <div class="evento-info">Cena Anual 2025</div>
                            <div class="area-info">Eventos Sociales</div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleAsistenciaClub(this, 'S001-INV1')">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>S002</td>
                        <td><span class="badge-type socio">socio</span></td>
                        <td>23456789</td>
                        <td>Carlos Rodríguez Silva</td>
                        <td>
                            <div class="evento-info">Cena Anual 2025</div>
                            <div class="area-info">Eventos Sociales</div>
                        </td>
                        <td class="checkbox-cell">
                            <label class="checkbox-container">
                                <input type="checkbox" onchange="toggleAsistenciaClub(this, 'S002')">
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
    .entrada-club-wrapper {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .entrada-club-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .entrada-club-header .header-text h2 {
        color: #333;
        font-size: 1.5rem;
        margin: 0 0 0.3rem 0;
        font-weight: 600;
    }

    .entrada-club-header .header-subtitle {
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
</style>

<script>
    function buscarEntradaClub() {
        const codigo = document.getElementById('club_search_codigo').value.trim();
        const nombre = document.getElementById('club_search_nombre').value.trim();
        const area = document.getElementById('club_filter_area').value;

        console.log('[Entrada Club] Buscando:', { codigo, nombre, area });

        // Aquí iría la llamada AJAX al backend para filtrar participantes
    }

    function toggleAsistenciaClub(checkbox, codigoSocio) {
        const isPresent = checkbox.checked;
        console.log('[Entrada Club] Toggle asistencia:', codigoSocio, isPresent);

        // Actualizar estadísticas
        const presentesEl = document.getElementById('club_presentes');
        const ausentesEl = document.getElementById('club_ausentes');

        let presentes = parseInt(presentesEl.textContent);
        let ausentes = parseInt(ausentesEl.textContent);

        if (isPresent) {
            presentes++;
            ausentes--;
        } else {
            presentes--;
            ausentes++;
        }

        presentesEl.textContent = presentes;
        ausentesEl.textContent = ausentes;

        // Aquí iría la llamada AJAX al backend para guardar
    }

    function exportarPDFClub() {
        console.log('[Entrada Club] Exportando PDF...');

        // Aquí iría la lógica para generar el PDF
        alert('Exportando lista de asistencia a PDF...');

        // Ejemplo de llamada al backend:
        // window.location.href = '/api/entrada/club/export-pdf';
    }
</script>
