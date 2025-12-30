@extends('layouts.app')

@section('title', 'Registro')

@section('content')

<!-- Contenedor de notificaciones -->
<div class="notification-container" id="notificationContainer"></div>

<div class="registro-modern-container">
    <div class="content-wrapper">
        <!-- Panel izquierdo: Formulario de registro -->
        <div class="left-panel">
            <div class="panel-header">
                <h2>Registrar Participante</h2>
                <p class="panel-subtitle">Complete los datos del socio o invitado para el evento</p>
            </div>

            <div class="action-buttons-top">
                <button class="btn-action btn-primary" onclick="openEventModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        <line x1="12" y1="14" x2="12" y2="18"/>
                        <line x1="10" y1="16" x2="14" y2="16"/>
                    </svg>
                    Nuevo Evento
                </button>
                <button class="btn-action btn-secondary" onclick="openMesaModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <rect x="3" y="10" width="18" height="2"/>
                        <line x1="5" y1="12" x2="5" y2="20"/>
                        <line x1="19" y1="12" x2="19" y2="20"/>
                        <line x1="8" y1="6" x2="8" y2="10"/>
                        <line x1="16" y1="6" x2="16" y2="10"/>
                    </svg>
                    Nueva Mesa
                </button>
            </div>

            <form class="registro-form" id="formRegistroParticipante">
                <div class="form-group">
                    <label for="tipo">Tipo <span class="required">*</span></label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione tipo</option>
                        <option value="socio">Socio</option>
                        <option value="invitado">Invitado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="codigo_socio">Código de Socio <span class="required">*</span></label>
                    <div class="codigo-input-wrapper">
                        <input type="text" id="codigo_socio" name="codigo_socio" placeholder="S001" required>
                        <button type="button" id="btn_buscar_socio" class="btn-buscar-socio" style="display: none;" title="Buscar socio y familiares">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                        </button>
                        <div class="spinner-busqueda" id="spinner_codigo" style="display: none;"></div>
                    </div>
                    <div id="invitado_mensaje" class="invitado-info-message">
                        Invitado de: <strong id="invitado_de_nombre"></strong>
                    </div>
                    <div id="error_codigo" class="error-message-codigo" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span id="error_codigo_texto"></span>
                    </div>
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
                    </select>
                </div>

                <div class="form-group">
                    <label for="mesa_silla_selector">Mesa y Silla <span class="required">*</span></label>
                    <div class="mesa-silla-selector" id="mesa_silla_selector" onclick="abrirSelectorMesaSilla()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" style="margin-right: 8px;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="3" y1="9" x2="21" y2="9"/>
                            <line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                        <span class="mesa-silla-placeholder">Seleccione mesa y silla</span>
                        <input type="hidden" id="mesa" name="mesa">
                        <input type="hidden" id="n_silla" name="n_silla">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-clear" onclick="limpiarFormularioRegistro()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        </svg>
                        Limpiar
                    </button>
                    <button type="submit" class="btn-submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Registrar Participante
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel derecho: Capacidad de eventos y mesas -->
        <div class="right-panel">
            <!-- Capacidad de Eventos -->
            <div class="capacity-section">
                <h3>Capacidad de Eventos</h3>
                <p class="section-subtitle">Estado de ocupación de espacios por evento</p>
                <div class="capacity-scroll-wrapper">
                    <!-- Contenido cargado dinámicamente por JavaScript -->
                </div>
            </div>

            <!-- Gestión de Mesas -->
            <div class="mesas-section">
                <div class="section-header-with-filter">
                    <div class="section-title-group">
                        <h3>Gestión de Mesas</h3>
                        <p class="section-subtitle">Administre las mesas y su capacidad</p>
                    </div>
                    <div class="filter-group">
                        <label for="filtro_evento_mesas" style="font-size: 0.85rem; color: #666; margin-right: 0.5rem;">Evento:</label>
                        <select id="filtro_evento_mesas" onchange="filtrarMesasPorEvento()" style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 0.85rem; color: #333; background: white;">
                            <option value="">Todos los eventos</option>
                        </select>
                    </div>
                </div>
                <div class="mesas-scroll-wrapper">
                    <!-- Contenido cargado dinámicamente por JavaScript -->
                </div>
            </div>

            <!-- Disposición de Mesas ahora se muestra en modal por evento -->
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_evento">Fecha de Inicio <span class="required">*</span></label>
                        <input type="date" id="fecha_evento" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin_evento">Fecha de Fin</label>
                        <input type="date" id="fecha_fin_evento">
                    </div>
                </div>
                <div class="form-group">
                    <label for="area_evento">Área <span class="required">*</span></label>
                    <input type="text" id="area_evento" placeholder="Ej: Eventos Sociales" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEventModal()">Cancelar</button>
                    <button type="submit" class="btn-create">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Crear Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nueva Mesa en Masa -->
