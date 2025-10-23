@extends('layouts.app')

@section('title', 'Entrada')

@section('content')

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
            <table border="0">
                <thead>
                    <x-fila-header />
                </thead>
                <tbody>
                    <x-fila-socio dni="12345678" nombre="Juan Perez" edad="30" relacion="Hijo" :checked="true" />
                    <x-fila-socio dni="87654321" nombre="Maria Gomez" edad="28" relacion="Esposa" :checked="false" />
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

    .tabla-entrada tr:nth-child(even) {
        background-color: #f8f8f8;
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
    }
    .fila-socio:hover {
        background-color: #f1f1f1;
    }
    .check-socio {
        width: 25px;
        height: 25px;
        cursor: pointer;
        align-items: center;
    }


</style>
@endsection
