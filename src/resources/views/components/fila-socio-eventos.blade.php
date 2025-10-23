<tr class="fila-socios-eventos">
    <td>{{ $dni }}</td>
    <td>{{ $nombre }}</td>
    <td>{{ $mesa }}</td>
    <td>{{ $asiento }}</td>
    <td>
        <input
            type="checkbox"
            class="check-socio"
            {{ $checked1 ? 'checked' : '' }}>
    </td>
    <td>
        <input
            type="checkbox"
            class="check-socio"
            {{ $checked2 ? 'checked' : '' }}>
    </td>
</tr>
<!--<style>
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
</style>-->
