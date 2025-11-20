@extends('layouts.app')

@section('title', 'Entrada')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Función para actualizar el panel izquierdo con los datos del socio
    function updateSocioInfo(dni) {
        $.get(`/api/socio?dni=${dni}`, function(socio) {
            if (socio) {
                $('.dniinf').text(socio.dni);
                $('.nombreinf').text(socio.nombres);
                $('.apelidosinf').text(socio.apellidos);
                $('.rolinf').text(socio.rol);
                $('.estadoinf').text(socio.estado);
                $('.foto-socio img').attr('src', socio.foto);
            }
        });
    }

    function loadParticipantesByArea(area, codigoSocio = null) {
        const tbody = $('.tabla-entrada tbody');
        tbody.empty();

        // Spinner for entrada
        if (!$('#spinner-entrada').length) {
            $('.tabla-entrada').prepend('<div id="spinner-entrada" class="large-spinner" style="display:none" aria-hidden="true"></div>');
        }
        const $eSpinner = $('#spinner-entrada');

        if (!area) {
            tbody.append(`<tr class="fila-empty"><td colspan="5">Seleccione un área para ver participantes</td></tr>`);
            return;
        }

        let url = `/api/entrada/participantes?area=${encodeURIComponent(area)}`;
        if (codigoSocio) {
            url += `&codigo_socio=${encodeURIComponent(codigoSocio)}`;
        }

        $eSpinner.show();
        $.get(url)
            .done(function(participantes) {
                tbody.empty();
                if (!participantes || participantes.length === 0) {
                    tbody.append(`<tr class="fila-empty"><td colspan="5">No hay participantes para esta área</td></tr>`);
                    return;
                }

                participantes.forEach(p => {
                    const row = $(`
                        <tr class="fila-socio" data-dni="${p.dni}">
                            <td>${p.dni}</td>
                            <td>${p.nombre}</td>
                            <td>${p.edad ?? ''}</td>
                            <td>${p.relacion ?? ''}</td>
                            <td><input type="checkbox" class="check-socio" ${p.checked ? 'checked' : ''} disabled></td>
                        </tr>
                    `);
                    tbody.append(row);
                });
            })
            .fail(function() {
                tbody.append(`<tr class="fila-empty"><td colspan="5">Error al cargar participantes</td></tr>`);
            })
            .always(function() {
                $eSpinner.hide();
            });
    }

    function loadParticipantesByCodigo(codigoSocio) {
        const tbody = $('.tabla-entrada tbody');

        // ensure loading-status exists
        if (!$('#loading-status').length) {
            $('.filtro-socios').prepend('<div id="loading-status" class="loading-indicator" style="display:none">Buscando participantes...</div>');
        }
        const $loadingStatus = $('#loading-status');

        tbody.empty();
        if (!codigoSocio) {
            tbody.append(`<tr class="fila-empty"><td colspan="5">Ingrese el código del socio para buscar</td></tr>`);
            return;
        }

        if (!$('#spinner-entrada').length) {
            $('.tabla-entrada').prepend('<div id="spinner-entrada" class="large-spinner" style="display:none" aria-hidden="true"></div>');
        }
        const $eSpinner = $('#spinner-entrada');

        const url = `/api/entrada/participantes_by_codigo?codigo_socio=${encodeURIComponent(codigoSocio)}`;
        console.log('[entrada] loadParticipantesByCodigo -> requesting', url);
        $eSpinner.show();
        $loadingStatus.show().text(`Buscando participantes con código: ${codigoSocio}`);

        $.get(url)
            .done(function(participantes) {
                console.log('[entrada] loadParticipantesByCodigo -> response', participantes);
                tbody.empty();
                if (!participantes || participantes.length === 0) {
                    tbody.append(`<tr class="fila-empty"><td colspan="5">No se encontraron participantes para este código</td></tr>`);
                    $loadingStatus.text(`No se encontraron participantes para el código ${codigoSocio}`);
                    return;
                }

                $loadingStatus.text(`Se encontraron ${participantes.length} participantes para el código: ${codigoSocio}`).show();
                setTimeout(() => $loadingStatus.fadeOut(), 3000);

                participantes.forEach(p => {
                    const row = $(`
                        <tr class="fila-socio" data-dni="${p.dni}">
                            <td>${p.dni}</td>
                            <td>${p.nombre}</td>
                            <td>${p.edad ?? ''}</td>
                            <td>${p.relacion ?? ''}</td>
                            <td><input type="checkbox" class="check-socio" ${p.checked ? 'checked' : ''} disabled></td>
                        </tr>
                    `);
                    tbody.append(row);
                });
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('[entrada] loadParticipantesByCodigo -> error', textStatus, errorThrown, jqXHR && jqXHR.responseText);
                tbody.append(`<tr class="fila-empty"><td colspan="5">Error al buscar participantes por código</td></tr>`);
                $loadingStatus.text(`Error al buscar participantes con código ${codigoSocio}`).show();
            })
            .always(function() {
                $eSpinner.hide();
            });
    }

    // Expose functions to window so they can be called from console/debug snippets
    window.loadParticipantesByCodigo = loadParticipantesByCodigo;
    window.loadParticipantesByArea = loadParticipantesByArea;

    // Manejar cambio de área
    // Debounce helper (reused)
    function debounce(fn, delay) {
        let t;
        return function() {
            const args = arguments;
            clearTimeout(t);
            // preserve calling context so handlers using `this` work correctly
            const ctx = this;
            t = setTimeout(function() { fn.apply(ctx, args); }, delay);
        };
    }

    // expose debounce for debug snippets
    window.debounce = debounce;

    $('#area').on('change', function() {
        const area = $(this).val();
        const codigo = $('#codigo_socio').val();
        // If no codigo entered, selecting an area should not show all participants.
        // The flow: first filter is codigo, then area. If area selected and no codigo -> show message.
        const tbody = $('.tabla-entrada tbody');
        tbody.empty();
        if (!codigo || codigo.trim() === '') {
            tbody.append(`<tr class="fila-empty"><td colspan="5">Ingrese el código del socio para filtrar por área</td></tr>`);
            return;
        }
        loadParticipantesByArea(area, codigo || null);
    });

    // Manejar cambio de código del socio con debounce
    $('#codigo_socio').on('input', debounce(function() {
        const codigo = $(this).val().trim();
        const area = $('#area').val();
        const tbody = $('.tabla-entrada tbody');
        tbody.empty();

        if (!codigo) {
            tbody.append(`<tr class="fila-empty"><td colspan="5">Ingrese el código del socio para buscar 2</td></tr>`);
            return;
        }

        // If only codigo entered (no area selected) -> show aggregated results across areas/events
        if (area && area !== '') {
            loadParticipantesByArea(area, codigo);
        }else{
            loadParticipantesByCodigo(codigo);
        }
    }, 300));

    // Load available areas (events + other activity areas) into select
    function loadAreas() {
        // Fetch events and add them as options with value 'evento_<id>' so the backend can map
        $.get('/api/eventos')
            .done(function(events) {
                const $area = $('#area');
                $area.empty();
                $area.append(`<option value="">-- Seleccione un área --</option>`);
                events.forEach(function(ev) {
                    $area.append(`<option value="evento_${ev.id}">${ev.nombre}</option>`);
                });
                // Add other activity areas
                const otherAreas = ['piscina','gimnasio','cancha','spa'];
                otherAreas.forEach(function(a) {
                    $area.append(`<option value="${a}">${a.charAt(0).toUpperCase() + a.slice(1)}</option>`);
                });
            })
            .fail(function() {
                // fallback: still provide common areas
                const $area = $('#area');
                $area.empty();
                $area.append(`<option value="">-- Seleccione un área --</option>`);
                const otherAreas = ['piscina','gimnasio','cancha','spa'];
                otherAreas.forEach(function(a) {
                    $area.append(`<option value="${a}">${a.charAt(0).toUpperCase() + a.slice(1)}</option>`);
                });
            });
    }

    // Inicializar lista de áreas
    loadAreas();
    // Clic en fila para resaltar y cargar info
    $(document).on('click', '.fila-socio', function() {
        $('.fila-socio.selected').removeClass('selected');
        $(this).addClass('selected');
        const dni = $(this).data('dni');
        updateSocioInfo(dni);
    });
});
</script>

