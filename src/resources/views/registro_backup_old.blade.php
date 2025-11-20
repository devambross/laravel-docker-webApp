@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="registro-wrapper">
    <div class="registro-container">
        <!-- Primer bloque -->
        <div class="form-container">
            <h2>Añadir a lista</h2>

            <label>Área/Actividad:</label>
            <select>
                <option value="todas">Todas</option>
                <option value="area1">Área 1</option>
                <option value="area2">Área 2</option>
                <option value="area3">Área 3</option>
            </select>

            <label>Código del socio:</label>
            <input type="text" name="codigo_socio" required>

            <label>DNI:</label>
            <input type="text" name="dni" required>

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>

            <button type="submit">Añadir</button>
        </div>

        <!-- Segundo bloque -->
        <div class="lista-container">
            <h2>Lista de invitados</h2>
            <table border="0">
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
                <tr>
                    <td>12345678</td>
                    <td>Juan Perez</td>
                    <td>2024-06-01</td>
                    <td><button type="button" class="btn-eliminar">Eliminar</button></td>
                </tr>
                <tr>
                    <td>87654321</td>
                    <td>Maria Gomez</td>
                    <td>2024-06-02</td>
                    <td><button type="button" class="btn-eliminar">Eliminar</button></td>
                </tr>
            </table>

            <button type="submit">Registrar</button>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

    .registro-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 200px);
        background-color: #fdfdfd;
        padding: 1rem;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    .registro-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        gap: 20px;
        max-width: 1200px;
        width: 100%;
    }

    .form-container,
    .lista-container {
        flex: 1;
        background-color: #fff;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        min-width: 0;
        max-width: 100%;
    }

    h2 {
        margin-bottom: 1rem;
        color: #78B548;
        border-bottom: 2px solid #78B548;
        padding-bottom: 8px;
        text-align: center;
        font-size: 1.3rem;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 0.8rem;
        margin-bottom: 0.3rem;
        color: #333;
    }

    input, select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 100%;
        margin-bottom: 0.6rem;
        transition: border-color 0.3s ease;
        outline: none;
    }

    input:focus, select:focus {
        border-color: #78B548;
    }

    button {
        background-color: #78B548;
        color: white;
        border: none;
        padding: 0.7rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 0.8rem;
        font-size: 0.95rem;
        font-weight: 600;
        width: 100%;
    }

    button:hover {
        background-color: #6aa23f;
    }

    .btn-eliminar {
        background-color: #cc3d3d;
        padding: 0.45rem 0.8rem;
        font-size: 0.85rem;
        width: auto;
    }

    .btn-eliminar:hover {
        background-color: #a42e2e;
    }

    .lista-container table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        font-size: 0.9rem;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    th, td {
        padding: 0.65rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #78B548;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
        font-size: 0.9rem;
    }

    tr:hover {
        background-color: #eaf6e3;
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .registro-wrapper {
            padding: 0.5rem;
        }

        .registro-container {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
            width: 100%;
        }

        .form-container,
        .lista-container {
            width: 100%;
            min-width: unset;
            padding: 0.9rem;
        }

        h2 {
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
        }

        label {
            margin-top: 0.6rem;
        }

        input, select {
            padding: 8px;
        }

        button {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }

        .lista-container table {
            font-size: 0.8rem;
        }

        th, td {
            padding: 0.5rem 0.3rem;
            font-size: 0.8rem;
        }

        .btn-eliminar {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .registro-wrapper {
            padding: 0.25rem;
        }

        .form-container,
        .lista-container {
            padding: 0.7rem;
        }

        h2 {
            font-size: 1rem;
        }

        label {
            margin-top: 0.5rem;
        }

        input, select {
            padding: 8px;
        }

        button {
            padding: 0.55rem 0.8rem;
            font-size: 0.85rem;
        }

        .lista-container table {
            font-size: 0.75rem;
        }

        th, td {
            padding: 0.4rem 0.2rem;
            font-size: 0.75rem;
        }

        .btn-eliminar {
            padding: 0.35rem 0.5rem;
            font-size: 0.7rem;
        }
    }
</style>
@endsection
