@extends('layouts.app')

@section('title', 'Registro')

@section('content')

<div class="registro-modern-container">
    <!-- Header con título -->
    <div class="page-header">
        <h1>Sistema de Gestión de Eventos</h1>
        <p class="subtitle">Administración de participantes y control de asistencia</p>
    </div>

    <!-- Pestañas de navegación -->
    <div class="tabs-container">
        <button class="tab-btn active" data-tab="registro">
            <i class="icon-clipboard">📋</i> Registro
        </button>
        <button class="tab-btn" data-tab="entrada-club">
            <i class="icon-users">👥</i> Entrada Club
        </button>
        <button class="tab-btn" data-tab="entrada-evento">
            <i class="icon-calendar">📅</i> Entrada Evento
        </button>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content active" id="registro-tab">
        <div class="content-wrapper">
            <!-- Panel izquierdo: Formulario de registro -->
            <div class="left-panel">
                <div class="panel-header">
                    <h2>Registrar Participante</h2>
                    <p class="panel-subtitle">Complete los datos del socio o invitado para el evento</p>
                </div>

                <div class="action-buttons-top">
                    <button class="btn-action btn-primary" onclick="openEventModal()">
                        <span class="btn-icon">➕</span> Nuevo Evento
                    </button>
                    <button class="btn-action btn-secondary" onclick="openMesaModal()">
                        <span class="btn-icon">🪑</span> Nueva Mesa
                    </button>
                </div>

                <form class="registro-form">
                    <div class="form-group">
                        <label for="tipo">Tipo <span class="required">*</span></label>
                        <select id="tipo" name="tipo" required>
                            <option value="socio">Socio</option>
                            <option value="invitado">Invitado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="codigo_socio">Código de Socio <span class="required">*</span></label>
                        <input type="text" id="codigo_socio" name="codigo_socio" placeholder="S001" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dni">DNI <span class="required">*</span></label>
                            <input type="text" id="dni" name="dni" placeholder="12345678" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="required">*</span></label>
                            <input type="text" id="nombre" name="nombre" placeholder="Juan Pérez" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="n_recibo">N° de Recibo</label>
                            <input type="text" id="n_recibo" name="n_recibo" placeholder="R-2025-001">
                        </div>
                        <div class="form-group">
                            <label for="n_boleta">N° de Boleta</label>
                            <input type="text" id="n_boleta" name="n_boleta" placeholder="B-001">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="evento">Evento <span class="required">*</span></label>
                        <select id="evento" name="evento" required>
                            <option value="">Seleccione un evento</option>
                            <option value="cena-anual-2025">Cena Anual 2025</option>
                            <option value="torneo-tenis">Torneo de Tenis</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mesa">Mesa</label>
                            <select id="mesa" name="mesa">
                                <option value="">Seleccione mesa</option>
                                <option value="1">Mesa 1</option>
                                <option value="2">Mesa 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="n_silla">N° de Silla</label>
                            <input type="number" id="n_silla" name="n_silla" placeholder="1">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        ➕ Registrar Participante
                    </button>
                </form>
            </div>

            <!-- Panel derecho: Capacidad de eventos y mesas -->
            <div class="right-panel">
                <!-- Capacidad de Eventos -->
                <div class="capacity-section">
                    <h3>Capacidad de Eventos</h3>
                    <p class="section-subtitle">Estado de ocupación de espacios por evento</p>

                    <div class="event-card">
                        <div class="event-info">
                            <h4>Cena Anual 2025</h4>
                            <span class="event-date">2025-12-15</span>
                        </div>
                        <div class="capacity-badge">
                            <span class="capacity-fill">Libres: 13</span>
                            <span class="capacity-total">Ocupados: 3/16</span>
                        </div>
                    </div>

                    <div class="event-card">
                        <div class="event-info">
                            <h4>Torneo de Tenis</h4>
                            <span class="event-date">2025-11-30</span>
                        </div>
                        <div class="capacity-badge">
                            <span class="capacity-fill">Libres: 0</span>
                            <span class="capacity-total">Ocupados: 0/0</span>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Mesas -->
                <div class="mesas-section">
                    <h3>Gestión de Mesas</h3>
                    <p class="section-subtitle">Administre las mesas y su capacidad</p>

                    <div class="mesa-card">
                        <div class="mesa-header">
                            <span class="mesa-number">Mesa 1</span>
                            <span class="mesa-event">Cena Anual 2025</span>
                            <div class="mesa-actions">
                                <button class="btn-icon-action edit" title="Editar">✏️</button>
                                <button class="btn-icon-action delete" title="Eliminar">🗑️</button>
                            </div>
                        </div>
                        <div class="mesa-details">
                            <span class="mesa-date">2025-12-15</span>
                            <div class="capacity-indicator">
                                <span class="capacity-fill">Libres: 5</span>
                                <span class="capacity-total">Ocupados: 3/8</span>
                            </div>
                        </div>
                    </div>

                    <div class="mesa-card">
                        <div class="mesa-header">
                            <span class="mesa-number">Mesa 2</span>
                            <span class="mesa-event">Cena Anual 2025</span>
                            <div class="mesa-actions">
                                <button class="btn-icon-action edit" title="Editar">✏️</button>
                                <button class="btn-icon-action delete" title="Eliminar">🗑️</button>
                            </div>
                        </div>
                        <div class="mesa-details">
                            <span class="mesa-date">2025-12-15</span>
                            <div class="capacity-indicator">
                                <span class="capacity-fill">Libres: 8</span>
                                <span class="capacity-total">Ocupados: 0/8</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disposición de Mesas -->
                <div class="disposition-section">
                    <h3>Disposición de Mesas</h3>
                    <p class="section-subtitle">Visualice la distribución de participantes por mesa</p>

                    <div class="disposition-table-wrapper">
                        <table class="disposition-table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Mesa</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S001</td>
                                    <td>Juan Pérez García</td>
                                    <td><span class="badge-type socio">socio</span></td>
                                    <td>Mesa 1 - Silla 1</td>
                                    <td><button class="btn-remove">🗑️</button></td>
                                </tr>
                                <tr>
                                    <td>S001-INV1</td>
                                    <td>María López Martínez</td>
                                    <td><span class="badge-type invitado">invitado</span></td>
                                    <td>Mesa 1 - Silla 2</td>
                                    <td><button class="btn-remove">🗑️</button></td>
                                </tr>
                                <tr>
                                    <td>S002</td>
                                    <td>Carlos Rodríguez Silva</td>
                                    <td><span class="badge-type socio">socio</span></td>
                                    <td>Mesa 1 - Silla 3</td>
                                    <td><button class="btn-remove">🗑️</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Entrada Club (placeholder) -->
    <div class="tab-content" id="entrada-club-tab">
        <div class="content-wrapper">
            <p class="tab-placeholder">Contenido de Entrada Club - En desarrollo</p>
        </div>
    </div>

    <!-- Contenido Entrada Evento (placeholder) -->
    <div class="tab-content" id="entrada-evento-tab">
        <div class="content-wrapper">
            <p class="tab-placeholder">Contenido de Entrada Evento - En desarrollo</p>
        </div>
    </div>
