<tr class="fila-socio">
    <td>{{ $dni }}</td>
    <td>{{ $nombre }}</td>
    <td>{{ $edad }}</td>
    <td>{{ $relacion }}</td>
    <td>
        <input
            type="checkbox"
            class="check-socio"
            {{ $checked ? 'checked' : '' }}>
    </td>
</tr>
<style>
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
