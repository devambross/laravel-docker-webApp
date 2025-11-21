@extends('layouts.app')

@section('title', 'Registro')

@section('content')

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

                <div class="form-row">
                    <div class="form-group">
                        <label for="mesa">Mesa</label>
                        <select id="mesa" name="mesa" onchange="cargarSillasDisponibles()">
                            <option value="">Seleccione mesa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="n_silla">N° de Silla</label>
                        <select id="n_silla" name="n_silla">
                            <option value="">Primero seleccione mesa</option>
                        </select>
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
                <h3>Gestión de Mesas</h3>
                <p class="section-subtitle">Administre las mesas y su capacidad</p>
                <div class="mesas-scroll-wrapper">
                    <!-- Contenido cargado dinámicamente por JavaScript -->
                </div>
            </div>

            <!-- Disposición de Mesas -->
            <div class="disposition-section">
                <h3>Disposición de Mesas</h3>
                <p class="section-subtitle">Visualice la distribución de participantes por mesa</p>

                <div class="disposition-table-wrapper disposition-scroll-wrapper">
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
                    <label for="evento_mesa">Evento <span class="required">*</span></label>
                    <select id="evento_mesa" onchange="asignarNumeroMesaAutomatico()" required>
                        <option value="">Seleccione un evento</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="numero_mesa">Número de Mesa <span class="required">*</span></label>
                    <input type="number" id="numero_mesa" placeholder="Se asigna automáticamente" readonly required>
                </div>
                <div class="form-group">
                    <label for="capacidad_mesa">Capacidad <span class="required">*</span></label>
                    <input type="number" id="capacidad_mesa" placeholder="8" min="1" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeMesaModal()">Cancelar</button>
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
                <div class="form-group">
                    <label for="edit_fecha_evento">Fecha <span class="required">*</span></label>
                    <input type="date" id="edit_fecha_evento" required>
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

@include('partials.registro_styles')
@include('partials.registro_scripts')

@endsection