<div class="entrada-container">
    <div class="entrada-p1">
        <div class="filtro-socios">
            <div id="loading-status" class="loading-indicator">
                Buscando participantes...
            </div>
            <div class="codigo_filtro">
                <label for="codigo_socio">Código del socio:</label>
                <input type="text" id="codigo_socio" name="codigo_socio" placeholder="Ingrese código del socio">
            </div>
            <div class="area_filtro">
                <label for="area">Área/Actividad:</label>
                <select id="area" name="area">
                    <option value="">-- Seleccione un area --</option>
                </select>
            </div>
        </div>

        <div class="informacion-socio">
            <div class="foto-socio">
                <img src="https://e7.pngegg.com/pngimages/513/311/png-clipart-silhouette-male-silhouette-animals-head-thumbnail.png"
                alt="Foto del Socio">
            </div>

            <div class="detalles-socio">
                <div class="dni-section">
                    <p class="dni">DNI:</p>
                    <p class="dniinf">...</p>
                </div>
                <div class="nombres-section">
                    <p class="nombres">Nombres:</p>
                    <p class="nombreinf">...</p>
                </div>
                <div class="apellidos-section">
                    <p class="apellidos">Apellidos:</p>
                    <p class="apelidosinf">...</p>
                </div>
                <div class="rol-section">
                    <p class="rol">Rol:</p>
                    <p class="rolinf">...</p>
                </div>
                <div class="estado-section">
                    <p class="estado">Estado:</p>
                    <p class="estadoinf">...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="entrada-p2">
        <h2>Árbol familiar e invitados</h2>
        <div class="tabla-entrada">
            <table>
                <thead>
                    <x-fila-header />
                </thead>
                <tbody>
                    {{-- Filas generadas dinámicamente vía JS desde /api/entrada/participantes --}}
                </tbody>

            </table>

        </div>

    </div>