</div>

<!-- Modal Nuevo Evento -->
<div id="modalNuevoEvento" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Nuevo Evento</h2>
            <button class="modal-close" onclick="closeEventModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Complete los datos del nuevo evento</p>
            <form id="formNuevoEvento">
                <div class="form-group">
                    <label for="nombre_evento">Nombre del Evento <span class="required">*</span></label>
                    <input type="text" id="nombre_evento" placeholder="Ej: Cena Anual 2025" required>
                </div>
                <div class="form-group">
                    <label for="fecha_evento">Fecha <span class="required">*</span></label>
                    <input type="date" id="fecha_evento" required>
                </div>
                <div class="form-group">
                    <label for="area_evento">Área <span class="required">*</span></label>
                    <input type="text" id="area_evento" placeholder="Ej: Eventos Sociales" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEventModal()">Cancelar</button>
                    <button type="submit" class="btn-create">➕ Crear Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nueva Mesa -->
<div id="modalNuevaMesa" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Nueva Mesa</h2>
            <button class="modal-close" onclick="closeMesaModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Complete los datos de la nueva mesa</p>
            <form id="formNuevaMesa">
                <div class="form-group">
                    <label for="numero_mesa">Número de Mesa <span class="required">*</span></label>
                    <input type="text" id="numero_mesa" placeholder="Ej: 1" required>
                </div>
                <div class="form-group">
                    <label for="evento_mesa">Evento <span class="required">*</span></label>
                    <select id="evento_mesa" required>
                        <option value="">Seleccione un evento</option>
                        <option value="cena-anual-2025">Cena Anual 2025</option>
                        <option value="torneo-tenis">Torneo de Tenis</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacidad_mesa">Capacidad <span class="required">*</span></label>
                    <input type="number" id="capacidad_mesa" placeholder="8" min="1" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeMesaModal()">Cancelar</button>
                    <button type="submit" class="btn-create">➕ Crear Mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .registro-modern-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    /* Header de la página */
    .page-header {
        background: linear-gradient(135deg, #78B548 0%, #6aa23f 100%);
        color: white;
        padding: 1.5rem 2rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 1.8rem;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .subtitle {
        font-size: 0.95rem;
        opacity: 0.95;
    }

    /* Pestañas de navegación */
    .tabs-container {
        display: flex;
        background: #f5f5f5;
        border-bottom: 3px solid #78B548;
        overflow-x: auto;
    }

    .tab-btn {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        background: #e8e8e8;
        color: #666;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .tab-btn:hover {
        background: #f0f0f0;
        color: #333;
    }

    .tab-btn.active {
        background: white;
        color: #78B548;
        border-bottom: 3px solid #78B548;
    }

    .icon-clipboard, .icon-users, .icon-calendar {
        font-size: 1.2rem;
    }

    /* Contenido de pestañas */
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .content-wrapper {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #fafafa;
        min-height: calc(100vh - 250px);
    }

    /* Paneles */
    .left-panel {
        flex: 0 0 400px;
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .panel-header h2 {
        color: #333;
        font-size: 1.4rem;
        margin-bottom: 0.3rem;
    }

    .panel-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    /* Botones de acción superiores */
    .action-buttons-top {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .btn-action {
        flex: 1;
        padding: 0.7rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-primary {
        background: #78B548;
        color: white;
    }

    .btn-primary:hover {
        background: #6aa23f;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(120, 181, 72, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .btn-icon {
        font-size: 1.1rem;
    }

    /* Formulario */
    .registro-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #333;
    }

    .required {
        color: #e74c3c;
    }

    input, select {
        padding: 0.7rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #78B548;
        box-shadow: 0 0 0 3px rgba(120, 181, 72, 0.1);
    }

    .btn-submit {
        padding: 0.9rem;
        background: #78B548;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        background: #6aa23f;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(120, 181, 72, 0.3);
    }

    /* Secciones del panel derecho */
    .capacity-section, .mesas-section, .disposition-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .capacity-section h3, .mesas-section h3, .disposition-section h3 {
        color: #78B548;
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
    }

    .section-subtitle {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    /* Tarjetas de eventos */
    .event-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #78B548;
    }

    .event-info h4 {
        color: #333;
        font-size: 1rem;
        margin-bottom: 0.3rem;
    }

    .event-date {
        color: #666;
        font-size: 0.85rem;
    }

    .capacity-badge {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }

    .capacity-fill {
        color: #78B548;
        font-weight: 600;
    }

    .capacity-total {
        color: #666;
    }

    /* Tarjetas de mesas */
    .mesa-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e0e0e0;
    }

    .mesa-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .mesa-number {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
    }

    .mesa-event {
        flex: 1;
        color: #78B548;
        font-size: 0.9rem;
    }

    .mesa-actions {
        display: flex;
        gap: 0.3rem;
    }

    .btn-icon-action {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0.3rem;
        transition: transform 0.2s ease;
    }

    .btn-icon-action:hover {
        transform: scale(1.2);
    }

    .mesa-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }

    .mesa-date {
        color: #666;
    }

    .capacity-indicator {
        display: flex;
        gap: 0.75rem;
    }

    /* Tabla de disposición */
    .disposition-table-wrapper {
        overflow-x: auto;
    }

    .disposition-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .disposition-table th {
        background: #78B548;
        color: white;
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
    }

    .disposition-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .disposition-table tr:hover {
        background: #f8f9fa;
    }

    .badge-type {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-type.socio {
        background: #2c3e50;
        color: white;
    }

    .badge-type.invitado {
        background: #e8e8e8;
        color: #666;
    }

    .btn-remove {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: transform 0.2s ease;
    }

    .btn-remove:hover {
        transform: scale(1.2);
    }

    /* Modales */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid #78B548;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #333;
        font-size: 1.4rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #999;
        cursor: pointer;
        line-height: 1;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #e74c3c;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-cancel, .btn-create {
        flex: 1;
        padding: 0.8rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #666;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .btn-create {
        background: #78B548;
        color: white;
    }

    .btn-create:hover {
        background: #6aa23f;
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
    }

    .tab-placeholder {
        text-align: center;
        color: #999;
        padding: 3rem;
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-wrapper {
            flex-direction: column;
        }

        .left-panel {
            flex: 1;
            max-width: 100%;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.4rem;
        }

        .subtitle {
            font-size: 0.85rem;
        }

        .tab-btn {
            padding: 0.8rem 0.5rem;
            font-size: 0.85rem;
        }

        .icon-clipboard, .icon-users, .icon-calendar {
            font-size: 1rem;
        }

        .content-wrapper {
            padding: 1rem;
        }

        .left-panel {
            padding: 1rem;
        }

        .action-buttons-top {
            flex-direction: column;
        }

        .modal-content {
            width: 95%;
        }
    }
</style>

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
    });

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

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    });

    // Manejar envío de formularios
    document.getElementById('formNuevoEvento')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Evento creado exitosamente');
        closeEventModal();
    });

    document.getElementById('formNuevaMesa')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Mesa creada exitosamente');
        closeMesaModal();
    });
</script>

@endsection
