@extends('layouts.app')

@section('title', 'Eventos')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let currentEventoId = null;

    // Cargar eventos en el select
    const $eventoSpinner = $('<div id="spinner-eventos" class="small-spinner" style="display:none" aria-hidden="true"></div>');
    $('#evento').after($eventoSpinner);

    function loadEventos() {
        $eventoSpinner.show();
        $.get('/api/eventos')
            .done(function(eventos) {
                const select = $('#evento');
                select.find('option:not([value=""])').remove();
                eventos.forEach(evento => {
                    select.append(`<option value="${evento.id}">${evento.nombre}</option>`);
                });
            })
            .always(function() {
                $eventoSpinner.hide();
            });
    }

    loadEventos();

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

    function loadParticipantes(eventoId, codigoSocio = null) {
        const tbody = $('.tabla-eventos tbody');
        tbody.empty();

        // Spinner para carga de participantes
        if (!$('#spinner-participantes').length) {
            $('.tabla-eventos').prepend('<div id="spinner-participantes" class="large-spinner" style="display:none" aria-hidden="true"></div>');
        }
        const $pSpinner = $('#spinner-participantes');

        if (!eventoId) {
            // Mostrar mensaje indicando que se debe seleccionar un evento
            tbody.append(`<tr class="fila-empty"><td colspan="6">Seleccione un evento para ver participantes</td></tr>`);
            return;
        }

        let url = `/api/participantes?evento_id=${eventoId}`;
        if (codigoSocio) {
            url += `&codigo_socio=${codigoSocio}`;
        }

        $pSpinner.show();
        $.get(url)
            .done(function(participantes) {
                tbody.empty();

                if (!participantes || participantes.length === 0) {
                    tbody.append(`<tr class="fila-empty"><td colspan="6">No hay participantes para este evento</td></tr>`);
                    return;
                }

                participantes.forEach(p => {
                // Usar las mismas clases/estructura que el componente blade para mantener estilos
                const row = $(`
                    <tr class="fila-socios-eventos" data-dni="${p.dni}">
                        <td>${p.dni}</td>
                        <td>${p.nombre}</td>
                        <td>${p.mesa}</td>
                        <td>${p.asiento}</td>
                        <td>
                            <input type="checkbox" class="check-socio" ${p.checked1 ? 'checked' : ''} disabled>
                        </td>
                        <td>
                            <input type="checkbox" class="check-socio" ${p.checked2 ? 'checked' : ''} disabled>
                        </td>
                    </tr>
                `);
                tbody.append(row);
                });
            })
            .fail(function() {
                tbody.append(`<tr class="fila-empty"><td colspan="6">Error al cargar participantes</td></tr>`);
            })
            .always(function() {
                $pSpinner.hide();
            });
    }

    // Manejar cambio de evento
    $('#evento').on('change', function() {
        currentEventoId = $(this).val();
        $('#codigo_socio').val(''); // Limpiar código de socio
        loadParticipantes(currentEventoId);
    });

    // Debounce helper
    function debounce(fn, delay) {
        let t;
        return function() {
            const args = arguments;
            clearTimeout(t);
            t = setTimeout(function() { fn.apply(null, args); }, delay);
        };
    }

    // Buscar socio por código con debounce
    $('#codigo_socio').on('input', debounce(function() {
        const codigo = $(this).val();
        if (currentEventoId) {
            loadParticipantes(currentEventoId, codigo);
        }
    }, 300));

    // Manejar clic en fila de participante y resaltar la fila seleccionada
    $(document).on('click', '.fila-socios-eventos', function() {
        // Resaltar selección (una sola fila seleccionada)
        $('.fila-socios-eventos.selected').removeClass('selected');
        $(this).addClass('selected');

        const dni = $(this).data('dni');
        updateSocioInfo(dni);
    });
});
</script>

<div class="eventos-container">
    <div class="eventos-p1">
        <div class="filtro-socios">
            <div class="evento_filtro">
                <label for="evento">Evento:</label>
                <select id="evento" name="evento">
                    <option value="">-- Seleccione evento --</option>
                </select>
            </div>
            <div class="codigo_filtro">
                <label for="codigo_socio">Código del socio:</label>
                <input type="text" id="codigo_socio" name="codigo_socio" placeholder="Ingrese código del socio">
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
                    <p class="dniinf">12345678</p>
                </div>
                <div class="nombres-section">
                    <p class="nombres">Nombres:</p>
                    <p class="nombreinf">Primero Segundo</p>
                </div>
                <div class="apellidos-section">
                    <p class="apellidos">Apellidos:</p>
                    <p class="apelidosinf">Paterno Materno</p>
                </div>
                <div class="rol-section">
                    <p class="rol">Rol:</p>
                    <p class="rolinf">Socio</p>
                </div>
                <div class="estado-section">
                    <p class="estado">Estado:</p>
                    <p class="estadoinf">Activo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="eventos-p2">
        <h2>Lista de participantes</h2>
        <div class="tabla-eventos">
            <table>
                <thead>
                    <x-fila-header-eventos/>
                </thead>
                <tbody>
                    {{-- Las filas se generan dinámicamente vía JS desde /api/participantes --}}
                </tbody>
            </table>
        </div>
    </div>