<div id="modalNuevaMesa" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Mesas en Masa</h2>
            <button class="modal-close" onclick="closeMesaModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Distribuya automáticamente las mesas según la cantidad de personas</p>
            <form id="formNuevaMesa">
                <div class="form-group">
                    <label for="evento_mesa">Evento <span class="required">*</span></label>
                    <select id="evento_mesa" required>
                        <option value="">Seleccione un evento</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="numero_mesas">Número de Mesas <span class="required">*</span></label>
                        <input type="number" id="numero_mesas" placeholder="10" min="1" required oninput="calcularDistribucionMesas()">
                    </div>
                    <div class="form-group">
                        <label for="total_personas">Total de Personas <span class="required">*</span></label>
                        <input type="number" id="total_personas" placeholder="80" min="1" required oninput="calcularDistribucionMesas()">
                    </div>
                </div>
                <div id="preview_distribucion" class="info-message" style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 12px; border-radius: 4px; margin: 15px 0; display: none;">
                    <p style="margin: 0; color: #555; font-size: 0.9rem;">
                        <strong style="color: #2196F3;">📄 Previsualización:</strong><br>
                        <span id="preview_text"></span>
                    </p>
                </div>
                <div class="info-message" style="background: #e8f5e9; border-left: 4px solid #78B548; padding: 12px; border-radius: 4px; margin: 15px 0;">
                    <p style="margin: 0; color: #555; font-size: 0.9rem;">
                        <strong>Distribución automática:</strong> Las sillas se distribuirán equitativamente entre todas las mesas. Si la división no es exacta, las primeras mesas recibirán una silla adicional para balancear la carga.
                    </p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeMesaModal()">Cancelar</button>
                    <button type="submit" class="btn-create">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Crear Mesas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Mesa -->
<div id="modalEditarMesa" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Mesa</h2>
            <button class="modal-close" onclick="closeEditMesaModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Modifique los datos de la mesa</p>
            <form id="formEditarMesa">
                <div class="form-group">
                    <label for="edit_numero_mesa">Número de Mesa <span class="required">*</span></label>
                    <input type="text" id="edit_numero_mesa" placeholder="Ej: 1" required readonly>
                </div>
                <div class="form-group">
                    <label for="edit_evento_mesa">Evento <span class="required">*</span></label>
                    <select id="edit_evento_mesa" required disabled>
                        <option value="">Seleccione un evento</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_capacidad_mesa">Capacidad <span class="required">*</span></label>
                    <input type="number" id="edit_capacidad_mesa" placeholder="8" min="1" required>
                </div>
                <div class="info-message">
                    <p class="capacity-info">
                        <strong>Mínimo:</strong> <span id="edit_min_capacity">3</span> (sillas ocupadas actualmente)
                    </p>
                    <p class="occupied-info">
                        Sillas ocupadas: <span id="edit_occupied_seats">3/8</span>
                    </p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditMesaModal()">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Selección de Socio/Familiar -->
<div id="modalSeleccionSocio" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Seleccionar Persona</h2>
            <button class="modal-close" onclick="closeSeleccionSocioModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Seleccione al socio principal o uno de sus familiares</p>
            <div class="selector-personas-container">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Evento -->
<div id="modalEditarEvento" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Evento</h2>
            <button class="modal-close" onclick="closeEditEventoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Modifique los datos del evento</p>
            <form id="formEditarEvento">
                <input type="hidden" id="edit_evento_id">
                <div class="form-group">
                    <label for="edit_nombre_evento">Nombre del Evento <span class="required">*</span></label>
                    <input type="text" id="edit_nombre_evento" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_fecha_evento">Fecha de Inicio <span class="required">*</span></label>
                        <input type="date" id="edit_fecha_evento" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_fecha_fin_evento">Fecha de Fin</label>
                        <input type="date" id="edit_fecha_fin_evento">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditEventoModal()">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Eliminación de Evento -->
