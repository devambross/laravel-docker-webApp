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
