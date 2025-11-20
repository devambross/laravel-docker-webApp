@extends('layouts.app')

@section('title', 'Sistema de Gestión')

@section('content')

<div class="registro-modern-container">
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

    <!-- PESTAÑA 1: REGISTRO -->
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

    <!-- PESTAÑA 2: ENTRADA CLUB -->
    <div class="tab-content" id="entrada-club-tab">
        @include('partials.entrada_club_tab')
    </div>

    <!-- PESTAÑA 3: ENTRADA EVENTO -->
    <div class="tab-content" id="entrada-evento-tab">
        @include('partials.entrada_evento_tab')
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

@include('partials.registro_styles')
@include('partials.registro_scripts')

@endsection