<div id="modalEliminarEvento" class="modal">
    <div class="modal-content modal-confirm-delete">
        <div class="modal-header">
            <h2>Confirmar Eliminación</h2>
            <button class="modal-close" onclick="closeEliminarEventoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <h3 id="delete_evento_nombre" style="margin: 1rem 0; color: #2c3e50;"></h3>
            <p class="modal-subtitle">Esta acción eliminará permanentemente:</p>
            <div class="delete-info-list">
                <div class="delete-info-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#78B548" stroke-width="2">
                        <rect x="3" y="10" width="18" height="2"/>
                    </svg>
                    <span><strong id="delete_mesas_count">0</strong> mesas</span>
                </div>
                <div class="delete-info-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#78B548" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <span><strong id="delete_participantes_count">0</strong> participantes registrados</span>
                </div>
            </div>
            <p style="color: #e74c3c; margin-top: 1.5rem; font-weight: 500;">⚠️ Esta acción no se puede deshacer</p>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEliminarEventoModal()">Cancelar</button>
                <button type="button" class="btn-delete-confirm" onclick="confirmarEliminarEvento()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                    </svg>
                    Eliminar Evento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Disposición de Mesas por Evento -->
<div id="modalDisposicionEvento" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Disposición de Mesas - <span id="disposicion_evento_nombre"></span></h2>
            <button class="modal-close" onclick="closeDisposicionEventoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Participantes registrados en este evento</p>
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
                        <!-- Contenido cargado dinámicamente por JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Selector de Mesa y Silla -->
<div id="modalSelectorMesaSilla" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Seleccionar Mesa y Silla</h2>
            <button class="modal-close" onclick="closeSelectorMesaSilla()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Seleccione una mesa con sillas disponibles</p>
            <div id="mesas_disponibles_container" class="mesas-grid">
                <!-- Contenido cargado dinámicamente por JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Mesa Individual -->
<div id="modalNuevaMesaIndividual" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Mesa Individual</h2>
            <button class="modal-close" onclick="closeMesaIndividualModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-subtitle">Complete los datos de la nueva mesa</p>
            <form id="formNuevaMesaIndividual">
                <div class="form-group">
                    <label for="evento_mesa_individual">Evento <span class="required">*</span></label>
                    <select id="evento_mesa_individual" onchange="asignarNumeroMesaAutomatico()" required>
                        <option value="">Seleccione un evento</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="numero_mesa_individual">Número de Mesa <span class="required">*</span></label>
                    <input type="number" id="numero_mesa_individual" placeholder="Se asigna automáticamente" readonly required>
                </div>
                <div class="form-group">
                    <label for="capacidad_mesa_individual">Capacidad <span class="required">*</span></label>
                    <input type="number" id="capacidad_mesa_individual" placeholder="8" min="1" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeMesaIndividualModal()">Cancelar</button>
                    <button type="submit" class="btn-create">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Crear Mesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Eliminar Mesa -->
<div id="confirmDeleteMesaModal" class="modal">
    <div class="modal-content modal-confirm">
        <div class="modal-header modal-header-danger">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Confirmar Eliminación
            </h2>
            <button class="modal-close" onclick="closeConfirmDeleteMesaModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmDeleteMesaText" class="modal-confirm-text"></p>
            <p class="modal-warning">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeConfirmDeleteMesaModal()">Cancelar</button>
            <button type="button" class="btn-delete" onclick="confirmarEliminarMesa()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Eliminar Mesa
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar Participante -->
<div id="confirmDeleteParticipanteModal" class="modal">
    <div class="modal-content modal-confirm">
        <div class="modal-header modal-header-danger">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Confirmar Eliminación
            </h2>
            <button class="modal-close" onclick="closeConfirmDeleteParticipanteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmDeleteParticipanteText" class="modal-confirm-text"></p>
            <p class="modal-warning">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeConfirmDeleteParticipanteModal()">Cancelar</button>
            <button type="button" class="btn-delete" onclick="confirmarEliminarParticipante()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Eliminar Participante
            </button>
        </div>
    </div>
</div>

@include('partials.registro_styles')
@include('partials.registro_scripts')

@endsection
