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
            <table border="1">
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
    .registro-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 200px);
        background-color: #fdfdfd;
        padding: 2rem;
    }

    .registro-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        gap: 2rem;
        max-width: 1200px;
        width: 100%;
    }

    .form-container,
    .lista-container {
        flex: 1;
        background-color: #fafafad2;
        border: 1px solid #003C3E;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        min-width: 350px;
    }

    h2 {
        margin-bottom: 1rem;
        color: #003C3E;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 1rem;
        margin-bottom: 0.3rem;
    }

    input, select {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        margin-bottom: 0.8rem;
        box-sizing: border-box;
    }

    button {
        background-color: #003C3E;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 1rem;
    }

    button:hover {
        background-color: #6aa23f;
    }

    .btn-eliminar {
        background-color: #cc3d3d;
    }

    .btn-eliminar:hover {
        background-color: #a42e2e;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    th, td {
        padding: 0.75rem;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    @media (max-width: 768px) {
        .registro-container {
            flex-direction: column;
            align-items: center;
        }

        .form-container,
        .lista-container {
            width: 100%;
        }
    }
</style>
@endsection