</div>


<style>
        * {
            box-sizing: border-box;
        }

        /*Contenedor Principal*/
    .eventos-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: nowrap;
        font-family: 'Segoe UI', Tahoma, sans-serif;
        max-width: 100%;
        overflow-x: hidden;
    }

    /*Contenedores Secundarios*/
    .eventos-p1 {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        flex: 0 0 30%;
        min-width: 280px;
        min-height: 400px;
        overflow-y: auto;
        max-width: 100%;
    }
    .eventos-p2 {
        background: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-x: auto;
        min-height: 400px;
        max-width: 100%;
    }

    /*Filtro socios*/
    .filtro-socios {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .evento_filtro,
    .codigo_filtro {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .evento_filtro label,
    .codigo_filtro label {
        font-weight: bold;
        color: #333;
    }

    .evento_filtro select,
    .codigo_filtro input {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
    }

    .evento_filtro select:focus,
    .codigo_filtro input:focus {
        border-color: #78B548;
    }

    /*Informacion Del Socio*/
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
    .dniinf, .nombreinf, .apelidosinf, .rolinf, .estadoinf {
        margin-top: 0.5rem;
        color: #555;
    }

    /*Lista De Asistentes*/
    .eventos-p2 h2 {
        text-align: center;
        margin-bottom: 12px;
        color: #78B548;
        border-bottom: 2px solid #78B548;
        padding-bottom: 4px;
    }

    /*Tabla De Asistentes*/
    .tabla-eventos {
        flex: 1;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #ccc;
        max-height: calc(100vh - 280px); /* hace visible toda la tabla en pantallas grandes */
    }

    .tabla-eventos table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-eventos th,
    .tabla-eventos td {
        padding: 8px;
        /*text-align: left;*/
        border-bottom: 1px solid #ddd;
    }

    .tabla-eventos thead th {
        background-color: #78B548;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .tabla-eventos tr:hover {
        background-color: #eaf6e3;
    }

    /*Responsivo*/
    @media (max-width: 1030px) {
        .eventos-container {
            flex-direction: column;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .eventos-p1, .eventos-p2 {
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
        .eventos-container {
            padding: 0;
            gap: 0.5rem;
        }

        .eventos-p1, .eventos-p2 {
            border-radius: 8px;
            padding: 0.9rem;
        }

        .filtro-socios {
            gap: 8px;
            margin-bottom: 15px;
        }

        .evento_filtro label,
        .codigo_filtro label {
            font-size: 0.9rem;
        }

        .evento_filtro select,
        .codigo_filtro input {
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

        .eventos-p2 h2 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .tabla-eventos {
            font-size: 0.8rem;
            max-height: calc(100vh - 400px);
        }

        .tabla-eventos th,
        .tabla-eventos td {
            padding: 0.5rem 0.3rem;
        }
    }

    @media (max-width: 480px) {
        .eventos-p1, .eventos-p2 {
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

        .eventos-p2 h2 {
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

        .tabla-eventos {
            font-size: 0.75rem;
            max-height: calc(100vh - 420px);
        }

        .tabla-eventos th,
        .tabla-eventos td {
            padding: 0.4rem 0.2rem;
        }

        .check-socio {
            width: 18px;
            height: 18px;
        }

        .evento_filtro select,
        .codigo_filtro input {
            padding: 0.55rem;
            font-size: 0.85rem;
        }
    }

    /*Contenido Tabla*/
    .fila-socio-eventos td {
        padding: 0.75rem;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    .fila-socio-eventos:hover {
        background-color: #f1f1f1;
    }
    .check-socio {
        width: 25px;
        height: 25px;
        cursor: pointer;
        align-items: center;
    }

    /*Encabesado tabla*/
    .fila-header-eventos th {
        background-color: #003C3E;
        color: white;
        padding: 0.75rem;
        text-align: left;
    }

    /* Fila seleccionada */
    .fila-socios-eventos.selected {
        background-color: #caf3faff; /* tono verde claro */
    }

    .fila-empty td {
        text-align: center;
        color: #777;
        padding: 1rem;
    }

    /* Small spinner (inline near selects) */
    .small-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(0,0,0,0.1);
        border-top-color: #78B548;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
        vertical-align: middle;
    }

    /* Large spinner (over table) */
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

    @keyframes spin { to { transform: rotate(360deg); } }

</style>
@endsection
