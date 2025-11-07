@extends('layouts.app')

@section('title', 'Eventos')

@section('content')

<div class="eventos-container">
    <div class="eventos-p1">
        <div class="filtro-socios">
            <div class="evento_filtro">
                <label for="evento">Evento:</label>
                <select id="evento" name="evento">
                    <option value="todas">Todos</option>
                    <option value="evento1">Evento 1</option>
                    <option value="evento2">Evento 2</option>
                    <option value="evento3">Evento 3</option>
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
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                    <x-fila-socio-eventos dni="12345678" nombre="Juan" mesa="DU" asiento="DU" :checked1="true" :checked2="false" />
                    <x-fila-socio-eventos dni="87654321" nombre="Maria" mesa="DU" asiento="DU" :checked1="false" :checked2="true" />
                </tbody>
            </table>
        </div>
    </div>
</div>


<style>
        /*Contenedor Principal*/
    .eventos-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: nowrap;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    /*Contenedores Secundarios*/
    .eventos-p1 {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        flex: 0 0 30%; /* ocupa menos espacio */
        min-width: 432.85px;
        min-height: 470.917px;
        overflow-y: auto;/*prueba*/
    }
    .eventos-p2 {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        flex: 1; /* ocupa el resto */
        display: flex;
        flex-direction: column;
        overflow-x: auto;
        min-height: 470.917px;
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
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .tabla-eventos thead th {
        background-color: #78B548;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .tabla-eventos tr:nth-child(even) {
        background-color: #f8f8f8;
    }

    .tabla-eventos tr:hover {
        background-color: #eaf6e3;
    }

    /*Responsivo*/
    @media (max-width: 1030px) {
        .eventos-container {
            flex-direction: column;
            flex-wrap: wrap;
        }

        .eventos-p1, .eventos-p2 {
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

    /*Contenido Tabla*/
    .fila-socio-eventos td {
        padding: 0.75rem;
        border-bottom: 1px solid #ddd;
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

</style>
@endsection
