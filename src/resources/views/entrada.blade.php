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

        if (!area || area === 'todas') {
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

    // Manejar cambio de área
    // Debounce helper (reused)
    function debounce(fn, delay) {
        let t;
        return function() {
            const args = arguments;
            clearTimeout(t);
            t = setTimeout(function() { fn.apply(null, args); }, delay);
        };
    }

    $('#area').on('change', function() {
        const area = $(this).val();
        const codigo = $('#codigo_socio').val();
        // Si hay código, filtrar por código; si no, mostrar todos de área
        loadParticipantesByArea(area, codigo || null);
    });

    // Manejar cambio de código del socio con debounce
    $('#codigo_socio').on('input', debounce(function() {
        const codigo = $(this).val();
        const area = $('#area').val();
        if (!area || area === 'todas') {
            const tbody = $('.tabla-entrada tbody');
            tbody.empty();
            tbody.append(`<tr class="fila-empty"><td colspan="5">Seleccione un área para filtrar por código</td></tr>`);
            return;
        }
        loadParticipantesByArea(area, codigo);
    }, 300));

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
            <div class="codigo_filtro">
                <label for="codigo_socio">Código del socio:</label>
                <input type="text" id="codigo_socio" name="codigo_socio" placeholder="Ingrese código del socio">
            </div>
            <div class="area_filtro">
                <label for="area">Área/Actividad:</label>
                <select id="area" name="area">
                    <option value="todas">Todas</option>
                    <option value="area1">Área 1</option>
                    <option value="area2">Área 2</option>
                    <option value="area3">Área 3</option>
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
    /* CONTENEDOR PRINCIPAL */
    .entrada-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: nowrap;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    /*Bloques princiales*/
    .entrada-p1 {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        flex: 0 0 30%;
        min-width: 418.533px;
        min-height: 470.917px;
    }

    .entrada-p2 {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-x: auto;
        min-height: 470.917px;
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
        }
        .entrada-p1, .entrada-p2 {
            max-width: 100%;
            flex: none;
        }
        .informacion-socio {
            flex-direction: column;
            align-items: flex-start;
        }

        .foto-socio img {
            width: 80px;
            height: 80px;
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

    @keyframes spin { to { transform: rotate(360deg); } }


</style>
@endsection