</div>


<style>
    * {
        box-sizing: border-box;
    }

    /* CONTENEDOR PRINCIPAL */
    .entrada-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: nowrap;
        font-family: 'Segoe UI', Tahoma, sans-serif;
        max-width: 100%;
        overflow-x: hidden;
    }

    /*Bloques princiales*/
    .entrada-p1 {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        flex: 0 0 30%;
        min-width: 280px;
        min-height: 400px;
        max-width: 100%;
    }

    .entrada-p2 {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-x: auto;
        min-height: 400px;
        max-width: 100%;
    }

    /*Filtros */
    .filtro-socios {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .codigo_filtro,
    .area_filtro {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .codigo_filtro label,
    .area_filtro label{
        font-weight: bold;
        color: #333;
    }

    .codigo_filtro input,
    .area_filtro select{
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
    }

    .codigo_filtro input:focus,
    .area_filtro select:focus{
        border-color: #78B548;
    }

    /*Informacion del socio*/
    .informacion-socio {
        display: flex;
        gap: 20px;
        align-items: center;
        background-color: #f4f9f1;
        border-radius: 8px;
        padding: 15px;
    }

    .foto-socio img {
        width: 180px;
        height: 230px;
        /*border-radius: 50%;*/
        object-fit: cover;
        border: 2px solid #78B548;
    }

    .detalles-socio {
        display: flex;
        flex-direction: column;
        gap: 8px;
        color: #333;
    }

    .detalles-socio div {
        display: flex;
        /*gap: 6px;*/
    }

    .detalles-socio p {
        margin: 0;
    }

    .detalles-socio p:first-child {
        font-weight: 600;
    }

    .dni-section, .nombres-section, .apellidos-section, .rol-section, .estado-section {
        display: flex;
        flex-direction: column;
    }

    .dniinf, .nombreinf, .apellidosinf, .rolinf, .estadoinf {
        margin-top: 0.5rem;
        color: #555;
    }

    /*Arbol familiar */
    .entrada-p2 h2 {
        text-align: center;
        margin-bottom: 12px;
        color: #78B548;
        border-bottom: 2px solid #78B548;
        padding-bottom: 4px;
    }
    /* Estilos de la Tabla */

    /*Tabla general*/
    .tabla-entrada {
        flex: 1;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #ccc;
        max-height: calc(100vh - 280px);
    }

    .tabla-entrada table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-entrada th,
    .tabla-entrada td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .tabla-entrada thead th {
        background-color: #78B548;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .tabla-entrada tr:hover {
        background-color: #eaf6e3;
    }

    /*Responsivo*/
    @media (max-width: 900px) {
        .entrada-container {
            flex-direction: column;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .entrada-p1, .entrada-p2 {
            max-width: 100%;
            flex: none;
            min-width: unset;
            width: 100%;
        }
        .informacion-socio {
            flex-direction: row;
            align-items: center;
            gap: 15px;
        }

        .foto-socio img {
            width: 100px;
            height: 130px;
        }
    }

    @media (max-width: 768px) {
        .entrada-container {
            padding: 0;
            gap: 0.5rem;
        }

        .entrada-p1, .entrada-p2 {
            border-radius: 8px;
            padding: 0.9rem;
        }

        .filtro-socios {
            gap: 8px;
            margin-bottom: 15px;
        }

        .codigo_filtro label,
        .area_filtro label {
            font-size: 0.9rem;
        }

        .codigo_filtro input,
        .area_filtro select {
            padding: 0.6rem;
            font-size: 0.9rem;
        }

        .foto-socio img {
            width: 80px;
            height: 100px;
        }

        .detalles-socio {
            gap: 5px;
            font-size: 0.85rem;
        }

        .entrada-p2 h2 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .tabla-entrada {
            font-size: 0.8rem;
            max-height: calc(100vh - 400px);
        }

        .tabla-entrada th,
        .tabla-entrada td {
            padding: 0.5rem 0.3rem;
        }

        .loading-indicator {
            font-size: 0.8rem;
            padding: 5px;
        }
    }

    @media (max-width: 480px) {
        .entrada-p1, .entrada-p2 {
            padding: 0.7rem;
        }

        .filtro-socios {
            gap: 6px;
            margin-bottom: 12px;
        }

        .informacion-socio {
            padding: 10px;
            gap: 10px;
        }

        .entrada-p2 h2 {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .foto-socio img {
            width: 70px;
            height: 90px;
        }

        .detalles-socio {
            font-size: 0.8rem;
            gap: 4px;
        }

        .dni-section, .nombres-section, .apellidos-section, .rol-section, .estado-section {
            gap: 2px;
        }

        .tabla-entrada {
            font-size: 0.75rem;
            max-height: calc(100vh - 420px);
        }

        .tabla-entrada th,
        .tabla-entrada td {
            padding: 0.4rem 0.2rem;
        }

        .check-socio {
            width: 18px;
            height: 18px;
        }

        .area_filtro select,
        .codigo_filtro input {
            padding: 0.55rem;
            font-size: 0.85rem;
        }

        .loading-indicator {
            font-size: 0.75rem;
            padding: 4px;
        }
    }
    /* Encabesado tabla*/
    .fila-header th {
        background-color: #003C3E;
        color: white;
        padding: 0.75rem;
        text-align: left;
    }

    /* Contenido tabla*/
    .fila-socio td {
        padding: 0.75rem;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    .fila-socio:hover {
        background-color: #f1f1f1;
    }
    .check-socio {
        width: 25px;
        height: 25px;
        cursor: pointer;
    }

    /* Fila seleccionada */
    .fila-socio.selected {
        background-color: #caf3faff;
    }

    .fila-empty td {
        text-align: center;
        color: #777;
        padding: 1rem;
    }

    /* Spinner styles reused from eventos view */
    .large-spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 36px;
        height: 36px;
        border: 4px solid rgba(0,0,0,0.08);
        border-top-color: #78B548;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10;
    }

    .loading-indicator {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 10px;
        display: none;
        text-align: center;
    }

    @keyframes spin { to { transform: rotate(360deg); } }


</style>
@endsection
