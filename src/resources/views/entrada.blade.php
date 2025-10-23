@extends('layouts.app')

@section('title', 'Entrada')

@section('content')
<div class="entrada-wrapper">
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
            <table border="0">
                <thead>
                    <x-fila-header />
                </thead>
                <tbody>
                    <x-fila-socio dni="12345678" nombre="Juan Perez" edad="30" relacion="Hijo" :checked="true" />
                    <x-fila-socio dni="87654321" nombre="Maria Gomez" edad="28" relacion="Esposa" />
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .entrada-wrapper {
        width: 100%;
        padding: 2rem;
        background: #fdfdfd;
        display: flex;
        justify-content: center;
    }

    .entrada-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        max-width: 1200px;
        width: 100%;
    }

    .entrada-p1, .entrada-p2 {
        flex: 1 1 45%;
        background: #f9f9f9;
        border: 1px solid #003C3E;
        padding: 1.5rem;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .filtro-socios {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .codigo_filtro, .area_filtro {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    label {
        font-weight: bold;
        flex: 0 0 120px;
    }

    input, select {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        min-width: 150px;
    }

    .informacion-socio {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 1rem;
    }

    .foto-socio img {
        width: 180px;
        height: 230px;
        object-fit: cover;
        border-radius: 6px;
    }

    .detalles-socio p {
        margin: 0.3rem 0;
    }

    .dni, .nombres, .apellidos, .rol, .estado {
        font-weight: bold;
    }
    .dniinf, .nombreinf, .apellidosinf, .rolinf, .estadoinf {
        margin-top: 0.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    th, td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #003C3E;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .chckb1, .chckb2 {
        width: 25px;
        height: 25px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .entrada-container {
            flex-direction: column;
        }
        .entrada-p1, .entrada-p2 {
            flex: 1 1 100%;
        }
    }
</style>
@endsection
