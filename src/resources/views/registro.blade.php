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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Registrar Participante
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
                            <button class="btn-icon-action edit" title="Editar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="btn-icon-action delete" title="Eliminar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                </svg>
                            </button>
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
                            <button class="btn-icon-action edit" title="Editar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="btn-icon-action delete" title="Eliminar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                </svg>
                            </button>
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
                                <td>
                                    <button class="btn-remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>S001-INV1</td>
                                <td>María López Martínez</td>
                                <td><span class="badge-type invitado">invitado</span></td>
                                <td>Mesa 1 - Silla 2</td>
                                <td>
                                    <button class="btn-remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>S002</td>
                                <td>Carlos Rodríguez Silva</td>
                                <td><span class="badge-type socio">socio</span></td>
                                <td>Mesa 1 - Silla 3</td>
                                <td>
                                    <button class="btn-remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
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

@include('partials.registro_styles')
@include('partials.registro_scripts')

@endsection
